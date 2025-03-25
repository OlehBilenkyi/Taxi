<?php
require_once '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_FILES['carImages'])) {
    $uploadDir = '../uploads_img/';
    $uploadedFiles = [];

    foreach ($_FILES['carImages']['tmp_name'] as $key => $tmp_name) {
        $fileName = basename($_FILES['carImages']['name'][$key]);
        $targetFilePath = $uploadDir . $fileName;

        if (move_uploaded_file($tmp_name, $targetFilePath)) {
            $uploadedFiles[] = $fileName;
        }
    }

    echo json_encode(['success' => true, 'files' => $uploadedFiles]);
} else {
    echo json_encode(['success' => false, 'message' => 'Файлы не загружены']);
}
?>
