<?php
session_start();

// Настройки ошибок
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Подключение БД
include($_SERVER['DOCUMENT_ROOT'] . '/config/config.php');

// Функция для безопасного получения изображений
// Функция для безопасного получения изображения
function getCarImage($imagesJson, $defaultImage = '/uploads_img/default-car.jpg') {
    $images = json_decode($imagesJson, true); // Декодируем JSON
    
    if (!empty($images) && is_array($images)) {
        $imagePath = '/' . ltrim($images[0], '/'); // Берем первый путь

        return htmlspecialchars($imagePath, ENT_QUOTES, 'UTF-8'); // Защита от XSS
    }

    return $defaultImage;
}



try {
    // Подготовленный запрос
   $stmt = $pdo->query("
    SELECT 
        id,
        name,
        description,
        version,
        rental_price,
        deposit_price,
        images,
        mileage,
        transmission,
        fuel,
        consumption,
        created_at,
        updated_at
    FROM cars_rental
    ORDER BY created_at DESC
    ");
    
    $cars = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Обработка данных
   // Обработка данных
foreach ($cars as &$car) {
    // Обрабатываем только одно изображение (первое)
    $car['main_image'] = getCarImage($car['images'] ?? '[]');

    // Форматируем цены
    $car['rental_price'] = number_format($car['rental_price'], 0, '', ' ');
    $car['deposit_price'] = number_format($car['deposit_price'], 0, '', ' ');
    
    // Проверка на наличие 'created_at' и 'updated_at', чтобы избежать ошибок
    $car['created_at'] = isset($car['created_at']) ? htmlspecialchars($car['created_at']) : 'Не указано';
    $car['updated_at'] = isset($car['updated_at']) ? htmlspecialchars($car['updated_at']) : 'Не указано';
}

} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    die("Ошибка базы данных. Пожалуйста, попробуйте позже.");
} catch (Throwable $e) {
    error_log("General error: " . $e->getMessage());
    die("Произошла ошибка. Мы уже работаем над её устранением.");
}
?>

