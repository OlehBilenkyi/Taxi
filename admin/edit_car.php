<?php
session_start();
require_once '../config/config.php'; // Обновленный путь к config.php

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.html');
    exit;
}

$id = $_GET['id'];
$stmt = $pdo->prepare('SELECT * FROM cars_rental WHERE id = :id');
$stmt->execute(['id' => $id]);
$car = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $version = $_POST['version'];
    $rental_price = $_POST['rental_price'];
    $deposit_price = $_POST['deposit_price'];

    $stmt = $pdo->prepare('UPDATE cars_rental SET name = :name, description = :description, version = :version, rental_price = :rental_price, deposit_price = :deposit_price WHERE id = :id');
    $stmt->execute([
        'name' => $name,
        'description' => $description,
        'version' => $version,
        'rental_price' => $rental_price,
        'deposit_price' => $deposit_price,
        'id' => $id
    ]);

    header('Location: dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Car</title>
            <link rel="stylesheet" type="text/css" href="../assets/css/admin_styles.css">
</head>
<body>
    <h2>Edit Car</h2>
    <form action="edit_car.php?id=<?= $car['id'] ?>" method="POST">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?= $car['name'] ?>" required>
        <br>
        <label for="description">Description:</label>
        <textarea id="description" name="description" required><?= $car['description'] ?></textarea>
        <br>
        <label for="version">Version:</label>
        <input type="text" id="version" name="version" value="<?= $car['version'] ?>" required>
        <br>
        <label for="rental_price">Rental Price:</label>
        <input type="text" id="rental_price" name="rental_price" value="<?= $car['rental_price'] ?>" required>
        <br>
        <label for="deposit_price">Deposit Price:</label>
        <input type="text" id="deposit_price" name="deposit_price" value="<?= $car['deposit_price'] ?>" required>
        <br>
        <button type="submit">Save</button>
    </form>
</body>
</html>
