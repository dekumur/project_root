<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/config.php';

// Проверяем права (если забыли include admin_auth)
if (empty($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . '/pages/login.php');
    exit;
}

$userName = htmlspecialchars($_SESSION['user_name'] ?? 'Администратор');
$userRole = htmlspecialchars($_SESSION['user_role'] ?? '');
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? htmlspecialchars($page_title) : 'Админ-панель' ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/main.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin.css">
    <link rel="icon" href="<?= BASE_URL ?>/assets/img/favicon-32x32.png" type="image/png">
</head>
<body class="admin-body">

<header class="admin-header">
  <div class="wrap">
    <div class="admin-brand">
      <a href="<?= BASE_URL ?>/admin/index.php" class="logo">🛠 Админ-панель</a>
      <span class="role"><?= $userRole ? "($userRole)" : '' ?></span>
    </div>

    <nav class="admin-nav">
      <a href="<?= BASE_URL ?>/admin/index.php">Главная</a>
      <a href="<?= BASE_URL ?>/admin/events.php">Мероприятия</a>
      <a href="<?= BASE_URL ?>/admin/users.php">Пользователи</a>
      <a href="<?= BASE_URL ?>/admin/bid.php">Заявки</a>
    </nav>

    <div class="admin-user">
      <span><?= $userName ?></span>
      <a href="<?= BASE_URL ?>/pages/logout.php" class="btn-logout">Выйти</a>
    </div>
  </div>
</header>

<main class="admin-main">
