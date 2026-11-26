<?php

require_once __DIR__ . '/auth.php';
start_admin_session();
verify_csrf();
$_SESSION = [];
session_destroy();
header('Location: /adminCMS/login.php');
exit;