<!DOCTYPE html>
<html lang="ru">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Аренда авто под такси — Варшава</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
      referrerpolicy="no-referrer"
    />
    <style>
      * {
        font-family: Anton;
        margin: 0;
        padding: 0;
        box-sizing: border-box;
      }
      html {
        scroll-behavior: smooth;
      }
      body {
        font-family: "Montserrat", sans-serif;
        line-height: 1.5;
      }
      a {
        text-decoration: none;
      }

      /* CSS-переменные */
      :root {
        --color-primary: #f8f8f8; /* Изменён для лучшей читаемости на темном фоне */
        --color-secondary: #3fd10f; /* Яркий зеленый */
        --color-tertiary: #1a1a1a; /* Основной фон */
        --color-action: #3fd10f;
        --color-white: #fff;
        --color-dark: #333;
        --color-shadow: rgba(0, 0, 0, 0.5);
        --color-accent: #e74c3c;
        --transition: all 0.3s ease;
      }

      body {
  margin: 0;
  padding: 0;
  background: url("./Flux_Dev_____Mercedes_EClass_______TAXI____________TAXI________1.jpeg") center/cover no-repeat fixed;
  /* fixed — чтобы при прокрутке картинка оставалась на месте */
  color: #f8f8f8; /* текст по умолчанию */
}

      /* Шапка и футер */
      body.dark-theme header,
      body.dark-theme footer {
        background-color: #2b2b2b;
      }

      /* Навигация */
      body.dark-theme .nav-link {
        color: var(--color-primary);
      }
      body.dark-theme .nav-link:hover {
        color: var(--color-secondary);
      }

      /* Кнопки */
      body.dark-theme .btn,
      body.dark-theme .btn-hero,
      body.dark-theme .btn-rent {
        background-color: var(--color-secondary);
        color: var(--color-tertiary);
      }
      body.dark-theme .btn:hover,
      body.dark-theme .btn-hero:hover,
      body.dark-theme .btn-rent:hover {
        background-color: #2e9a0a;
      }

      /* Header */
      header {
        position: fixed;
        top: 0;
        width: 100%;
        padding: 10px 0;
        z-index: 1000;
        background: transparent;
        transition: background 0.3s ease, box-shadow 0.3s ease;
      }
      header.scrolled {
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.5);
      }
      .header-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
      }
      .logo {
        line-height: 1.1;
        font-family: "Arial", sans-serif;
        font-size: 1.8rem;
        font-weight: 700;
        letter-spacing: 1.5px;
        color: var(--color-primary);
      }
      .logo .logo-accent {
        color: var(--color-secondary);
      }

      /* Навигация */
      .nav-links {
        font-size: 19px;
        display: flex;
        gap: 2rem;
        align-items: center;
      }
      .nav-link {
        position: relative;
        padding: 0.5rem 1rem;
        transition: var(--transition);
      }
      .nav-link:hover {
        color: var(--color-secondary);
      }
      .nav-link::after {
        content: "";
        position: absolute;
        bottom: -5px;
        left: 0;
        width: 0;
        height: 2px;
        background-color: var(--color-secondary);
        transition: var(--transition);
      }
      .nav-link:hover::after {
        width: 100%;
      }

      /* Dropdown */
      .dropdown {
        position: relative;
        display: inline-block;
      }
      .dropbtn {
        font-size: 16px;
        background: none;
        border: none;
        padding: 0.5rem 1rem;
        cursor: pointer;
        transition: var(--transition);
        color: inherit;
      }
      .dropbtn:hover {
        color: var(--color-secondary);
      }
      .dropdown-content {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        min-width: 200px;
        border-radius: 4px;
        overflow: hidden;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.5);
        background-color: #2b2b2b;
      }
      .dropdown-content a {
        padding: 12px 16px;
        display: block;
        transition: background 0.3s;
        color: var(--color-primary);
      }
      .dropdown-content a:hover {
        background: #3a3a3a;
      }
      .dropdown:hover .dropdown-content {
        display: block;
      }

      /* Mobile menu */
      .mobile-menu-btn {
        display: none;
        flex-direction: column;
        gap: 4px;
        cursor: pointer;
      }
      .mobile-menu-btn span {
        width: 25px;
        height: 2px;
        background: var(--color-primary);
        transition: var(--transition);
      }
      @media (max-width: 1024px) {
        .nav-links {
          display: none;
        }
        .mobile-menu-btn {
          display: flex;
        }
        .header-container {
          padding: 0 1rem;
        }
        .logo {
          font-size: 1.5rem;
        }
      }

      .main-container {
        max-width: 1200px;
        margin: 0 auto;
      }

      .hero {
        min-height: 80vh;
        position: relative;
        background: none; /* убираем фон-картинку из hero */
        display: flex;
        justify-content: center;
        align-items: center;
        text-align: center;
        color: #f8f8f8;
      }
      .hero::before {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5));
        z-index: 1;
      }
      .hero-content {
        position: relative;
        z-index: 2;
        max-width: 1200px;
        padding: 0 20px;
        text-align: center;
      }
      .hero-content h1 {
        font-family: Oswald;
        font-size: 66px;
        margin-bottom: 20px;
        text-transform: uppercase;
        letter-spacing: 2px;
        line-height: 1.2;
      }
      .hero-content p {
        font-size: 35px;
        margin-bottom: 30px;
      }
      .highlight {
        color: #3fd10f;
        font-weight: bold;
      }
      .btn-hero {
        display: inline-block;
        background-color: var(--color-secondary);
        color: var(--color-tertiary);
        padding: 15px 30px;
        border-radius: 5px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: background 0.3s, transform 0.3s;
      }
      .btn-hero:hover {
        background-color: #2e9a0a;
        transform: translateY(-3px);
      }
      @media (max-width: 768px) {
        .hero-content h1 {
          font-size: 2.2rem;
        }
        .hero-content p {
          font-size: 1rem;
        }
      }

      /* Секции */
      .section {
        padding: 60px 20px;
      }
      .section h2 {
        text-align: center;
        font-size: 2rem;
        position: relative;
      }

      .carH2 {
        padding: 60px 0 20px;
      }

      /* Автопарк */
      #autopark {
        position: relative;
        background: rgba(0, 0, 0, 0.2); /* или none, если хотите полностью прозрачный */
        padding: 20px 20px;
      }
      .autopark-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
      }
      .car-card {
        border: 2px solid #3fd10f;
        background: #2b2b2b;
        border-radius: 8px;
        box-shadow: 0 2px 5px var(--color-shadow);
        padding: 20px;
        display: flex;
        flex-direction: column;
        transition: transform 0.3s, box-shadow 0.3s;
      }
      .car-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px #3fd10f;
      }
      .car-image {
        width: 100%;
        height: 180px;
        overflow: hidden;
        border-radius: 5px;
        margin-bottom: 15px;
      }
      .car-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s;
      }
      .car-card:hover .car-image img {
        transform: scale(1.05);
      }
      .carfl{
          display: flex;
          justify-content: space-around;
      }
      .car-title {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 10px;
        color: var(--color-primary);
      }
      .car-price {
        font-size: 1rem;
        font-weight: 500;
        color: var(--color-secondary);
        margin-bottom: 10px;
      }
      .car-details {
        font-size: 0.9rem;
        margin-bottom: 20px;
      }
      .car-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
      }
      .btn-rent {
        background-color: var(--color-secondary);
        color: var(--color-tertiary);
        padding: 10px 20px;
        border-radius: 4px;
        font-weight: 600;
        transition: background 0.3s, transform 0.3s;
      }
      .btn-rent:hover {
        background-color: #2e9a0a;
        transform: translateY(-3px);
      }

      /* Новости и отзывы */
      .news-reviews-section {
        background-color: #1a1a1a;
        display: flex;
        flex-wrap: wrap;
        gap: 40px;
        padding: 60px 20px;
      }
      .news-list,
      .reviews-list {
        flex: 1;
        min-width: 300px;
      }
      .news-item,
      .review-item {
        background: #2b2b2b;
        border-radius: 8px;
        box-shadow: 0 2px 5px var(--color-shadow);
        padding: 20px;
        margin-bottom: 20px;
        transition: transform 0.3s, box-shadow 0.3s;
      }
      .news-item:hover,
      .review-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.7);
      }
      .news-item h3,
      .review-item h3 {
        font-size: 1.2rem;
        margin-bottom: 10px;
        color: var(--color-primary);
      }
      .news-item p,
      .review-item p {
        font-size: 1rem;
        margin-bottom: 10px;
        line-height: 1.4;
        color: #ccc;
      }
      .news-item small,
      .review-item small {
        color: #777;
      }

      /* Видео */
      .video-section {
        padding: 4rem 0;
      }
      .video-wrapper {
        position: relative;
        width: 100%;
        padding-bottom: 46.25%; /* Соотношение 16:9 */
        margin: 2rem 0;
        border-radius: 1.5rem;
        overflow: hidden;
        box-shadow: 0 12px 24px -6px rgba(0, 0, 0, 0.7);
      }

      /* ВАЖНО: iframe внутри .video-wrapper должен занимать всё пространство */
      .video-wrapper iframe {
        /* padding-left: 20px; */
        border-radius: 1.5rem;
        padding: 15px 20px;
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: none;
      }
      .small-videos {
        padding: 0 20px;
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
        max-width: 1200px;
        margin: 2rem auto 0;
      }
      .small-video-wrapper {
        position: relative;
        border-radius: 1rem;
        overflow: hidden;
        transition: transform 0.3s ease;
        aspect-ratio: 16/9;
      }
      .small-video-wrapper:hover {
        transform: translateY(-5px);
      }
      .small-video-wrapper iframe {
        width: 100%;
        height: 100%;
        border: none;
      }
      @media (max-width: 768px) {
        .small-videos {
          grid-template-columns: 1fr;
          max-width: 600px;
        }
      }

      /* Футер */
      footer {
        background-color: #2b2b2b;
        padding: 20px;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
      }
      .footer-left,
      .footer-right {
        flex: 1 1 300px;
        margin: 10px;
      }
      .footer-left p,
      .footer-right p {
        margin-bottom: 10px;
      }
      .footer-right a {
        color: var(--color-primary);
        transition: var(--transition);
      }
      .footer-right a:hover {
        color: var(--color-secondary);
      }
      .social-icons {
        display: flex;
        gap: 15px;
        margin-bottom: 10px;
      }

      /* Анимация появления секций */
      .hidden {
        opacity: 0;
        transform: translateY(20px);
        transition: opacity 0.6s, transform 0.6s;
      }
      .show {
        opacity: 1;
        transform: translateY(0);
      }
      .advantages-section {
        background-color: #1a1a1a;
        padding: 60px 20px 0;
      }

      .advantages-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
      }

      .advantage-card {

        background: #2b2b2b;
        color: #f8f8f8;
        border: 2px solid #3fd10f;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.5);
        padding: 20px;
        transition: transform 0.3s, box-shadow 0.3s, border 0.3s;
      }

      .advantage-card h3 {
        font-size: 1.2rem;
        margin-bottom: 10px;

        color: #f8f8f8;
      }

      .advantage-card p {
        font-size: 1rem;
        margin-bottom: 10px;
        line-height: 1.4;

        color: #ccc;
      }

      .video-section{
        background-color: #1a1a1a;
      }

      .telegram-float {
          position: fixed;       /* фиксируем в окне */
          bottom: 140px;          /* отступ снизу */
          right: 40px;           /* отступ справа */
          background-color: #3fd10f; /* брендовый цвет Telegram или любой другой */
          color: #fff;
          padding: 5px;
          border-radius: 50%;    /* скругляем, чтобы получился круг */
          box-shadow: 0 2px 5px rgba(0,0,0,0.3);
          text-align: center;
          z-index: 9999;         /* чтобы было поверх других элементов */
          cursor: pointer;
        }
        .telegram-float i {
          font-size: 70px;       /* размер иконки */
        }
        .telegram-float:hover {
          background-color: #007ab8; /* эффект при наведении */
        }

      </style>
     </head>
 <body class="dark-theme">
    <header class="header">
      <div class="header-container">
        <div class="logo">
          <span class="logo-primary">SHPAX</span>
          <span class="logo-accent">ARENDA</span>
        </div>

        <nav class="nav-menu">
          <div class="nav-links">
            <a href="#hero" class="nav-link">Главная</a>
            <a href="#autopark" class="nav-link">Автопарк</a>
            <a href="#news" class="nav-link">Новости</a>
            <a href="#video" class="nav-link">Видео</a>

            <a href="#footer" class="nav-link">Контакты</a>
            <div class="dropdown">
              <button class="dropbtn">Документы ▼</button>
              <div class="dropdown-content">
                <a href="admin/dashboard.php">Админка</a>
                <a href="form/">Формы</a>
                <a href="privacy_policy/">Политика</a>
                <a href="regulamin/">Регламент</a>
                <a href="index.html">Главная страница</a>
              </div>
            </div>
          </div>
        </nav>

        <div class="mobile-menu-btn">
          <span></span>
          <span></span>
          <span></span>
        </div>
      </div>
    </header>
