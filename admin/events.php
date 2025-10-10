<?php
// admin/events.php
require_once __DIR__ . '/../includes/config.php';
require_once BASE_PATH . '/includes/db_connect.php';
require_once __DIR__ . '/admin_auth.php';

$page_title = 'Админ — Мероприятия';
require_once BASE_PATH . '/includes/admin_header.php';

function e($s){ return htmlspecialchars($s, ENT_QUOTES|ENT_SUBSTITUTE, 'UTF-8'); }

$action = $_GET['action'] ?? '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$errors = [];

// Handle POST actions: add / edit / delete
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $act = $_POST['act'] ?? '';
    if ($act === 'delete' && !empty($_POST['id'])) {
        $delId = (int)$_POST['id'];
        $stmt = mysqli_prepare($connect, "DELETE FROM events WHERE id = ?");
        mysqli_stmt_bind_param($stmt, 'i', $delId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        header('Location: ' . BASE_URL . '/admin/events.php');
        exit;
    }

    if ($act === 'save') {
        // собираем поля
        $id = !empty($_POST['id']) ? (int)$_POST['id'] : 0;
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $event_date = trim($_POST['event_date'] ?? '');
        $event_time = trim($_POST['event_time'] ?? '');
        $location = trim($_POST['location'] ?? '');
        $municipality = trim($_POST['municipality'] ?? '');
        $format = trim($_POST['format'] ?? '');
        $audience = trim($_POST['audience'] ?? '');
        $speaker_role = trim($_POST['speaker_role'] ?? '');
        $speaker_name = trim($_POST['speaker_name'] ?? '');
        $contact_person = trim($_POST['contact_person'] ?? '');
        $reach = (int)($_POST['reach'] ?? 0);
        $link = trim($_POST['link'] ?? '');
        $publication_link = trim($_POST['publication_link'] ?? '');
        $comments = trim($_POST['comments'] ?? '');

        if ($title === '') {
            $errors[] = 'Укажите тему/название мероприятия.';
        }
        if ($event_date === '') {
            $errors[] = 'Укажите дату мероприятия.';
        }

        if (empty($errors)) {
            if ($id > 0) {
                // update
                $sql = "UPDATE events SET title=?, description=?, event_date=?, event_time=?, location=?, municipality=?, format=?, audience=?, speaker_role=?, speaker_name=?, contact_person=?, reach=?, link=?, publication_link=?, comments=? WHERE id=?";
                $stmt = mysqli_prepare($connect, $sql);
                mysqli_stmt_bind_param($stmt, 'sssssssssssssssi',
                    $title, $description, $event_date, $event_time, $location, $municipality, $format, $audience, $speaker_role, $speaker_name, $contact_person, $reach, $link, $publication_link, $comments, $id);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                header('Location: ' . BASE_URL . '/admin/events.php');
                exit;
            } else {
                // insert
                $sql = "INSERT INTO events (title, description, event_date, event_time, location, municipality, format, audience, speaker_role, speaker_name, contact_person, reach, link, publication_link, comments, created_at)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
                $stmt = mysqli_prepare($connect, $sql);
                mysqli_stmt_bind_param($stmt, 'sssssssssssssss', $title, $description, $event_date, $event_time, $location, $municipality, $format, $audience, $speaker_role, $speaker_name, $contact_person, $reach, $link, $publication_link, $comments);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                header('Location: ' . BASE_URL . '/admin/events.php');
                exit;
            }
        }
    }
}

