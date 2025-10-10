<?php
include 'includes/db_connect.php';
include 'includes/header.php';

function e($s) { return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function excerpt($text, $len = 220) {
    $text = strip_tags($text);
    if (mb_strlen($text) <= $len) return $text;
    return mb_substr($text, 0, $len) . '…';
}

// Последние новости
$news_sql = "SELECT n.id, n.title, n.content, n.image, n.created_at, u.name AS author
             FROM news n
             LEFT JOIN users u ON n.author_id = u.id
             WHERE n.is_published = 1
             ORDER BY n.created_at DESC
             LIMIT 3";
$news_result = mysqli_query($connect, $news_sql);

// Ближайшие мероприятия
$events_sql = "SELECT id, title, description, event_date, event_time, location
               FROM events
               WHERE event_date >= CURDATE()
               ORDER BY event_date ASC
               LIMIT 3";
$events_result = mysqli_query($connect, $events_sql);
?>
<section class="hero wrap">
  <h1>Центр финансового просвещения Удмуртской Республики</h1>
  <p class="lead">Актуальная информация, обучение и материалы для повышения финансовой грамотности жителей Удмуртии.</p>
</section>

<section class="wrap grid-two">
  <div class="col">
    <h2>Последние новости</h2>
    <?php if (mysqli_num_rows($news_result) > 0): ?>
      <div class="cards">
        <?php while ($n = mysqli_fetch_assoc($news_result)): ?>
          <article class="card news-card">
            <?php if (!empty($n['image'])): ?>
              <div class="thumb"><img src="<?= e($n['image']) ?>" alt="<?= e($n['title']) ?>"></div>
            <?php endif; ?>
            <div class="card-body">
              <h3><a href="/pages/news_detail.php?id=<?= (int)$n['id'] ?>"><?= e($n['title']) ?></a></h3>
              <p class="meta">Опубликовано <?= date('d.m.Y', strtotime($n['created_at'])) ?> <?= $n['author'] ? '• ' . e($n['author']) : '' ?></p>
              <p><?= e(excerpt($n['content'], 240)) ?></p>
              <a href="/pages/news_detail.php?id=<?= (int)$n['id'] ?>" class="more">Читать далее →</a>
            </div>
          </article>
        <?php endwhile; ?>
      </div>
    <?php else: ?>
      <p>Пока нет новостей.</p>
    <?php endif; ?>
  </div>

  <aside class="col">
    <h2>Ближайшие мероприятия</h2>
    <?php if (mysqli_num_rows($events_result) > 0): ?>
      <ul class="events-list">
        <?php while ($e = mysqli_fetch_assoc($events_result)): ?>
          <li>
            <strong><?= e($e['title']) ?></strong><br>
            <span class="meta"><?= date('d.m.Y', strtotime($e['event_date'])) ?> <?= $e['event_time'] ? '• ' . substr($e['event_time'], 0, 5) : '' ?></span><br>
            <span class="muted"><?= e($e['location']) ?></span>
            <p><?= e(excerpt($e['description'], 140)) ?></p>
          </li>
        <?php endwhile; ?>
      </ul>
    <?php else: ?>
      <p>Нет запланированных мероприятий.</p>
    <?php endif; ?>
  </aside>
</section>

<?php include 'includes/footer.php'; ?>
