<?php
// Подключаем конфиг и DB
require_once __DIR__ . '/../includes/config.php';
require_once BASE_PATH . '/includes/db_connect.php';


$page_title = 'Регистрация';
require_once BASE_PATH . '/includes/header.php';

function e($s){ 
    return htmlspecialchars($s, ENT_QUOTES|ENT_SUBSTITUTE, 'UTF-8'); 
}

// CSRF токен
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Проверка CSRF
    if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $errors[] = 'Ошибка безопасности. Попробуйте ещё раз.';
    } else {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $password2 = $_POST['password2'] ?? '';

        // Валидация
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Введите корректный email.';
        }
        if (strlen($password) < 8) {
            $errors[] = 'Пароль должен содержать не менее 8 символов.';
        }
        if ($password !== $password2) {
            $errors[] = 'Пароли не совпадают.';
        }

        if (empty($errors)) {
            // Проверяем существование пользователя
            $sql = "SELECT id FROM users WHERE email = ? LIMIT 1";
            $stmt = mysqli_prepare($connect, $sql);
            mysqli_stmt_bind_param($stmt, 's', $email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_num_rows($stmt) > 0) {
                $errors[] = 'Пользователь с таким email уже существует.';
                mysqli_stmt_close($stmt);
            } else {
                mysqli_stmt_close($stmt);
                // Создаем пользователя без хеширования пароля
                $insert = "INSERT INTO users (email, password, name) VALUES (?, ?, ?)";
                $stmt2 = mysqli_prepare($connect, $insert);
                mysqli_stmt_bind_param($stmt2, 'sss', $email, $password, $name);

                if (mysqli_stmt_execute($stmt2)) {
                    $success = true;
                    // Автоматический логин
                    $_SESSION['user_id'] = mysqli_insert_id($connect);
                    $_SESSION['user_email'] = $email;
                    $_SESSION['user_name'] = $name;
                    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

                    mysqli_stmt_close($stmt2);

                    // Редирект на профиль
                    header('Location: ' . BASE_URL . '/index.php');
                    exit;
                } else {
                    $errors[] = 'Ошибка при сохранении в БД: ' . mysqli_error($connect);
                    mysqli_stmt_close($stmt2);
                }
            }
        }
    }
}
?>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/main.css">

<div class="auth-wrap">
    <div class="auth-card">
        <h2>Регистрация</h2>

        <?php if ($errors): ?>
            <div class="form-errors">
                <ul>
                    <?php foreach ($errors as $err): ?>
                        <li><?= e($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post" action="">
            <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">

            <label>
                ФИО
                <input type="text" name="name" value="<?= e($_POST['name'] ?? '') ?>" autocomplete="name">
            </label>

            <label>
                Email
                <input type="email" name="email" required value="<?= e($_POST['email'] ?? '') ?>" autocomplete="email">
            </label>

            <label>
                Пароль (минимум 8 символов)
                <input type="password" name="password" required autocomplete="new-password">
            </label>

            <label>
                Повторите пароль
                <input type="password" name="password2" required autocomplete="new-password">
            </label>

            <div class="form-actions">
                <button type="submit" class="btn">Зарегистрироваться</button>
                <a href="<?= BASE_URL ?>/pages/login.php" class="link">Уже зарегистрированы? Войти</a>
            </div>
        </form>
    </div>
</div>

<?php include BASE_PATH . '/includes/footer.php'; ?>
