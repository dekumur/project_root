<?php
require_once __DIR__ . '/../includes/config.php';
require_once BASE_PATH . '/includes/db_connect.php';


$page_title = 'Авторизация';
require_once BASE_PATH . '/includes/header.php';

function e($s){ return htmlspecialchars($s, ENT_QUOTES|ENT_SUBSTITUTE, 'UTF-8'); }

// Генерация CSRF-токена
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Проверка CSRF
    if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        $errors[] = 'Ошибка безопасности. Попробуйте ещё раз.';
    } else {
        $email = ($_POST['email'] ?? '');
        $password = ($_POST['password'] ?? '');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
            $errors[] = 'Введите корректный email и пароль.';
        } else {
            // Получаем id, пароль, имя и роль (пароль в открытом виде)
            $sql = "SELECT id, password, name, role FROM users WHERE email = ? LIMIT 1";
            $stmt = mysqli_prepare($connect, $sql);
            mysqli_stmt_bind_param($stmt, 's', $email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $id, $db_password, $name, $role);
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_num_rows($stmt) > 0) {
                mysqli_stmt_fetch($stmt);
                mysqli_stmt_close($stmt);

                // Проверяем пароль (простое сравнение)
                if ($password === $db_password) {
                    // Авторизация успешна
                    session_regenerate_id(true);
                    $_SESSION['user_id'] = $id;
                    $_SESSION['user_email'] = $email;
                    $_SESSION['user_name'] = $name;
                    $_SESSION['user_role'] = $role; // Сохраняем роль в сессии
                    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

                    // Редирект в зависимости от роли
                    switch ($role) {
                        case 'admin':
                            header('Location: ' . BASE_URL . '/admin/index.php');
                            break;
                        case 'curator':
                            header('Location: ' . BASE_URL . '/curator/index.php');
                            break;
                        case 'user':
                        default:
                            header('Location: ' . BASE_URL . '/index.php');
                            break;
                    }
                    exit;

                } else {
                    $errors[] = 'Неверный email или пароль.';
                }

            } else {
                $errors[] = 'Неверный email или пароль.';
            }
        }
    }
}
?>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/forms.css">

<div class="auth-wrap">
    <div class="auth-card">
        <h2>Вход</h2>

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
                Email
                <input type="email" name="email" required value="<?= e($_POST['email'] ?? '') ?>" autocomplete="email">
            </label>

            <label>
                Пароль
                <input type="password" name="password" required autocomplete="current-password">
            </label>

            <div class="form-actions">
                <button type="submit" class="btn">Войти</button>
                <a href="<?= BASE_URL ?>/pages/register.php" class="link">Нет аккаунта? Зарегистрироваться</a>
            </div>
        </form>
    </div>
</div>

<?php include BASE_PATH . '/includes/footer.php'; ?>