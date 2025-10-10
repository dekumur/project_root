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


  body {
    font-family: Arial, sans-serif;
    margin: 0;
    background:rgb(255, 255, 255);
    color: #222;
  }

  .wrap {
    max-width: 1100px;
    margin: 0 auto;
    padding: 20px;
  }

  a {
    color: #005bbb;
    text-decoration: none;
  }

  a:hover {
    text-decoration: underline;
  }

  .site-header {
    background: #fff;
    border-bottom: 1px solid #ddd;
  }

  .site-header .wrap {
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  .brand {
    font-weight: bold;
    color: #005bbb;
  }

  .main-nav a {
    margin: 0 10px;
    color: #333;
  }

  .main-nav a:hover {
    color: #005bbb;
  }

  .hero {
    background: #eef3fa;
    padding: 30px 20px;
    margin-bottom: 20px;
  }

  .hero h1 {
    margin: 0 0 10px;
  }

  .cards {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
  }

  .card {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 8px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
  }

  .card img {
    width: 100%;
    max-height: 160px;
    object-fit: cover;
  }

  .card-body {
    padding: 15px;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
  }

  .card-body h3 {
    margin-top: 0;
    margin-bottom: 10px;
  }

  .meta {
    font-size: 0.9em;
    color: #777;
    margin-bottom: 10px;
  }

  .more {
    margin-top: auto;
    font-weight: bold;
    color: #005bbb;
  }

  .site-footer {
    background: #fff;
    border-top: 1px solid #ddd;
    padding: 15px;
    text-align: center;
    margin-top: 30px;
  }

  @media (max-width: 768px) {
    .cards {
      grid-template-columns: 1fr;
    }
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