<div class="main-container">
    <!-- HERO (главная секция) -->
    <section class="hero" id="hero">
      <div class="hero-content">
        <h1>Аренда авто Варшава</h1>
        <p>
          Зарабатывайте от
          <span class="highlight">6 000</span>
          до
          <span class="highlight">8 000</span>
          zl<br> в месяц на работе в такси
        </p>
        <a href="#autopark" class="btn-hero">Выбрать автомобиль</a>
      </div>
    </section>

    <!-- НАШИ ПРЕИМУЩЕСТВА -->
    <section class="section advantages-section" id="advantages">
      <h2>Наши преимущества</h2>
      <div class="advantages-grid">
        <div class="advantage-card">
          <h3>Даем лучшие условия на рынке</h3>
          <p>Нет залога и депозита на старте</p>
          <p>Моментальный вывод, комиссия – 0,6%</p>
        </div>
        <div class="advantage-card">
          <h3>С нами выгодно</h3>
          <p>Нет штрафов за перепробег</p>
          <p>На время сложного ремонта выдаем подменный авто</p>
        </div>
        <div class="advantage-card">
          <h3>Работаем честно</h3>
          <p>Простой и понятный договор</p>
          <p>Нет скрытых платежей</p>
        </div>
        <div class="advantage-card">
          <h3>С нами комфортно</h3>
          <p>Поддержка 24/7 – отвечаем быстро и по делу</p>
          <p>
            Решаем споры с поддержкой Яндекса – мы отстаиваем позицию водителя
          </p>
        </div>
      </div>
      <h2 class="carH2">Автопарк</h2>
    </section>

