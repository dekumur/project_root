<?php
session_start();
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db_connect.php'; // здесь создается $connect

// Проверка авторизации
if (empty($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ' . BASE_URL . '/pages/login.php');
    exit;
}

$page_title = "Заявки от кураторов";

// Получаем все заявки
$sql = "SELECT er.id, er.title, er.description, er.created_at, u.name AS curator_name
        FROM event_request er
        JOIN users u ON er.user_id = u.id
        ORDER BY er.created_at DESC";

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

<?php include __DIR__ . '/../includes/admin_header.php'; ?>

<section class="admin-section">
    <h1>Заявки от кураторов</h1>

    <div class="admin-table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Куратор</th>
                    <th>Название</th>
                    <th>Описание</th>
                    <th>Дата подачи</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($requests): ?>
                    <?php foreach ($requests as $req): ?>
                        <tr>
                            <td><?= htmlspecialchars($req['id']) ?></td>
                            <td><?= htmlspecialchars($req['curator_name']) ?></td>
                            <td><?= htmlspecialchars($req['title']) ?></td>
                            <td><?= htmlspecialchars($req['description']) ?></td>
                            <td><?= htmlspecialchars($req['created_at']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align:center;">Нет заявок</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
