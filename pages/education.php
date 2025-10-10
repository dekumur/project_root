<?php
include __DIR__ . '/../includes/db_connect.php';
include __DIR__ . '/../includes/header.php';
?>

<style>


    @font-face {
  font-family: "YanoneKaffeesatz"; 
  src: url("../fonts/YanoneKaffeesatz.ttf") format("truetype");
  font-style: normal;
  font-weight: normal;
}
body {
  font-family: "YanoneKaffeesatz"; 
  margin: 0;
  background-color: #fff;
  color: #222;
}


.wrap {
  max-width: 1100px;
  margin: 0 auto;
  padding: 30px 20px;
}


.site-header {
  background: #fff;
  border-bottom: 1px solid #ddd;
}

.site-header .wrap {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 15px 20px;
}

.brand {
  display: flex;
  align-items: center;
  font-weight: bold;
  color: #009688;
  text-transform: uppercase;
  font-size: 14px;
}

.brand img {
  width: 50px;
  height: auto;
  margin-right: 10px;
}

.main-nav a {
  text-decoration: none;
  color: #333;
  border: 1px solid #333;
  border-radius: 20px;
  padding: 6px 14px;
  margin-left: 10px;
  transition: 0.3s;
}

.main-nav a:hover {
  background: #333;
  color: #fff;
}

.main-nav a.active {
  background: #009688;
  color: #fff;
  border-color: #009688;
}

/* Основной блок */
.hero {
  text-align: left;
  margin: 50px auto;
  line-height: 1.6;
  font-size: 20px;
  color: #333;
  max-width: 850px;
}


.groups {
  display: grid;
  grid-template-columns: repeat(2, 220px); /* две колонки фиксированной ширины */
  gap: 20px 30px; /* вертикальные и горизонтальные отступы */
  justify-content: start; /* выравнивание по левому краю */
  margin-top: 40px;
}

.group-btn {
  text-decoration: none;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  font-weight: bold;
  font-size: 16px;
  padding: 25px 15px;
  border-radius: 16px;
  width: 100%;
  transition: all 0.3s ease;
  text-align: center;
  color: #222;
  box-sizing: border-box;
}

/* Цвета кнопок */
.kids { background: #f3e4ff; border: 2px solid #d1b8ff; }
.teens { background: #ffe589; border: 2px solid #ffc800; }
.adults { background: #baf4f4; border: 2px solid #8fd3d6; }
.pensioners { background: #cde3c2; border: 2px solid #a6c79a; }

.group-btn:hover {
  transform: translateY(-4px);
  box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}


/* Иллюстрация */
.illustration {
  margin-top: 50px;
  text-align: right;
}

.illustration img {
  max-width: 280px;
  height: auto;
}

/* Адаптив */
@media (max-width: 768px) {
  .illustration {
    text-align: center;
    margin-top: 30px;
  }
}
</style>


<section class="wrap hero">
  <p>
    На странице собраны обучающие видео, документы и статьи, адаптированные для
    разных возрастных групп — от школьников и студентов до взрослых и пенсионеров.
    Материалы охватывают базовые понятия, практические советы и актуальные темы,
    позволяя каждому пользователю шаг за шагом повысить свои знания в управлении
    личными финансами, защитить себя от финансовых рисков и принимать обоснованные решения.
  </p>

  <div class="groups">
    <a href="education_kids.php" class="group-btn kids">
      Дети<br><span>6–12 лет</span>
    </a>
    <a href="education_teens.php" class="group-btn teens">
      Подростки
    </a>
    <a href="education_adults.php" class="group-btn adults">
      Взрослые
    </a>
    <a href="education_pensioners.php" class="group-btn pensioners">
      Пенсионеры
    </a>
  </div>

  <div class="illustration">
    <img src="/project_root/assets/img/image 8.png" alt="Преподаватель">

  </div>
</section>

<?php include __DIR__ . '/../includes/footer.php'; ?>