// Если action = edit или add, подгрузим запись (для edit)
$event = null;
if ($action === 'edit' && $id > 0) {
    $stmt = mysqli_prepare($connect, "SELECT * FROM events WHERE id = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $event = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);
}

// Получаем список событий
$events = [];
$res = mysqli_query($connect, "SELECT id, title, event_date, event_time, municipality FROM events ORDER BY event_date DESC");
if ($res) {
    while ($row = mysqli_fetch_assoc($res)) {
        $events[] = $row;
    }
}
?>

<link rel="stylesheet" href="../assets/css/admin.css">
<section class="wrap">
    <h1>Управление мероприятиями</h1>

    <p><a href="<?= BASE_URL ?>/admin/events.php?action=add" class="btn">+ Добавить мероприятие</a></p>

    <?php if (!empty($errors)): ?>
        <div class="form-errors">
            <ul>
                <?php foreach ($errors as $er): ?><li><?= e($er) ?></li><?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if ($action === 'add' || $action === 'edit'): ?>
        <?php
            $v = $event ?? [];
            // helper
            $get = function($k, $default='') use ($v){ return isset($v[$k]) ? e($v[$k]) : $default; };
        ?>
        <form method="post" action="<?= BASE_URL ?>/admin/events.php">
            <input type="hidden" name="act" value="save">
            <input type="hidden" name="id" value="<?= $get('id', 0) ?>">

            <label>Тема / название
                <input type="text" name="title" value="<?= $get('title') ?>" required>
            </label>

            <label>Краткое описание
                <textarea name="description"><?= $get('description') ?></textarea>
            </label>

            <label>Дата
                <input type="date" name="event_date" value="<?= $get('event_date') ?>" required>
            </label>

            <label>Время
                <input type="time" name="event_time" value="<?= $get('event_time') ?>">
            </label>

            <label>Муниципалитет
                <input type="text" name="municipality" value="<?= $get('municipality') ?>">
            </label>

            <label>Место (адрес)
                <input type="text" name="location" value="<?= $get('location') ?>">
            </label>

            <label>Формат
                <input type="text" name="format" value="<?= $get('format') ?>">
            </label>

            <label>Целевая аудитория
                <input type="text" name="audience" value="<?= $get('audience') ?>">
            </label>

            <label>Роль спикера
                <input type="text" name="speaker_role" value="<?= $get('speaker_role') ?>">
            </label>

            <label>ФИО спикера
                <input type="text" name="speaker_name" value="<?= $get('speaker_name') ?>">
            </label>

            <label>Контакт (ФИО/телефон/почта)
                <input type="text" name="contact_person" value="<?= $get('contact_person') ?>">
            </label>

            <label>Охват (кол-во)
                <input type="number" name="reach" value="<?= $get('reach', 0) ?>">
            </label>

            <label>Ссылка (онлайн)
                <input type="url" name="link" value="<?= $get('link') ?>">
            </label>

            <label>Ссылка на публикацию
                <input type="url" name="publication_link" value="<?= $get('publication_link') ?>">
            </label>

            <label>Комментарии
                <textarea name="comments"><?= $get('comments') ?></textarea>
            </label>

            <div style="margin-top:10px;">
                <button type="submit" class="btn">Сохранить</button>
                <a href="<?= BASE_URL ?>/admin/events.php" class="btn">Отмена</a>
            </div>
        </form>

    <?php else: ?>
        <table class="calendar-table" style="margin-top:10px;">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Дата</th>
                    <th>Тема</th>
                    <th>Муниципалитет</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($events) === 0): ?>
                    <tr><td colspan="5">Нет мероприятий</td></tr>
                <?php endif; ?>
                <?php foreach ($events as $it): ?>
                    <tr>
                        <td><?= e($it['id']) ?></td>
                        <td><?= e($it['event_date']) ?> <?= e($it['event_time']) ?></td>
                        <td><?= e($it['title']) ?></td>
                        <td><?= e($it['municipality']) ?></td>
                        <td>
                            <a href="<?= BASE_URL ?>/admin/events.php?action=edit&id=<?= e($it['id']) ?>">Ред.</a> |
                            <form method="post" action="<?= BASE_URL ?>/admin/events.php" style="display:inline" onsubmit="return confirm('Удалить мероприятие?');">
                                <input type="hidden" name="act" value="delete">
                                <input type="hidden" name="id" value="<?= e($it['id']) ?>">
                                <button type="submit" class="link-btn" style="background:none;border:none;color:#c00;cursor:pointer;padding:0;">Удал.</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</section>

<?php include BASE_PATH . '/includes/footer.php'; ?>
