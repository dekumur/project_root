<?php
session_start();
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db_connect.php';

// Проверка авторизации куратора
if (empty($_SESSION['user_id']) || $_SESSION['user_role'] !== 'curator') {
    header('Location: ' . BASE_URL . '/pages/login.php');
    exit;
}

$page_title = "Редактирование заявки";
$errors = [];
$success = '';

$request_id = (int)($_GET['id'] ?? 0);

if ($request_id <= 0) {
    die("Некорректный ID заявки.");
}

// Проверяем, что заявка принадлежит текущему куратору
$user_id = (int)$_SESSION['user_id'];
$sql = "SELECT * FROM event_request WHERE id = $request_id AND user_id = $user_id";
$result = mysqli_query($connect, $sql);
$request = mysqli_fetch_assoc($result);

if (!$request) {
    die("Заявка не найдена.");
}

// Обработка сохранения изменений
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if ($title === '') {
        $errors[] = "Введите название мероприятия.";
    }
    if ($description === '') {
        $errors[] = "Введите описание мероприятия.";
    }

    if (empty($errors)) {
        $title_safe = mysqli_real_escape_string($connect, $title);
        $desc_safe = mysqli_real_escape_string($connect, $description);

        $sql_update = "UPDATE event_request 
                       SET title='$title_safe', description='$desc_safe' 
                       WHERE id=$request_id AND user_id=$user_id";

        if (mysqli_query($connect, $sql_update)) {
            $success = "Заявка успешно обновлена!";
            $request['title'] = $title;
            $request['description'] = $description;
        } else {
            $errors[] = "Ошибка при обновлении заявки: " . mysqli_error($connect);
        }
    }
}
?>

<?php include __DIR__ . '/../includes/curator_header.php'; ?>
<link rel="stylesheet" href="../assets/css/curator.css">
<section class="admin-section">
    <h1>Редактирование заявки</h1>

    <?php if ($errors): ?>
        <div class="form-errors">
            <ul>
                <?php foreach ($errors as $err): ?>
                    <li><?= htmlspecialchars($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form action="" method="post" class="admin-form">
        <div class="form-group">
            <label for="title">Название мероприятия</label>
            <input type="text" name="title" id="title" value="<?= htmlspecialchars($request['title']) ?>" required>
        </div>

        <div class="form-group">
            <label for="description">Описание мероприятия</label>
            <textarea name="description" id="description" rows="5" required><?= htmlspecialchars($request['description']) ?></textarea>
        </div>

        <button type="submit" class="btn-primary">Сохранить изменения</button>
        <a href="curator_requests.php" class="btn-reset" style="margin-left:10px;">Отмена</a>
    </form>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
