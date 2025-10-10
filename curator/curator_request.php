<?php
session_start();
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db_connect.php';

// Проверка авторизации куратора
if (empty($_SESSION['user_id']) || $_SESSION['user_role'] !== 'curator') {
    header('Location: ' . BASE_URL . '/pages/login.php');
    exit;
}

$page_title = "Мои заявки на мероприятия";
$errors = [];
$success = '';

// Обработка отправки новой заявки
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
        $user_id = (int)$_SESSION['user_id'];
        $created_at = date('Y-m-d H:i:s');

        $sql = "INSERT INTO event_request (user_id, title, description, created_at) 
                VALUES ('$user_id', '$title_safe', '$desc_safe', '$created_at')";

        if (mysqli_query($connect, $sql)) {
            $success = "Заявка успешно отправлена!";
        } else {
            $errors[] = "Ошибка при добавлении заявки: " . mysqli_error($connect);
        }
    }
}

// Получаем все заявки текущего куратора
$user_id = (int)$_SESSION['user_id'];
$sql = "SELECT * FROM event_request WHERE user_id = $user_id ORDER BY created_at DESC";
$result = mysqli_query($connect, $sql);

$requests = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $requests[] = $row;
    }
} else {
    die("Ошибка запроса: " . mysqli_error($connect));
}
?>

<?php include __DIR__ . '/../includes/curator_header.php'; ?>

<section class="admin-section">
    <h1>Мои заявки на мероприятия</h1>

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
        <link rel="stylesheet" href="../assets/css/curator.css">
    <!-- Форма отправки новой заявки -->
    <form action="" method="post" class="admin-form">
        <div class="form-group">
            <label for="title">Название мероприятия</label>
            <input type="text" name="title" id="title" value="<?= htmlspecialchars($_POST['title'] ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label for="description">Описание мероприятия</label>
            <textarea name="description" id="description" rows="5" required><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
        </div>

        <button type="submit" class="btn-primary">Отправить заявку</button>
    </form>

    <!-- Таблица с ранее отправленными заявками -->
    <h2 style="margin-top:30px;">Ранее отправленные заявки</h2>
    <div class="admin-table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Название</th>
                    <th>Описание</th>
                    <th>Дата подачи</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($requests): ?>
                    <?php foreach ($requests as $req): ?>
                        <tr>
                            <td><?= htmlspecialchars($req['id']) ?></td>
                            <td><?= htmlspecialchars($req['title']) ?></td>
                            <td><?= htmlspecialchars($req['description']) ?></td>
                            <td><?= htmlspecialchars($req['created_at']) ?></td>
                            <td>
                                <a href="edit_request.php?id=<?= $req['id'] ?>" class="btn-primary" style="padding:4px 8px;font-size:0.85rem;">Редактировать</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align:center;">Нет заявок</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