<!-- Выводим данные о машинах -->
<section class="section" id="autopark">
    <div class="autopark-grid">
        <?php foreach ($cars as $car): ?>
  
    <div 
          class="car-card"
          data-name="<?= htmlspecialchars($car['name']) ?>"
          data-mileage="<?= htmlspecialchars($car['mileage']) ?>"
          data-transmission="<?= htmlspecialchars($car['transmission']) ?>"
          data-fuel="<?= htmlspecialchars($car['fuel']) ?>"
          data-consumption="<?= htmlspecialchars($car['consumption']) ?>"
          data-rental-price="<?= htmlspecialchars($car['rental_price']) ?>"
          data-deposit-price="<?= htmlspecialchars($car['deposit_price']) ?>"
          data-images='<?= htmlspecialchars($car['images'], ENT_QUOTES, 'UTF-8') ?>'
        >
            <div class="car-image">
                <img src="<?= htmlspecialchars($car['main_image']) ?>" alt="<?= htmlspecialchars($car['name']) ?>">
            </div>
            <div class = "carfl">
            <div class="car-title"><?= htmlspecialchars($car['name']) ?></div>
            <div class="car-version"><?= htmlspecialchars($car['version']) ?></div>
            </div>
            <div class="car-price">от <?= htmlspecialchars($car['rental_price']) ?> злотых/неделя</div>
            <div class="car-deposit">Залог: <?= htmlspecialchars($car['deposit_price']) ?> злотых</div>
            <div class="car-description"><?= nl2br(htmlspecialchars($car['description'])) ?></div>
            
            <div class="car-actions">
                <a href="/form/" class="btn-rent">Арендовать</a>
            </div>
        </div>
