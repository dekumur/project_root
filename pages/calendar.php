<?php
require_once __DIR__ . '/../includes/config.php';
require_once BASE_PATH . '/includes/db_connect.php';

$page_title = 'Календарь мероприятий';
require_once BASE_PATH . '/includes/header.php';

function e($s) { 
    return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); 
}

// Получаем данные из БД
$sql = "SELECT id, title, description, event_date, event_time, location, organizer, link,
               municipality, format, audience, speaker_role, speaker_name, contact_person, reach, publication_link, comments
        FROM events
        ORDER BY event_date ASC";

$result = mysqli_query($connect, $sql);

if (!$result) {
    die("Ошибка запроса: " . mysqli_error($connect));
}

$total = mysqli_num_rows($result);
?>

<section class="wrap">
    <h1>Календарь мероприятий</h1>
    <p>Здесь вы можете узнать о предстоящих мероприятиях по финансовой грамотности.</p>

    <?php if ($total > 0): ?>
        <div class="calendar-table-wrapper">
            <table class="calendar-table">
                <thead>
                    <tr>
                        <th>Муниципальное образование</th>
                        <th>Формат проведения</th>
                        <th>Дата и время</th>
                        <th>Место проведения</th>
                        <th>Целевая аудитория</th>
                        <th>Тема</th>
                        <th>Роль спикера</th>
                        <th>ФИО спикера</th>
                        <th>Контакты</th>
                        <th>Охват</th>
                        <th>Публикация</th>
                        <th>Комментарии</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($e = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= e($e['municipality']) ?></td>
                            <td><?= e($e['format']) ?></td>
                            <td>
                                <?= e($e['event_date']) ?> <?= e($e['event_time']) ?>
                                <?php if (!empty($e['link'])): ?>
                                    <br><a href="<?= e($e['link']) ?>" target="_blank">Ссылка</a>
                                <?php endif; ?>
                            </td>
                            <td><?= e($e['location']) ?></td>
                            <td><?= e($e['audience']) ?></td>
                            <td><?= e($e['title']) ?></td>
                            <td><?= e($e['speaker_role']) ?></td>
                            <td><?= e($e['speaker_name']) ?></td>
                            <td><?= e($e['contact_person']) ?></td>
                            <td><?= e($e['reach']) ?></td>
                            <td>
                                <?php if (!empty($e['publication_link'])): ?>
                                    <a href="<?= e($e['publication_link']) ?>" target="_blank">Ссылка</a>
                                <?php endif; ?>
                            </td>
                            <td><?= e($e['comments']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p>Пока нет запланированных мероприятий.</p>
    <?php endif; ?>
</section>

<?php include BASE_PATH . '/includes/footer.php'; ?>
