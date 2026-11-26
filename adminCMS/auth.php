<?php

require_once __DIR__ . '/../includes/bootstrap.php';

function start_admin_session(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    ini_set('session.use_strict_mode', '1');
    session_set_cookie_params([
        'httponly' => true,
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'samesite' => 'Strict',
    ]);
    session_start();
}

function require_admin(): void
{
    start_admin_session();
    if (empty($_SESSION['admin_id'])) {
        header('Location: /adminCMS/login.php');
        exit;
    }
}
