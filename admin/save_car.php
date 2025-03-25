<?php
session_start();
require_once '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $id = $_POST['car_id'] ?? null;
        $name = $_POST['name'];
        $description = $_POST['description'];
        $version = $_POST['version'];
        $rental_price = $_POST['rental_price'];
        $deposit_price = $_POST['deposit_price'];
        $image_url = $_POST['image_url'];
        $status = $_POST['status'];
        $deleted_images = json_decode($_POST['deleted_images'] ?? '[]', true);

        if (empty($name) || empty($description) || empty($version) || empty($rental_price) || empty($deposit_price)) {
            throw new Exception('Ошибка: не все данные были переданы.');
        }

        // Получаем текущие изображения
        $stmt = $pdo->prepare('SELECT images FROM cars_rental WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $current_images = json_decode($stmt->fetchColumn(), true) ?? [];

        // Удаляем отмеченные изображения
        foreach ($deleted_images as $image) {
            if (file_exists($image)) {
                unlink($image);
            }
        }
        $current_images = array_diff($current_images, $deleted_images);

        // Добавляем новые изображения
        $new_images = [];
        if (!empty($_FILES['images']['name'][0])) {
            $uploadDir = '../uploads_img/';
            if (!is_dir($uploadDir)) {
                if (!mkdir($uploadDir, 0755, true)) {
                    throw new Exception('Ошибка: не удалось создать директорию для загрузки изображений.');
                }
            }

            foreach ($_FILES['images']['tmp_name'] as $key => $tmpName) {
                $imageName = uniqid() . '_' . basename($_FILES['images']['name'][$key]);
                $imagePath = $uploadDir . $imageName;
                $imageType = exif_imagetype($tmpName);

                if ($imageType && move_uploaded_file($tmpName, $imagePath)) {
                    $new_images[] = '/' . $uploadDir . $imageName; // Используем прямой слэш
                } else {
                    throw new Exception('Ошибка: неверный тип файла или ошибка загрузки.');
                }
            }
        }

        // Объединяем изображения
        $all_images = array_merge($current_images, $new_images);

        if ($id) {
            $stmt = $pdo->prepare('UPDATE cars_rental SET
                name = :name,
                description = :description,
                version = :version,
                rental_price = :rental_price,
                deposit_price = :deposit_price,
                image_url = :image_url,
                status = :status,
                images = :images
                WHERE id = :id');
            $stmt->execute([
                'name' => $name,
                'description' => $description,
                'version' => $version,
                'rental_price' => $rental_price,
                'deposit_price' => $deposit_price,
                'image_url' => $image_url,
                'status' => $status,
                'images' => json_encode($all_images),
                'id' => $id
            ]);
        } else {
            $stmt = $pdo->prepare('INSERT INTO cars_rental (name, description, version, rental_price, deposit_price, image_url, status, images) VALUES (:name, :description, :version, :rental_price, :deposit_price, :image_url, :status, :images)');
            $stmt->execute([
                'name' => $name,
                'description' => $description,
                'version' => $version,
                'rental_price' => $rental_price,
                'deposit_price' => $deposit_price,
                'image_url' => $image_url,
                'status' => $status,
                'images' => json_encode($all_images)
            ]);
        }

        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}
?>
