<?php
require_once '../config/config.php';

header('Content-Type: application/json; charset=utf-8');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo json_encode(['error' => 'Некорректный ID']);
    exit;
}

$carId = intval($_GET['id']);

try {
    $stmt = $pdo->prepare("SELECT * FROM cars_rental WHERE id = ?");
    $stmt->execute([$carId]);
    $car = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($car) {
        // Обрабатываем возможные null-значения
        foreach ($car as $key => $value) {
            $car[$key] = $value ?? '';
        }

        // Проверяем, есть ли поле images и обрабатываем его
        if (isset($car['images']) && !empty($car['images'])) {
            $decodedImages = json_decode($car['images'], true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $car['images'] = $decodedImages;
            } else {
                $car['images'] = [];
            }
        } else {
            $car['images'] = [];
        }

        echo json_encode($car);
    } else {
        echo json_encode(['error' => 'Авто не найдено']);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => 'Ошибка БД: ' . $e->getMessage()]);
}
