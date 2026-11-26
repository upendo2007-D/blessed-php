<?php

require_once __DIR__ . '/auth.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method not allowed.');
}
verify_csrf();

$currentPassword = $_POST['current_password'] ?? null;
$newPassword = $_POST['new_password'] ?? null;
$confirmPassword = $_POST['confirm_password'] ?? null;
if (!is_string($currentPassword) || !is_string($newPassword) || !is_string($confirmPassword)) {
    http_response_code(400);
    exit('Invalid password form submission.');
}

$redirect = static function (string $status): void {
    header('Location: /adminCMS/dashboard.php?password=' . rawurlencode($status) . '#change-password');
    exit;
};

if (strlen($newPassword) < 12) {
    $redirect('short');
}
if (!hash_equals($newPassword, $confirmPassword)) {
    $redirect('mismatch');
}

$pdo = database();
$select = $pdo->prepare('SELECT password_hash FROM admins WHERE id = :id');
$select->execute(['id' => (int) $_SESSION['admin_id']]);
$admin = $select->fetch();
if (!$admin) {
    http_response_code(401);
    exit('Admin account is unavailable.');
}
if (!password_verify($currentPassword, $admin['password_hash'])) {
    $redirect('current');
}

$newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
$update = $pdo->prepare('UPDATE admins SET password_hash = :new_hash WHERE id = :id AND password_hash = :current_hash');
$update->execute([
    'new_hash' => $newPasswordHash,
    'id' => (int) $_SESSION['admin_id'],
    'current_hash' => $admin['password_hash'],
]);
if ($update->rowCount() !== 1) {
    http_response_code(409);
    exit('The password could not be changed because the account was updated. Try again.');
}

session_regenerate_id(true);
$redirect('changed');
