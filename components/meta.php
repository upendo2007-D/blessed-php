<?php

require_once __DIR__ . '/../includes/bootstrap.php';
$pageTitle = $pageTitle ?? setting('school_name');
$pageDescription = $pageDescription ?? setting('school_description');
$pageClass = $pageClass ?? '';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="<?= escape($pageDescription) ?>">
  <title><?= escape($pageTitle) ?></title>
  <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body class="<?= escape($pageClass) ?>">
