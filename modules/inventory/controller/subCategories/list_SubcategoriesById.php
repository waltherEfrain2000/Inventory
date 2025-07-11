<?php
require_once __DIR__ . '/../../../../config/config.php';
require_once __DIR__ . '/../../model/categories/category.php';

$pdo = require __DIR__ . '/../../../../config/config.php';
$categoryModel = new Category($pdo);

try {
    $id = $_GET['id'];
    $categories = $categoryModel->getSubCategoriesById($id);
    echo json_encode(["success" => true, "data" => $categories]);
} catch (\Throwable $th) {
    echo json_encode(["success" => false, "error" => "Error al guardar la categoría"]);
}