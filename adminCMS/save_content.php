<?php

require_once __DIR__ . '/auth.php';
require_admin();

function save_site_settings(array $settings): void
{
    $configPath = __DIR__ . '/../config/site.php';
    $temporaryPath = tempnam(dirname($configPath), '.site-settings-');
    if ($temporaryPath === false) {
        http_response_code(500);
        exit('Could not prepare the site settings file for saving.');
    }

    $contents = "<?php\n\nreturn " . var_export($settings, true) . ";\n";
    if (file_put_contents($temporaryPath, $contents, LOCK_EX) !== strlen($contents)) {
        unlink($temporaryPath);
        http_response_code(500);
        exit('Could not write the site settings file. Check that PHP can write to the config directory.');
    }

    if (!rename($temporaryPath, $configPath)) {
        unlink($temporaryPath);
        http_response_code(500);
        exit('Could not replace the site settings file. Check that PHP can write to the config directory.');
    }
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method not allowed.');
}
verify_csrf();

$collections = [
    'programs' => ['icon', 'title', 'description', 'sort_order'],
    'admissions' => ['label', 'title', 'description', 'sort_order'],
    'staff_members' => ['name', 'role', 'initials', 'sort_order'],
    'news_items' => ['published_at', 'title', 'description', 'sort_order', 'is_published'],
    'academic_courses' => ['title', 'description', 'details', 'sort_order'],
    'gallery_items' => ['image_path', 'alt_text', 'caption', 'sort_order', 'is_published'],
];

$action = (string) ($_POST['action'] ?? '');

if ($action === 'settings') {
    $allowed = ['school_name', 'school_slogan', 'school_description', 'logo_path', 'phone', 'email', 'address', 'office_hours', 'map_embed_url'];
    $settings = [];
    foreach ($allowed as $key) {
        $settings[$key] = trim((string) ($_POST['settings'][$key] ?? ''));
    }
    if (!filter_var($settings['email'], FILTER_VALIDATE_EMAIL)) {
        http_response_code(422);
        exit('Enter a valid school email address.');
    }
    if ($settings['map_embed_url'] !== '' && !str_starts_with($settings['map_embed_url'], 'https://www.google.com/maps/embed?')) {
        http_response_code(422);
        exit('The map URL must be a Google Maps embed URL.');
    }

    save_site_settings($settings);
} elseif ($action === 'blocks') {
    $pdo = database();
    $statement = $pdo->prepare('UPDATE page_content SET title = :title, body = :body WHERE id = :id');
    foreach (($_POST['blocks'] ?? []) as $id => $block) {
        if (!ctype_digit((string) $id) || !is_array($block)) {
            continue;
        }
        $statement->execute([
            'title' => trim((string) ($block['title'] ?? '')),
            'body' => trim((string) ($block['body'] ?? '')),
            'id' => (int) $id,
        ]);
    }
} elseif ($action === 'save_item') {
    $pdo = database();
    $collection = (string) ($_POST['collection'] ?? '');
    if (!isset($collections[$collection])) {
        http_response_code(400);
        exit('Unknown content collection.');
    }

    $values = [];
    foreach ($collections[$collection] as $column) {
        if ($column === 'is_published') {
            $values[$column] = isset($_POST['item'][$column]) ? 1 : 0;
        } elseif ($column === 'sort_order') {
            $order = filter_var($_POST['item'][$column] ?? '0', FILTER_VALIDATE_INT);
            if ($order === false) {
                http_response_code(422);
                exit('Sort order must be a whole number.');
            }
            $values[$column] = $order;
        } else {
            $values[$column] = trim((string) ($_POST['item'][$column] ?? ''));
        }
    }

    $requiredFields = [
        'programs' => ['title', 'description'],
        'admissions' => ['label', 'title', 'description'],
        'staff_members' => ['name', 'role'],
        'news_items' => ['published_at', 'title', 'description'],
        'academic_courses' => ['title', 'description', 'details'],
        'gallery_items' => ['image_path', 'alt_text', 'caption'],
    ];
    foreach ($requiredFields[$collection] as $field) {
        if ($values[$field] === '') {
            http_response_code(422);
            exit('Complete all required content fields.');
        }
    }

    if ($collection === 'news_items') {
        $date = DateTimeImmutable::createFromFormat('!Y-m-d', $values['published_at']);
        if (!$date || $date->format('Y-m-d') !== $values['published_at']) {
            http_response_code(422);
            exit('Enter a valid news date as YYYY-MM-DD.');
        }
    }
    if ($collection === 'gallery_items') {
        $imageRoot = realpath(__DIR__ . '/../assets/img');
        $imagePath = str_starts_with($values['image_path'], '/assets/img/')
            ? realpath($imageRoot . DIRECTORY_SEPARATOR . substr($values['image_path'], strlen('/assets/img/')))
            : false;
        if (!$imageRoot || !$imagePath || !is_file($imagePath) || !str_starts_with($imagePath, $imageRoot . DIRECTORY_SEPARATOR)) {
            http_response_code(422);
            exit('Choose an existing image under /assets/img/.');
        }
    }

    $id = (string) ($_POST['id'] ?? '');
    if ($id !== '' && !ctype_digit($id)) {
        http_response_code(400);
        exit('Invalid content item.');
    }

    $columns = array_keys($values);
    if ($id === '') {
        $quotedColumns = implode(', ', array_map(static fn(string $column): string => "`$column`", $columns));
        $placeholders = implode(', ', array_map(static fn(string $column): string => ":$column", $columns));
        $statement = $pdo->prepare("INSERT INTO `$collection` ($quotedColumns) VALUES ($placeholders)");
        $statement->execute($values);
    } else {
        $assignments = implode(', ', array_map(static fn(string $column): string => "`$column` = :$column", $columns));
        $values['id'] = (int) $id;
        $statement = $pdo->prepare("UPDATE `$collection` SET $assignments WHERE id = :id");
        $statement->execute($values);
    }
} elseif ($action === 'delete_item') {
    $pdo = database();
    $collection = (string) ($_POST['collection'] ?? '');
    $id = (string) ($_POST['id'] ?? '');
    if (!isset($collections[$collection]) || !ctype_digit($id)) {
        http_response_code(400);
        exit('Invalid content item.');
    }
    $statement = $pdo->prepare("DELETE FROM `$collection` WHERE id = :id");
    $statement->execute(['id' => (int) $id]);
} elseif ($action === 'mark_message_read') {
    $pdo = database();
    $id = filter_var($_POST['id'] ?? '', FILTER_VALIDATE_INT);
    if (!$id) {
        http_response_code(400);
        exit('Invalid message.');
    }
    $statement = $pdo->prepare('UPDATE contact_messages SET is_read = 1 WHERE id = :id');
    $statement->execute(['id' => $id]);
} else {
    http_response_code(400);
    exit('Unknown action.');
}

header('Location: /adminCMS/dashboard.php?saved=1');
exit;
