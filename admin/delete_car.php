<?php
session_start();
require_once '../config/config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Метод не поддерживается']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['car_id']) && !isset($data['image_url'])) {
    echo json_encode(['success' => false, 'message' => 'Не указан car_id или image_url']);
    exit;
}

try {
    if (isset($data['image_url'])) {
        // Удаление одного изображения
        $imageUrl = basename($data['image_url']); // Берём только имя файла для безопасности

        // Находим машину с этим изображением
        $stmt = $pdo->prepare("SELECT id, images FROM cars_rental WHERE JSON_CONTAINS(images, '\"$imageUrl\"')");
        $stmt->execute();
        $car = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$car) {
            echo json_encode(['success' => false, 'message' => 'Фото не найдено']);
            exit;
        }

        $images = json_decode($car['images'], true);
        $images = array_filter($images, fn($img) => basename($img) !== $imageUrl);
        $newImagesJson = json_encode(array_values($images));

        // Обновляем запись в БД
        $stmt = $pdo->prepare("UPDATE cars_rental SET images = ? WHERE id = ?");
        $stmt->execute([$newImagesJson, $car['id']]);

        // Удаляем файл с сервера
        $filePath = "../uploads_img/" . $imageUrl;
        if (file_exists($filePath)) unlink($filePath);

        echo json_encode(['success' => true, 'message' => 'Фото удалено']);
    } elseif (isset($data['car_id'])) {
        // Удаление всей машины
        $carId = (int) $data['car_id'];

        // Получаем данные машины, включая фото
        $stmt = $pdo->prepare("SELECT images FROM cars_rental WHERE id = ?");
        $stmt->execute([$carId]);
        $car = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$car) {
            echo json_encode(['success' => false, 'message' => 'Машина не найдена']);
            exit;
        }

        // Удаляем фото с сервера
        $images = json_decode($car['images'], true) ?: [];
        foreach ($images as $image) {
            $filePath = "../uploads_img/" . basename($image);
            if (file_exists($filePath)) unlink($filePath);
        }

        // Удаляем машину из базы
        $stmt = $pdo->prepare('DELETE FROM cars_rental WHERE id = ?');
        $stmt->execute([$carId]);

        echo json_encode(['success' => true, 'message' => 'Машина и её фото удалены']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