<?php endforeach; ?>

    </div>
</section>

    <!-- НОВОСТИ И ОТЗЫВЫ -->
    <section class="section news-reviews-section" id="news">
      <div class="news-list">
        <h2>Новости</h2>
        <div class="news-item">
          <h3>Новое авто в наличии!</h3>
          <p>
            В нашем автопарке появилось новое авто: Tesla Model S 2025 года.
            Отличный вариант для тех, кто хочет работать на электромобиле!
          </p>
          <small>01.03.2025</small>
        </div>
        <div class="news-item">
          <h3>Снижена цена на аренду Toyota Corolla</h3>
          <p>
            Теперь аренда популярной Toyota Corolla стала еще доступнее. Спешите
            воспользоваться предложением!
          </p>
          <small>25.02.2025</small>
        </div>
      </div>
      <div class="reviews-list">
        <h2>Отзывы</h2>
        <div class="review-item">
          <h3>Отличный сервис!</h3>
          <p>
            Очень доволен качеством обслуживания и автомобилями. Рекомендую!
          </p>
          <small>Иван И.</small>
        </div>
        <div class="review-item">
          <h3>Быстрое оформление</h3>
          <p>
            Быстро оформили документы, машина в отличном состоянии. Спасибо!
          </p>
          <small>Мария К.</small>
        </div>
      </div>
    </section>

    <!-- ВИДЕО (YouTube) -->
<section class="section video-section" id="video">
  <h2>Смотрите наши видео на YouTube</h2>
  <p><h3>Посмотрите наш обзор сервиса и машин:</h3></p>
  <div class="video-wrapper">
    <iframe
      src="https://www.youtube.com/embed/Ts4vw0FcTjQ?start=938"
      frameborder="0"
      allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
      allowfullscreen
    ></iframe>
  </div>
  <div class="small-videos">
    <div class="small-video-wrapper">
      <iframe
        src="https://www.youtube.com/embed/X_MMoTsc_mQ"
        frameborder="0"
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
        allowfullscreen
      ></iframe>
    </div>
    <div class="small-video-wrapper">
      <iframe
        src="https://www.youtube.com/embed/igqSHHCIixk"
        frameborder="0"
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
        allowfullscreen
      ></iframe>
    </div>
    <div class="small-video-wrapper">
      <iframe
        src="https://www.youtube.com/embed/VIDEO_ID_3"
        frameborder="0"
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
        allowfullscreen
      ></iframe>
    </div>
  </div>
</section>
</div>

<div class="telegram-float">
  <a href="https://t.me/SHPAX_ARENDA" target="_blank" rel="noopener" style= "color: black;">
    <i class="fab fa-telegram-plane"></i>
  </a>
