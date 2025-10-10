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
<main class="site-main">
  <div class="wrap grid-two">
    <div class="info-text">
      <strong>Центр</strong> — современная образовательная площадка, созданная для повышения финансовой грамотности и формирования устойчивых экономических знаний у жителей региона. Наша миссия — помочь каждому обрести уверенность в управлении личными финансами, планировать бюджет, разбираться в инвестициях и банковских продуктах, а также понимать финансовое законодательство.
    </div>
    <div class="info-image2">
      <img src="./assets/img/image 11.svg" alt="Центр финансового просвещения">
    </div>
  </div>
</main>
<div class="main-container">
  <div class="image-container">
    <img src="./assets/img/image 2.png" alt="Дед" />
  </div>
 <div class="che">Наши цели</div>
  <div class="blocks-wrapper">
    <div class="left-column">
      <div class="white-turquoise-block">
        <h3>Повышение <br>финансовой <br>грамотности населения</h3>
      </div>
      <div class="white-turquoise-block2">
        <h3>Консультирование<br> граждан</h3>
      </div>
    </div>

    <div class="right-column">
      <div class="white-turquoise-block2">
        <h3>Обучающие <br>материалы и<br> семинары</h3>
      </div>
      <div class="white-turquoise-block">
        <h3>Поддержка<br>через онлайн<br>консультации</h3>
      </div>
    </div>
  </div>
</div>

<section class="info-slider">
  <h2 class="slider-title">Мы организуем</h2>
  <div class="slider-container">
    <div class="slide active">
      <h3>Лекции</h3>
      <p>Мы организуем лекции с участием экспертов в области экономики, бухгалтерии и права.</p>
    </div>
    <div class="slide">
      <h3>Тренинги</h3>
      <p>Практикоориентированные тренинги для повышения финансовой грамотности.</p>
    </div>
    <div class="slide">
      <h3>Мастер-классы и консультации</h3>
      <p>Мастер-классы и консультации с профессионалами для глубокого понимания и решения задач.</p>
    </div>
  </div>
  <div class="slider-controls">
    <button class="prev" aria-label="Предыдущий слайд">&#10094;</button>
    <button class="next" aria-label="Следующий слайд">&#10095;</button>
  </div>
</section>


<script>
  const slides = document.querySelectorAll('.slide');
  const prevBtn = document.querySelector('.prev');
  const nextBtn = document.querySelector('.next');
  let current = 0;

  function showSlide(index) {
    slides.forEach((slide, i) => {
      slide.classList.toggle('active', i === index);
    });
  }

  prevBtn.addEventListener('click', () => {
    current = (current === 0) ? slides.length - 1 : current - 1;
    showSlide(current);
  });

  nextBtn.addEventListener('click', () => {
    current = (current + 1) % slides.length;
    showSlide(current);
  });
</script>
 <div class="cheza">Вопрос-Ответ</div>
<div class="container">
        <div class="question-answer">
            <div class="question">Что такое финансовая грамотность и почему она важна?</div>
            <div class="answer">Финансовая грамотность — это умение эффективно управлять своими доходами и расходами, планировать бюджет, правильно инвестировать и избегать долгов. Важно развивать эти навыки, чтобы принимать осознанные финансовые решения и обеспечивать финансовую безопасность.</div>
        </div>
        <div class="question-answer">
            <div class="question">Какие услуги предоставляет центр финансового просвещения?</div>
            <div class="answer"> Наш центр проводит образовательные курсы, семинары и консультации по управлению личными финансами, инвестициям, пенсионному обеспечению и вопросам финансового планирования.</div>
        </div>
        <div class="question-answer">
            <div class="question">Кому будет полезно обращаться в центр?</div>
            <div class="answer">Центр открыт для всех желающих повысить уровень финансовой грамотности: студентов, работников, пенсионеров, предпринимателей и всех, кто хочет научиться лучше распоряжаться своими деньгами.</div>
        </div>
        <div class="question-answer">
            <div class="question">Нужно ли иметь какой-то опыт для участия в курсах?</div>
            <div class="answer">Нет, наши программы рассчитаны на разный уровень знаний — от начального до продвинутого. Мы помогаем освоить финансовые основы и углубиться в более сложные темы.</div>
        </div>
        <div class="question-answer">
            <div class="question">Как записаться на обучение?</div>
            <div class="answer"> Да, мы регулярно публикуем бесплатные статьи, видеоуроки и проводим открытые вебинары, чтобы помочь большему числу людей повысить финансовую грамотность.</div>
        </div>
    </div>
  <?php include BASE_PATH . '/includes/footer.php'; ?>


      <script>
        document.querySelectorAll('.question').forEach(question => {
    question.addEventListener('click', () => {
        const answer = question.nextElementSibling;
        
        if (!question.classList.contains('active')) {
            document.querySelectorAll('.question').forEach(q => q.classList.remove('active'));
            document.querySelectorAll('.answer').forEach(a => a.style.maxHeight = '0');
        }
        
        question.classList.toggle('active');
        answer.style.maxHeight = !question.classList.contains('active') ? '0' : `${answer.scrollHeight}px`;
    });
});
      </script>