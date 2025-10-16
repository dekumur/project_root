<?php
require_once __DIR__ . '/../includes/config.php';
require_once BASE_PATH . '/includes/db_connect.php';
require_once BASE_PATH . '/includes/admin_header.php';

$page_title = 'Добавить новость';

if ($_SESSION['user_role'] !== 'admin') {
    header('Location: ' . BASE_URL . '/index.php');
    exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $is_published = isset($_POST['is_published']) ? 1 : 0;
    $author_id = $_SESSION['user_id'] ?? null;
    $imagePath = null;

    if ($title === '' || $content === '') {
        $message = '<p class="error">Заполните все обязательные поля.</p>';
    } else {
        if (!empty($_FILES['image']['name'])) {
            $uploadDir = BASE_PATH . '/uploads/news/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $fileName = time() . '_' . basename($_FILES['image']['name']);
            $targetPath = $uploadDir . $fileName;
            $imageType = mime_content_type($_FILES['image']['tmp_name']);

            if (in_array($imageType, ['image/jpeg', 'image/png', 'image/webp'])) {
                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                    $imagePath = 'uploads/news/' . $fileName;
                } else {
                    $message = '<p class="error">Ошибка загрузки изображения.</p>';
                }
            } else {
                $message = '<p class="error">Допустимые форматы: JPG, PNG, WEBP.</p>';
            }
        }

        if ($message === '') {
            $stmt = mysqli_prepare($connect, "
                INSERT INTO news (title, content, image, author_id, created_at, is_published)
                VALUES (?, ?, ?, ?, NOW(), ?)
            ");
            mysqli_stmt_bind_param($stmt, 'sssii', $title, $content, $imagePath, $author_id, $is_published);

            if (mysqli_stmt_execute($stmt)) {
                $message = '<p class="success">✅ Новость успешно добавлена!</p>';
            } else {
                $message = '<p class="error">Ошибка при добавлении: ' . mysqli_error($connect) . '</p>';
            }

            mysqli_stmt_close($stmt);
        }
    }
}
?>

<section class="admin-section">
  <h1>Добавить новость</h1>
  <?= $message ?>

  <form action="" method="POST" enctype="multipart/form-data" class="admin-form">
    <div class="form-group">
      <label for="title">Заголовок *</label>
      <input type="text" id="title" name="title" required>
    </div>

    <div class="form-group">
      <label for="content">Содержимое *</label>
      <textarea id="content" name="content" rows="8" required></textarea>
    </div>

    <div class="form-group">
      <label for="image">Изображение</label>
      <input type="file" id="image" name="image" accept="image/*">
    </div>

    <div class="form-group checkbox">
      <label>
        <input type="checkbox" name="is_published" checked> Опубликовать сразу
      </label>
    </div>

    <button type="submit" class="btn-primary">Добавить новость</button>
  </form>
</section>

<?php include BASE_PATH . '/includes/footer.php'; ?>
