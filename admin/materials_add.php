<?php
session_start();
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db_connect.php';

// Проверка авторизации
if (empty($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ' . BASE_URL . '/pages/login.php');
    exit;
}

$page_title = "Добавление образовательного материала";
$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $type = $_POST['type'] ?? '';
    $audience = $_POST['audience'] ?? '';

    // Валидация полей
    if ($title === '') $errors[] = "Введите название материала.";
    if ($description === '') $errors[] = "Введите описание материала.";
    if (!in_array($type, ['article', 'video'])) $errors[] = "Выберите корректный тип материала.";
    if (!in_array($audience, ['kids', 'teens', 'adults', 'seniors'])) $errors[] = "Выберите целевую аудиторию.";

    if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
        $errors[] = "Выберите файл для загрузки.";
    }

    if (empty($errors)) {
        // Папка для загрузки
        $uploadDir = __DIR__ . '/../assets/files/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $originalName = basename($_FILES['file']['name']);
        $ext = pathinfo($originalName, PATHINFO_EXTENSION);
        $filename = time() . '_' . preg_replace("/[^a-zA-Z0-9_-]/", "_", pathinfo($originalName, PATHINFO_FILENAME)) . '.' . $ext;
        $targetPath = $uploadDir . $filename;

        // Разрешённые MIME-типы
        $allowedTypes = [
            'application/pdf',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'video/mp4',
            'video/webm',
            'video/ogg'
        ];

        if (!in_array($_FILES['file']['type'], $allowedTypes)) {
            $errors[] = "Недопустимый тип файла. Разрешены PDF, презентации, DOCX и видео (MP4, WEBM, OGG).";
        }

        if (empty($errors)) {
            if (move_uploaded_file($_FILES['file']['tmp_name'], $targetPath)) {
                $filePathForDB = 'assets/files/' . $filename;
                $author_id = (int)$_SESSION['user_id'];
                $created_at = date('Y-m-d H:i:s');

                // Защита от SQL-инъекций
                $title_safe = mysqli_real_escape_string($connect, $title);
                $desc_safe = mysqli_real_escape_string($connect, $description);
                $file_safe = mysqli_real_escape_string($connect, $filePathForDB);
                $type_safe = mysqli_real_escape_string($connect, $type);
                $audience_safe = mysqli_real_escape_string($connect, $audience);

                $sql = "
                    INSERT INTO materials (title, description, file_path, type, audience, author_id, created_at)
                    VALUES ('$title_safe', '$desc_safe', '$file_safe', '$type_safe', '$audience_safe', '$author_id', '$created_at')
                ";

                if (mysqli_query($connect, $sql)) {
                    $success = "Материал успешно добавлен!";
                    $_POST = []; // очистим форму
                } else {
                    $errors[] = "Ошибка при добавлении в базу: " . mysqli_error($connect);
                }
            } else {
                $errors[] = "Ошибка при загрузке файла на сервер.";
            }
        }
    }
}
?>

<?php include __DIR__ . '/../includes/admin_header.php'; ?>

<section class="admin-section">
    <h1>Добавление образовательного материала</h1>

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

    <form action="" method="post" enctype="multipart/form-data" class="admin-form">
        <div class="form-group">
            <label for="title">Название материала</label>
            <input type="text" name="title" id="title" value="<?= htmlspecialchars($_POST['title'] ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label for="description">Описание</label>
            <textarea name="description" id="description" rows="4" required><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label for="type">Тип материала</label>
            <select name="type" id="type" required>
                <option value="">-- Выберите тип --</option>
                <option value="article" <?= (($_POST['type'] ?? '') === 'article') ? 'selected' : '' ?>>Статья / документ</option>
                <option value="video" <?= (($_POST['type'] ?? '') === 'video') ? 'selected' : '' ?>>Видео</option>
            </select>
        </div>

        <div class="form-group">
            <label for="audience">Целевая аудитория</label>
            <select name="audience" id="audience" required>
                <option value="">-- Выберите аудиторию --</option>
                <option value="kids" <?= (($_POST['audience'] ?? '') === 'kids') ? 'selected' : '' ?>>Дети (6–12 лет)</option>
                <option value="teens" <?= (($_POST['audience'] ?? '') === 'teens') ? 'selected' : '' ?>>Подростки</option>
                <option value="adults" <?= (($_POST['audience'] ?? '') === 'adults') ? 'selected' : '' ?>>Взрослые</option>
                <option value="seniors" <?= (($_POST['audience'] ?? '') === 'seniors') ? 'selected' : '' ?>>Пенсионеры</option>
            </select>
        </div>

        <div class="form-group">
            <label for="file">Файл (PDF, PPT, DOCX, MP4, WEBM, OGG)</label>
            <input type="file" name="file" id="file" accept=".pdf,.ppt,.pptx,.docx,.mp4,.webm,.ogg" required>
        </div>

        <button type="submit" class="btn-primary">Добавить материал</button>
    </form>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