</div>

    <footer>
      <div style="flex: 1; max-width: 45%; text-align: left">
        <p style="font-size: 1.2rem; margin-bottom: 10px">
          Аренда авто под такси в Варшаве
        </p>
        <p style="font-size: 1rem; margin-bottom: 10px">
          Свяжитесь с нами:
          <a
            href="tel:+48123456789"
            style="color: #3fd10f; text-decoration: none"
            >+48 123 456 789</a
          >
          |
          <a
            href="mailto:info@shpax-arenda.pl"
            style="color: #3fd10f; text-decoration: none"
            >info@shpax-arenda.pl</a
          >
        </p>
        <p style="font-size: 0.9rem; color: #777">
          © 2025 Аренда авто под такси в Варшаве. Все права защищены.
        </p>
      </div>
      <div style="flex: 1; max-width: 45%; text-align: right">
        <div
          style="
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            margin-bottom: 10px;
          "
        >
          <!-- <a
            href="https://t.me/SHPAX_ARENDA"
            target="_blank"
            rel="noopener"
            style="color: #333; transition: 0.3s"
          >
            <i class="fab fa-telegram-plane" style="font-size: 1.5rem"></i>
          </a> -->
          <a
            href="https://www.instagram.com/nikitashpakovski"
            target="_blank"
            rel="noopener"
            style="color: #3fd10f; transition: 0.3s"
          >
            <i class="fab fa-instagram" style="font-size: 1.5rem"></i>
          </a>
          <a
            href="https://www.youtube.com/@Shpakovskyi"
            target="_blank"
            rel="noopener"
            style="color: #3fd10f; transition: 0.3s"
          >
            <i class="fab fa-youtube" style="font-size: 1.5rem"></i>
          </a>
        </div>
        <p style="font-size: 1rem; margin-bottom: 10px">
          Адрес: ул. Примерная, 10, Варшава<br />График работы: Пн–Пт с 9:00 до
          18:00
        </p>
      </div>
    </footer>


     <!--модальное окно-->
    <!-- Полупрозрачная подложка под модальным окном -->
<div id="modalOverlay" style="
  display:none;
  position:fixed; 
  top:0; left:0; 
  width:100%; height:100%;
  background:rgba(0,0,0,0.7); 
  z-index:9998;
"></div>

<!-- Само модальное окно -->
<div id="carModal" style="
  display:none;
  position:fixed; 
  top:50%; left:50%;
  transform:translate(-50%, -50%);
  background:#fff; 
  color:#000; 
  padding:20px; 
  z-index:9999;
  width:90%;
  max-width:600px;
  border-radius:8px;
">
  <!-- Кнопка закрыть -->
  <button id="modalClose" style="float:right;">Закрыть</button>

  <h2 id="modalCarName" style="margin-top:0;"></h2>

  <p>Пробег: <span id="modalCarMileage"></span></p>
  <p>Коробка: <span id="modalCarTransmission"></span></p>
  <p>Топливо: <span id="modalCarFuel"></span></p>
  <p>Расход: <span id="modalCarConsumption"></span></p>
  <p>Цена аренды: <span id="modalCarPrice"></span> zł</p>
  <p>Залог: <span id="modalCarDeposit"></span> zł</p>

  <!-- Галерея картинок (простейшая) -->
  <div style="position:relative; margin-top:20px;">
    <img 
      id="modalCarImage" 
      src="" 
      alt="Car image" 
      style="width:100%; max-height:300px; object-fit:cover;"
    >
    <!-- Кнопки "Назад"/"Вперёд" -->
    <button 
      id="modalPrev" 
      style="position:absolute; top:50%; left:0; transform:translateY(-50%);"
    >
      ←
    </button>
    <button 
      id="modalNext" 
      style="position:absolute; top:50%; right:0; transform:translateY(-50%);"
    >
      →
    </button>
  </div>
