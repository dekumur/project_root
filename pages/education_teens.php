<?php
include __DIR__ . '/../includes/db_connect.php';
$page_title = 'Для подростков';
include __DIR__ . '/../includes/header.php';

// SQL-запросы к таблице materials
$articles_sql = "SELECT * FROM materials WHERE audience='teens' AND type='article' ORDER BY created_at DESC LIMIT 5";
$videos_sql   = "SELECT * FROM materials WHERE audience='teens' AND type='video' ORDER BY created_at DESC LIMIT 5";

$articles = mysqli_query($connect, $articles_sql);
if (!$articles) {
    die("Ошибка SQL (articles): " . mysqli_error($connect));
}

$videos = mysqli_query($connect, $videos_sql);
if (!$videos) {
    die("Ошибка SQL (videos): " . mysqli_error($connect));
}
?>

<style>
.page-wrap {
  max-width: 1200px;
  margin: 40px auto;
  padding: 0 20px;
}

.page-title {
  text-align: center;
  color: #7c4dff;
  margin-bottom: 30px;
}

.content-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 30px;
}

.block {
  background: #fff;
  border-radius: 12px;
  border: 1px solid #ddd;
  padding: 20px;
  box-shadow: 0 3px 8px rgba(0,0,0,0.05);
}

.block h2 {
  text-align: center;
  color: #444;
  margin-bottom: 20px;
}

.article, .video {
  margin-bottom: 20px;
  text-align: left;
}

.article h3, .video h3 {
  margin: 5px 0;
  color: #333;
}

.article p {
  font-size: 14px;
  color: #555;
  line-height: 1.5;
}

.video video {
  width: 100%;
  border-radius: 10px;
}

.more-link {
  display: inline-block;
  margin-top: 10px;
  text-align: right;
  font-weight: bold;
  color: #7c4dff;
}

.more-link:hover {
  text-decoration: underline;
}

/* Адаптив */
@media (max-width: 900px) {
  .content-grid {
    grid-template-columns: 1fr;
  }
}
</style>

<div class="page-wrap">
  <h1 class="page-title">Подростки</h1>
  <p style="text-align:center;">Материалы по финансовой грамотности, адаптированные для подростков.</p>

  <div class="content-grid">
    <!-- Статьи -->
    <div class="block">
      <h2>Статьи</h2>
      <?php if (mysqli_num_rows($articles) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($articles)): ?>
          <div class="article">
            <h3><?= htmlspecialchars($row['title']) ?></h3>
            <p><?= nl2br(htmlspecialchars(mb_substr(strip_tags($row['description']), 0, 180))) ?>...</p>
            <?php if (!empty($row['file_path'])): ?>
              <a href="<?= htmlspecialchars($row['file_path']) ?>">Скачать материал</a>
            <?php endif; ?>
          </div>
        <?php endwhile; ?>
        <a href="education_articles.php?group=teens" class="more-link">Смотреть все статьи →</a>
      <?php else: ?>
        <p>Пока нет статей для подростков.</p>
      <?php endif; ?>
    </div>

    <!-- Видео -->
    <div class="block">
      <h2>Видео</h2>
      <?php if (mysqli_num_rows($videos) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($videos)): ?>
          <div class="video">
            <?php if (!empty($row['file_path'])): ?>
              <video width="100%" controls>
                <source src="<?= htmlspecialchars($row['file_path']) ?>" type="video/mp4">
                Ваш браузер не поддерживает видео.
              </video>
            <?php endif; ?>
            <h3><?= htmlspecialchars($row['title']) ?></h3>
          </div>
        <?php endwhile; ?>
        <a href="education_videos.php?group=teens" class="more-link">Смотреть все видео →</a>
      <?php else: ?>
        <p>Пока нет видео для подростков.</p>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
