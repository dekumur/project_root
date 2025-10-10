<?php
// admin/index.php
require_once __DIR__ . '/../includes/config.php';
require_once BASE_PATH . '/includes/db_connect.php';
require_once __DIR__ . '/admin_auth.php';

$page_title = 'Админ — Dashboard';
require_once BASE_PATH . '/includes/admin_header.php';


// Простая статистика
$totalEvents = 0;
$totalUsers = 0;

$res = mysqli_query($connect, "SELECT COUNT(*) as c FROM events");
if ($res) {
    $row = mysqli_fetch_assoc($res);
    $totalEvents = (int)$row['c'];
}

$res = mysqli_query($connect, "SELECT COUNT(*) as c FROM users");
if ($res) {
    $row = mysqli_fetch_assoc($res);
    $totalUsers = (int)$row['c'];
}
?>

<section class="wrap">
    <h1>Админ-панель</h1>
    <p>Добро пожаловать, <?= htmlspecialchars($_SESSION['user_name'] ?? 'Администратор') ?></p>

    <div class="grid-two" style="gap:20px; margin-top:20px;">
        <div class="card">
            <div class="card-body">
                <h3>Статистика</h3>
                <p>Мероприятий: <strong><?= $totalEvents ?></strong></p>
                <p>Пользователей: <strong><?= $totalUsers ?></strong></p>
                <p><a href="<?= BASE_URL ?>/admin/events.php">Управление мероприятиями →</a></p>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h3>Быстрые действия</h3>
                <ul>
                    <li><a href="<?= BASE_URL ?>/admin/events.php?action=add">Добавить мероприятие</a></li>
                    <li><a href="<?= BASE_URL ?>/admin/add_news.php?action=add">Добавить новость</a></li>
                    <li><a href="<?= BASE_URL ?>/pages/logout.php">Выйти</a></li>
                </ul>
            </div>
        </div>
    </div>
</section>

<?php include BASE_PATH . '/includes/footer.php'; ?>
