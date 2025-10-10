<?php
require_once __DIR__ . '/../includes/config.php';
require_once BASE_PATH . '/includes/db_connect.php';

$page_title = 'Новости';
require_once BASE_PATH . '/includes/header.php';

/* --- Безопасные функции --- */
function e($s) {
    return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function excerpt($text, $len = 220) {
    $text = strip_tags($text);
    return (mb_strlen($text) <= $len) ? $text : mb_substr($text, 0, $len) . '…';
}

/* --- Получаем опубликованные новости --- */
$sql = "
    SELECT n.id, n.title, n.content, n.image, n.created_at, u.name AS author
    FROM news n
    LEFT JOIN users u ON n.author_id = u.id
    WHERE n.is_published = 1
    ORDER BY n.created_at DESC
";
$result = mysqli_query($connect, $sql);
?>

<style>
body {
    font-family: "YanoneKaffeesatz", sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
}
.wrap {
    max-width: 1200px;
    margin: 0 auto;
    padding: 40px 20px;
}
.hero h1 {
    font-size: 34px;
    margin-bottom: 15px;
}
.lead {
    font-size: 18px;
    color: #222;
    max-width: 900px;
}
/* Карточки новостей */
.cards {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    margin-top: 40px;
}
.card {
    width: calc(33.33% - 20px);
    background-color: #fff;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.card:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 14px rgba(0, 0, 0, 0.12);
}
.card img {
    width: 100%;
    height: 220px;
    object-fit: cover;
    display: block;
}
.card-body {
    padding: 18px 20px;
}
.card-body h3 {
    font-size: 20px;
    margin-bottom: 10px;
}
.card-body h3 a {
    color: #005bbb;
    text-decoration: none;
    transition: color 0.2s ease;
}
.card-body h3 a:hover {
    color: #003f8c;
}
.meta {
    font-size: 14px;
    color: #777;
    margin-bottom: 10px;
}
.card-body p {
    font-size: 16px;
    color: #444;
}
.no-news {
    text-align: center;
    font-size: 18px;
    color: #777;
    margin-top: 40px;
}
/* Адаптив */
@media (max-width: 1000px) { .card { width: calc(50% - 20px); } }
@media (max-width: 600px) { .card { width: 100%; } .card img { height: 180px; } }
</style>

<section class="wrap hero">
    <h1>Новости Центра финансового просвещения</h1>
    <p class="lead">
        Читайте актуальные материалы и обновления о деятельности Центра, новых инициативах и мероприятиях,
        направленных на повышение финансовой грамотности населения.
    </p>
</section>

<section class="wrap">
    <?php if ($result && mysqli_num_rows($result) > 0): ?>
        <div class="cards">
            <?php while ($n = mysqli_fetch_assoc($result)): ?>
                <?php
                // --- Определяем корректный путь к изображению ---
                $imagePath = '/project_root/assets/img/no-image.jpg'; // заглушка
                if (!empty($n['image'])) {
                    $img = basename($n['image']); // берем только имя файла
                    $imagePath = '/project_root/uploads/news/' . $img;
                }
                ?>
                <article class="card">
                    <img src="<?= e($imagePath) ?>" alt="<?= e($n['title']) ?>">
                    <div class="card-body">
                        <h3><a href="/pages/news_detail.php?id=<?= (int)$n['id'] ?>"><?= e($n['title']) ?></a></h3>
                        <p class="meta">
                            <?= date('d.m.Y', strtotime($n['created_at'])) ?>
                            <?= $n['author'] ? ' • ' . e($n['author']) : '' ?>
                        </p>
                        <p><?= e(excerpt($n['content'], 250)) ?></p>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p class="no-news">Пока нет опубликованных новостей.</p>
    <?php endif; ?>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