</div>



    <script>
      /*************************************************************
      3. Intersection Observer (плавное появление секций)
      *************************************************************/
      const sections = document.querySelectorAll(".section");
      const options = { threshold: 0.1 };
      const io = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add("show");
          }
        });
      }, options);
      sections.forEach((sec) => {
        sec.classList.add("hidden");
        io.observe(sec);
      });

      // для динамического обновления видео с Ютуба
      async function fetchLatestVideos() {
        const apiKey = "YOUR_YOUTUBE_API_KEY";
        const channelId = "YOUR_CHANNEL_ID";
        const url = `https://www.googleapis.com/youtube/v3/search?key=${apiKey}&channelId=${channelId}&part=snippet,id&order=date&maxResults=4`;

        const response = await fetch(url);
        const data = await response.json();

        const mainVideo = document.querySelector(".video-wrapper iframe");
        const smallVideos = document.querySelectorAll(
          ".small-video-wrapper iframe"
        );

        mainVideo.src = `https://www.youtube.com/embed/${data.items[0].id.videoId}`;
        smallVideos.forEach((video, index) => {
          video.src = `https://www.youtube.com/embed/${
            data.items[index + 1].id.videoId
          }`;
        });
      }

      fetchLatestVideos();

      // для хеадер
      document.querySelector('.mobile-menu-btn').addEventListener('click', function() {
  const navLinks = document.querySelector('.nav-links');
  navLinks.style.display = navLinks.style.display === 'flex' ? 'none' : 'flex';
});

    // Ищем элементы модалки
  const modal = document.getElementById('carModal');
  const modalOverlay = document.getElementById('modalOverlay');
  const modalCloseBtn = document.getElementById('modalClose');

  // Поля внутри модалки
  const modalCarName         = document.getElementById('modalCarName');
  const modalCarMileage      = document.getElementById('modalCarMileage');
  const modalCarTransmission = document.getElementById('modalCarTransmission');
  const modalCarFuel         = document.getElementById('modalCarFuel');
  const modalCarConsumption  = document.getElementById('modalCarConsumption');
  const modalCarPrice        = document.getElementById('modalCarPrice');
  const modalCarDeposit      = document.getElementById('modalCarDeposit');

  // Картинка и кнопки переключения
  const modalCarImage = document.getElementById('modalCarImage');
  const modalPrevBtn  = document.getElementById('modalPrev');
  const modalNextBtn  = document.getElementById('modalNext');

  // Массив с путями к изображениям текущей машины
  let imagesArray = [];
  let currentImageIndex = 0;

  // Навешиваем обработчик на все карточки
  document.querySelectorAll('.car-card').forEach(card => {
    card.addEventListener('click', (e) => {
      // Если не хотим, чтобы клик по кнопке "Арендовать" тоже открывал модалку,
      // можем добавить проверку:
      if (e.target.classList.contains('btn-rent')) {
        // Тогда при клике на "Арендовать" просто выходим
        return;
      }

      // Считываем данные из data-атрибутов
      const name         = card.getAttribute('data-name');
      const mileage      = card.getAttribute('data-mileage');
      const transmission = card.getAttribute('data-transmission');
      const fuel         = card.getAttribute('data-fuel');
      const consumption  = card.getAttribute('data-consumption');
      const rentalPrice  = card.getAttribute('data-rental-price');
      const depositPrice = card.getAttribute('data-deposit-price');

      // Считываем JSON-строку и парсим в массив
      const imagesJson   = card.getAttribute('data-images') || '[]';
      imagesArray        = JSON.parse(imagesJson);
      currentImageIndex  = 0;

      // Заполняем модалку
      modalCarName.textContent         = name;
      modalCarMileage.textContent      = mileage;
      modalCarTransmission.textContent = transmission;
      modalCarFuel.textContent         = fuel;
      modalCarConsumption.textContent  = consumption;
      modalCarPrice.textContent        = rentalPrice;
      modalCarDeposit.textContent      = depositPrice;

      // Показываем первую картинку (если есть)
      updateModalImage();

      // Показываем модалку и подложку
      modal.style.display = 'block';
      modalOverlay.style.display = 'block';
    });
  });

  // Функция, которая обновляет картинку в модалке
  function updateModalImage() {
    if (imagesArray.length > 0) {
      // Убедимся, что путь корректный. Если в БД хранится "uploads_img/xxx.jpg", 
      // возможно, стоит подставлять "/" или полный путь.
      modalCarImage.src = '/' + imagesArray[currentImageIndex].replace(/^\/+/, '');
    } else {
      modalCarImage.src = '/uploads_img/default-car.jpg';
    }
  }

  // Кнопки переключения
  modalPrevBtn.addEventListener('click', (e) => {
    e.stopPropagation(); // чтобы клик не всплывал и не закрывал модалку
    if (imagesArray.length > 0) {
      currentImageIndex = (currentImageIndex - 1 + imagesArray.length) % imagesArray.length;
      updateModalImage();
    }
  });

  modalNextBtn.addEventListener('click', (e) => {
    e.stopPropagation();
    if (imagesArray.length > 0) {
      currentImageIndex = (currentImageIndex + 1) % imagesArray.length;
      updateModalImage();
    }
  });

  // Закрываем модалку
  modalCloseBtn.addEventListener('click', () => {
    modal.style.display = 'none';
    modalOverlay.style.display = 'none';
  });

  // Клик по подложке тоже закрывает
  modalOverlay.addEventListener('click', () => {
    modal.style.display = 'none';
    modalOverlay.style.display = 'none';
  });


    </script>
    


    
  </body>
</html>
