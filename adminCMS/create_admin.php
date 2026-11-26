<?php

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

require_once __DIR__ . '/../includes/bootstrap.php';

$email = $argv[1] ?? '';
$password = getenv('BLESSED_ADMIN_PASSWORD') ?: '';
if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 190 || strlen($password) < 12) {
    fwrite(STDERR, "Usage: set BLESSED_ADMIN_PASSWORD to a password of at least 12 characters, then run php adminCMS/create_admin.php admin@example.com\n");
    exit(1);
}

$statement = database()->prepare('INSERT INTO admins (email, password_hash) VALUES (:email, :password_hash)');
$statement->execute([
    'email' => $email,
    'password_hash' => password_hash($password, PASSWORD_DEFAULT),
]);
fwrite(STDOUT, "Admin account created for $email\n");
