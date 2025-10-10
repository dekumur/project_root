<?php
session_start();
require_once __DIR__ . '/../includes/config.php';

// Проверка авторизации
if (empty($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ' . BASE_URL . '/pages/login.php');
    exit;
}

$page_title = "Список образовательных материалов";

// Удаление материала
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];

    // Получаем путь к файлу
    $res = mysqli_query($conn, "SELECT file_path FROM materials WHERE id = $id");
    if ($res && mysqli_num_rows($res)) {
        $row = mysqli_fetch_assoc($res);
        $filePath = __DIR__ . '/../' . $row['file_path'];
        if (file_exists($filePath)) {
            unlink($filePath); // удаляем файл
        }
        mysqli_query($conn, "DELETE FROM materials WHERE id = $id");
    }

    header("Location: materials_list.php");
    exit;
}

// Получаем все материалы
$result = mysqli_query($conn, "SELECT m.*, u.user_name FROM materials m LEFT JOIN users u ON m.author_id = u.id ORDER BY m.created_at DESC");
?>

<?php include __DIR__ . '/admin_header.php'; ?>

<section class="admin-section">
    <h1>Образовательные материалы</h1>
    <a href="materials_add.php" class="btn btn-primary" style="margin-bottom: 15px;">Добавить материал</a>

    <div class="admin-table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Название</th>
                    <th>Файл</th>
                    <th>Автор</th>
                    <th>Дата добавления</th>
                    <th>Действие</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr class="admin-row">
                            <td><?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['title']) ?></td>
                            <td><a href="<?= BASE_URL . '/' . $row['file_path'] ?>" target="_blank">Открыть</a></td>
                            <td><?= htmlspecialchars($row['user_name'] ?? 'Админ') ?></td>
                            <td><?= $row['created_at'] ?></td>
                            <td>
                                <a href="materials_add.php?id=<?= $row['id'] ?>" class="btn btn-primary" style="margin-right:5px;">Редактировать</a>
                                <a href="?delete=<?= $row['id'] ?>" class="btn-delete" onclick="return confirm('Удалить материал?');">Удалить</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="6" style="text-align:center;">Материалов нет</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<?php include __DIR__ . '/admin_footer.php'; ?>
