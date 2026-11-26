<?php

require_once __DIR__ . '/auth.php';
start_admin_session();

if (!empty($_SESSION['admin_id'])) {
    header('Location: /adminCMS/dashboard.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $email = trim((string) ($_POST['email'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');
    $statement = database()->prepare('SELECT id, email, password_hash FROM admins WHERE email = :email');
    $statement->execute(['email' => $email]);
    $admin = $statement->fetch();

    if ($admin && password_verify($password, $admin['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['admin_id'] = (int) $admin['id'];
        $_SESSION['admin_email'] = $admin['email'];
        header('Location: /adminCMS/dashboard.php');
        exit;
    }

    $error = 'The email or password is incorrect.';
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin sign in</title>
  <link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body class="admin-login-page">
  <main class="login-card">
    <h1>Website admin</h1>
    <p>Sign in to manage school website content.</p>
    <?php if ($error !== ''): ?><p class="notice error" role="alert"><?= escape($error) ?></p><?php endif; ?>
    <form method="post" action="/adminCMS/login.php">
      <?= csrf_field() ?>
      <label>Email<input type="email" name="email" maxlength="190" autocomplete="username" required></label>
      <label>Password<input type="password" name="password" autocomplete="current-password" required></label>
      <button type="submit">Sign in</button>
    </form>
  </main>
</body>
</html>
