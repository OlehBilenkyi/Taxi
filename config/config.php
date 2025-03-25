<?php
// Включаем логирование ошибок ДО запуска сессии
ini_set('log_errors', 1);
ini_set('error_log', $_SERVER['DOCUMENT_ROOT'] . '/logs/error_log.log');
error_reporting(E_ALL);

// Увеличиваем время жизни сессии до 4 часов
if (session_status() == PHP_SESSION_NONE) {
    ini_set('session.gc_maxlifetime', 14400);
    session_start();
}

// Данные для подключения к базе данных
$host = 'localhost';  // хост
$db = '';  // имя базы данных
$user = '';  // имя пользователя
$pass = '!';  // пароль
$charset = 'utf8mb4';  // кодировка базы данных

// Формируем DSN
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
    PDO::ATTR_PERSISTENT => false
];

try {
    // Подключаемся к базе данных
    $pdo = new PDO($dsn, $user, $pass, $options);
    $pdo->exec("SET NAMES 'utf8mb4'");

    // Пробуем выполнить запрос для проверки соединения
    $stmt = $pdo->query('SELECT NOW()');
    $currentDate = $stmt->fetchColumn();
    
    error_log("✅ Подключение успешно. Текущая дата из базы: $currentDate.");

} catch (PDOException $e) {
    // Если подключение не удалось, выводим ошибку
    error_log("❌ Ошибка при подключении к базе данных: " . $e->getMessage());
    http_response_code(500);

    $errorPagePath = $_SERVER['DOCUMENT_ROOT'] . '/includes/error_page.php';
    if (file_exists($errorPagePath)) {
        include($errorPagePath);
    } else {
        echo '<h1>Произошла ошибка</h1>';
        echo '<p>Сейчас невозможно подключиться к базе. Попробуйте позже.</p>';
    }
    exit();
}
?>
