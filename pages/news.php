<?php
require_once __DIR__ . '/../includes/config.php';
require_once BASE_PATH . '/includes/db_connect.php';


$page_title = 'Новости';
require_once BASE_PATH . '/includes/header.php';

function e($s) {
  return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function excerpt($text, $len = 220) {
  $text = strip_tags($text);
  if (mb_strlen($text) <= $len) return $text;
  return mb_substr($text, 0, $len) . '…';
}

$news_sql = "SELECT n.id, n.title, n.content, n.image, n.created_at, u.name AS author
             FROM news n
             LEFT JOIN users u ON n.author_id = u.id
             WHERE n.is_published = 1
             ORDER BY n.created_at DESC";
$news_result = mysqli_query($connect, $news_sql);
?>


<style>

    @font-face {
  font-family: 'Yo';
  src: url('/fonts/') format('woff2');
  font-style: normal;
}

/* Общие стили */
body {
  font-family: Arial, sans-serif;
  margin: 0;
  padding: 0;
  background-color: #f4f4f4;
}

/* Обертка для контента */
.wrap {
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
}


.hero h1 {
  font-size: 32px;
  margin-bottom: 20px;
  text-align: left; /* Выравнивание по левому краю */
}
.lead {
  font-size: 18px;
  color: #000000ff;
}

/* Стили для карточек новостей */
.cards {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
  margin-top: 40px;
}

.card {
  width: calc(33.33% - 20px);
  background-color: #fff;
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  overflow: hidden;
}

.card img {
  width: 100%;
  height: auto;
}

.card-body {
  padding: 20px;
}

.card-body h3 {
  font-size: 20px;
  margin-bottom: 10px;
}

.card-body .meta {
  font-size: 14px;
  color: #999;
  margin-bottom: 10px;
}

.card-body p {
  font-size: 16px;
  color: #666;
}

/* Стили для текста "Пока нет опубликованных новостей" */
.no-news {
  text-align: center;
  font-size: 18px;
  color: #999;
  margin-top: 40px;
}
</style>

<section class="wrap hero">
  <h1>Новости Центра финансового просвещения</h1>
  <p class="lead">Читайте актуальные материалы и обновления о деятельности Центра, новых инициативах и мероприятиях, направленных на повышение финансовой грамотности населения.</p>
</section>

<section class="wrap">
  <?php if ($news_result && mysqli_num_rows($news_result) > 0): ?>
    <div class="cards">
      <?php while ($n = mysqli_fetch_assoc($news_result)): ?>
        <article class="card">
          <?php if (!empty($n['image'])): ?>
            <img src="<?= e($n['image']) ?>" alt="<?= e($n['title']) ?>">
          <?php endif; ?>
          <div class="card-body">
            <h3><a href="/pages/news_detail.php?id=<?= (int)$n['id'] ?>"><?= e($n['title']) ?></a></h3>
            <p class="meta">
              Опубликовано <?= date('d.m.Y', strtotime($n['created_at'])) ?>
              <?= $n['author'] ? '• ' . e($n['author']) : '' ?>
            </p>
            <p><?= e(excerpt($n['content'], 250)) ?></p>
          </div>
        </article>
      <?php endwhile; ?>
    </div>
  <?php else: ?>
    <p>Пока нет опубликованных новостей.</p>
  <?php endif; ?>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>