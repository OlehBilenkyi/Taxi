<?php
require 'db.php'; // Подключение к БД

$data = json_decode(file_get_contents("php://input"), true);
$carId = $data['car_id'] ?? null;
$make = $data['make'] ?? '';
$model = $data['model'] ?? '';
$year = $data['year'] ?? '';
$price = $data['rental_price'] ?? '';
$isAvailable = $data['is_available'] ?? 0;

if (!$carId) {
    echo json_encode(["success" => false, "error" => "Car ID is required"]);
    exit;
}

$uploadedImages = [];

if (!empty($_FILES['car_images']) && is_array($_FILES['car_images']['name'])) {
    foreach ($_FILES['car_images']['tmp_name'] as $key => $tmpName) {
        $fileName = time() . '_' . basename($_FILES['car_images']['name'][$key]);
        $targetPath = $_SERVER['DOCUMENT_ROOT'] . "/uploads_img/" . $fileName;

        if (move_uploaded_file($tmpName, $targetPath)) {
            $uploadedImages[] = $fileName;
        }
    }
}

$imagesToStore = !empty($uploadedImages) ? json_encode($uploadedImages) : null;

$sql = "UPDATE cars_rental SET make=?, model=?, year=?, rental_price=?, is_available=?";
$params = [$make, $model, $year, $price, $isAvailable];

if ($imagesToStore) {
    $sql .= ", images=?";
    $params[] = $imagesToStore;
}

$sql .= " WHERE id=?";
$params[] = $carId;

$stmt = $pdo->prepare($sql);

if ($stmt->execute($params)) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => "Failed to update car data"]);
}
?>
