<?php

require_once __DIR__ . '/auth.php';
start_admin_session();
header('Location: ' . (empty($_SESSION['admin_id']) ? '/adminCMS/login.php' : '/adminCMS/dashboard.php'));
exit;
