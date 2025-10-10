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
<style>
    .site-header .wrap {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 30px 20px; /* увеличенный padding для высоты шапки */
}

.logo-row {
  display: flex;
  align-items: center;
  gap: 25px;
}

.info-image img {
  width: 100px; /* увеличенный размер лого */
  height: auto;
  border-radius: 10px;
}

.logo-text {
  font-size: 18px; /* крупнее текст */
  font-weight: 700;
  color: #168C74;
  line-height: 1.1;
}

.main-nav {
  display: flex;
  gap: 40px;
  margin-left: auto;
}

.main-nav a {
  font-size: 18px; /* увеличенный размер текста меню */
  padding: 10px 22px; /* увеличенная кнопка */
  border-radius: 24px;
  border: 1.5px solid #333;
  color: #222;
 
  text-decoration: none;
  transition: background-color 0.3s, color 0.3s;
}
.main-nav a.registration-link {
  background-color: #000; /* черный фон */
  color: #fff;            /* белый текст */
  border-color: #000;     /* чтобы рамка была тоже черной и не выделялась */
}

/* По желанию при наведении можно изменить стиль */
.main-nav a.registration-link:hover {
  background-color: #222;
  color: #fff;
  border-color: #222;
}
.main-nav {
  display: flex;
  gap: 30px;
  margin-left: auto; /* сдвигает меню вправо */
}


.main-nav a:hover {
  background-color: #ffffff;
  color: #b800bb;
}

    /* === Шапка сайта === */
.site-header {
    background: #fff;
    border-bottom: 1px solid #e0e0e0;
    box-shadow: 0 2px 5px rgba(0,0,0,0.03);
    position: sticky;
    top: 0;
    z-index: 10;
}

.site-header .wrap {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 15px 20px;
}

.brand {
  display: flex;
  align-items: center;
  font-weight: 700;
  font-size: 1.2rem;
  color: #168C74;
  gap: 10px; /* пространство между картинкой и текстом */
  text-decoration: none;
}


.main-nav a {
    margin: 0 12px;
    color: #333;
    font-weight: 500;
}
.main-nav a:hover {
    color: #005bbb;
}

/* === Блок hero === */
.hero {
    background: #eaf2fd;
    padding: 40px 25px;
    margin-bottom: 25px;
    border-radius: 8px;
}
.hero h1 {
    margin: 0 0 10px;
    color: #000000;
}

 .brand-logo {
  width: 200px;   /* нужный размер логотипа */
  height: auto;
  display: block;
  margin-top:10%;

}
</style>
<header class="site-header">
  <div class="wrap">
     <a href="<?= BASE_URL ?>/index.php">
    <img src="./assets/img/G1.png" alt="Логотип Центра" class="brand-logo" />
</a>

    <?php if (!empty($_SESSION['user_id'])): ?>
        <!-- Навигация для авторизованных пользователей -->
        <nav class="main-nav">
            
            <a href="<?= BASE_URL ?>/pages/calendar.php">Календарь</a>
            <a href="<?= BASE_URL ?>/pages/news.php">Новости</a>
<<<<<<< HEAD
            <a href="<?= BASE_URL ?>/pages/materials.php">Образование</a>
=======
            <a href="<?= BASE_URL ?>/pages/education.php">Образование</a>
        </nav>
        <div class="auth-links">
>>>>>>> 9b56cbb042f9f92d15cb7638df0931e50dc2f5a8
            <a href="<?= BASE_URL ?>/pages/logout.php" class="btn-logout">Выйти</a>
        </div>

    <?php else: ?>
        <!-- Навигация для гостей -->
        <nav class="main-nav">
            
            <a href="<?= BASE_URL ?>/pages/calendar.php">Календарь</a>
            <a href="<?= BASE_URL ?>/pages/news.php">Новости</a>
<<<<<<< HEAD
            <a href="<?= BASE_URL ?>/pages/materials.php">Образование</a>
=======
            <a href="<?= BASE_URL ?>/pages/education.php">Образование</a>
        </nav>
        <div class="auth-links">
>>>>>>> 9b56cbb042f9f92d15cb7638df0931e50dc2f5a8
            <a href="<?= BASE_URL ?>/pages/login.php" class="btn-login">Войти</a>
            <a href="<?= BASE_URL ?>/pages/register.php" class="btn-register">Регистрация</a>
        </div>
    <?php endif; ?>
  </div>
</header>

<main class="site-main">
