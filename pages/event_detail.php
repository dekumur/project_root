<?php
require_once __DIR__ . '/../includes/db_connect.php';
include __DIR__ . '/../includes/header.php';
function e($s) { return htmlspecialchars($s, ENT_QUOTES|ENT_SUBSTITUTE, 'UTF-8'); }

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<p>Неверный ID события.</p>";
    include __DIR__ . '/../includes/footer.php';
    exit;
}

$id = (int)$_GET['id'];

$sql = "SELECT * FROM events WHERE id = $id LIMIT 1";
$result = mysqli_query($connect, $sql);

if(mysqli_num_rows($result) == 0){
    echo "<p>Событие не найдено.</p>";
    include __DIR__ . '/../includes/footer.php';
    exit;
}

$event = mysqli_fetch_assoc($result);
?>

<section class="wrap">
    <h1><?= e($event['title']) ?></h1>
    <p><strong>Дата:</strong> <?= date('d.m.Y', strtotime($event['event_date'])) ?>
       <?= $event['event_time'] ? '• ' . substr($event['event_time'],0,5) : '' ?></p>
    <p><strong>Место:</strong> <?= e($event['location']) ?></p>
    <p><strong>Организатор:</strong> <?= e($event['organizer']) ?></p>
    <?php if($event['link']): ?>
        <p><a href="<?= e($event['link']) ?>" target="_blank">Ссылка на регистрацию / трансляцию</a></p>
    <?php endif; ?>
    <hr>
    <p><?= nl2br(e($event['description'])) ?></p>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
