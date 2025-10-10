<?php
require_once __DIR__ . '/../includes/config.php';
require_once BASE_PATH . '/includes/db_connect.php';
session_start();

// Проверка на админа
if ($_SESSION['user_email'] !== '1c_is_my_waify@gmail.com') {
    header('Location: ' . BASE_URL . '/index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['id'])) {
    $id = intval($_POST['id']);

    // Нельзя удалить самого админа
    $stmt = mysqli_prepare($connect, "DELETE FROM users WHERE id = ? AND email != '1c_is_my_waify@gmail.com'");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

header('Location: ' . BASE_URL . '/admin/users.php');
exit;
