<?php
include __DIR__ . '/../includes/db_connect.php';
$page_title = 'Статьи для детей';
include __DIR__ . '/../includes/header.php';

$audience = 'kids';
$sql_articles = "SELECT * FROM materials WHERE audience='$audience' AND type='article' ORDER BY created_at DESC";
$articles = mysqli_query($connect, $sql_articles);
if (!$articles) {
    die("Ошибка SQL (articles): " . mysqli_error($connect));
}
?>

<style>
.page-wrap {
  max-width: 900px;
  margin: 40px auto;
  padding: 0 20px;
  line-height: 1.6;
}

.page-title {
  text-align: center;
  color: #7c4dff;
  margin-bottom: 20px;
}

.page-subtitle {
  text-align: center;
  color: #444;
  margin-bottom: 40px;
}

.article {
  background: #fff;
  border-radius: 10px;
  border: 1px solid #ddd;
  padding: 25px 30px;
  margin-bottom: 30px;
  box-shadow: 0 3px 8px rgba(0,0,0,0.05);
}

.article h2 {
  margin-top: 0;
  color: #333;
  font-size: 22px;
}

.article p {
  color: #555;
  font-size: 16px;
}

.article a.download-link {
  display: inline-block;
  margin-top: 15px;
  color: #7c4dff;
  font-weight: bold;
  text-decoration: none;
}

.article a.download-link:hover {
  text-decoration: underline;
}

.no-articles {
  text-align: center;
  font-size: 18px;
  color: #777;
  margin-top: 40px;
}
</style>

<div class="page-wrap">
  <h1 class="page-title">Статьи для детей</h1>
  <p class="page-subtitle">Обучающие материалы по финансовой грамотности для детей 6–12 лет.</p>

  <?php if (mysqli_num_rows($articles) > 0): ?>
    <?php while ($row = mysqli_fetch_assoc($articles)): ?>
      <div class="article">
        <h2><?= htmlspecialchars($row['title']) ?></h2>
        <p><?= nl2br(htmlspecialchars($row['description'])) ?></p>
        <?php if (!empty($row['file_path'])): ?>
          <a class="download-link" href="<?= htmlspecialchars($row['file_path']) ?>" target="_blank">Скачать материал</a>
        <?php endif; ?>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <p class="no-articles">Пока нет статей для этой возрастной группы.</p>
  <?php endif; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
