<?php
// admin/admin_auth.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../includes/config.php';

// Если не авторизован — редиректим
if (empty($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . '/pages/login.php');
    exit;
}

// Разрешаем доступ только админам или заданным e-mail (модераторы/админы)
$allowedEmails = [
    '1c_is_my_waify@gmail.com',
    // добавьте сюда прочие e-mail админов, если нужно
];

$isAdmin = false;

if (!empty($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
    $isAdmin = true;
} elseif (!empty($_SESSION['user_email']) && in_array($_SESSION['user_email'], $allowedEmails, true)) {
    $isAdmin = true;
}

if (!$isAdmin) {
    // можно показать 403 или редирект на главную
    header('HTTP/1.1 403 Forbidden');
    echo 'Доступ запрещён.';
    exit;
}
