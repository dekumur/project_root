<?php
session_start();
require_once __DIR__ . '/../includes/config.php';
require_once BASE_PATH . '/includes/db_connect.php';

header('Content-Type: application/json');

$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
    echo json_encode(['success' => false, 'message' => 'Введите корректный email и пароль.']);
    exit;
}

// Получаем пользователя по email
$sql = "SELECT id, password, name FROM users WHERE email = ? LIMIT 1";
$stmt = mysqli_prepare($connect, $sql);
mysqli_stmt_bind_param($stmt, 's', $email);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $id, $hash, $name);

if (mysqli_stmt_fetch($stmt)) {
    mysqli_stmt_close($stmt);

    if (password_verify($password, $hash)) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $id;
        $_SESSION['user_email'] = $email;
        $_SESSION['user_name'] = $name;

        // Редирект для конкретного admin email
        $redirect = ($email === '1c_is_my_waify@gmail.com') ? BASE_URL . '/admin/index.php' : BASE_URL . '/index.php';

        echo json_encode(['success' => true, 'message' => 'Вход выполнен успешно!', 'redirect_url' => $redirect]);
        exit;
    } else {
        echo json_encode(['success' => false, 'message' => 'Неверный email или пароль.']);
        exit;
    }
} else {
    mysqli_stmt_close($stmt);
    echo json_encode(['success' => false, 'message' => 'Неверный email или пароль.']);
    exit;
}
?>
