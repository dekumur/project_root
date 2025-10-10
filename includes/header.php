<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Если BASE_URL не задан (например, при прямом обращении)
if (!defined('BASE_URL')) {
    define('BASE_URL', '');
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/main.css">
    <link rel="icon" href="<?= BASE_URL ?>/assets/img/favicon-32x32.png" type="image/png">
    <title><?= isset($page_title) ? htmlspecialchars($page_title) : 'Главная' ?></title>
</head>
<body>
<header class="site-header">
  <div class="wrap">
     <a href="<?= BASE_URL ?>/index.php">
    <img src="../assets/img/G1.png" alt="Логотип Центра" class="brand-logo" />
</a>

    <?php if (!empty($_SESSION['user_id'])): ?>
        <!-- Навигация для авторизованных пользователей -->
        <nav class="main-nav">
            
            <a href="<?= BASE_URL ?>/pages/calendar.php">Календарь</a>
            <a href="<?= BASE_URL ?>/pages/news.php">Новости</a>
            <a href="<?= BASE_URL ?>/pages/education.php">Образование</a>
        </nav>
        <div class="auth-links">
            <a href="<?= BASE_URL ?>/pages/logout.php" class="btn-logout">Выйти</a>
        </div>

    <?php else: ?>
        <!-- Навигация для гостей -->
        <nav class="main-nav">
            <a href="<?= BASE_URL ?>/pages/calendar.php">Календарь</a>
            <a href="<?= BASE_URL ?>/pages/news.php">Новости</a>
            <a href="<?= BASE_URL ?>/pages/education.php">Образование</a>
        </nav>
        <div class="auth-links">

            <a href="<?= BASE_URL ?>/pages/login.php" class="btn-login">Войти</a>
            <a href="<?= BASE_URL ?>/pages/register.php" class="btn-register">Регистрация</a>
        </div>
    <?php endif; ?>
  </div>
</header>

<main class="site-main">
