<?php

function escape(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function site_settings(): array
{
    static $settings;

    if ($settings === null) {
        $settings = require __DIR__ . '/../config/site.php';
        if (!is_array($settings)) {
            throw new UnexpectedValueException('The site settings file must return an array.');
        }
    }

    return $settings;
}

function setting(string $key): string
{
    return site_settings()[$key] ?? '';
}

function page_content(string $page, string $key): array
{
    static $content;

    if ($content === null) {
        $content = [];
        foreach (database()->query('SELECT page_key, block_key, title, body FROM page_content') as $row) {
            $content[$row['page_key']][$row['block_key']] = $row;
        }
    }

    return $content[$page][$key] ?? ['title' => '', 'body' => ''];
}

function page_rows(string $table): array
{
    $tables = ['programs', 'admissions', 'staff_members', 'news_items', 'academic_courses', 'gallery_items'];
    if (!in_array($table, $tables, true)) {
        throw new InvalidArgumentException('Unknown content collection.');
    }

    $publishedOnly = in_array($table, ['news_items', 'gallery_items'], true) ? ' WHERE is_published = 1' : '';
    return database()->query("SELECT * FROM `$table`$publishedOnly ORDER BY sort_order, id")->fetchAll();
}

function csrf_token(): string
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . escape(csrf_token()) . '">';
}

function verify_csrf(): void
{
    $token = $_POST['csrf_token'] ?? '';
    if (!is_string($token) || !hash_equals(csrf_token(), $token)) {
        http_response_code(400);
        exit('Invalid request token. Refresh the page and try again.');
    }
}
