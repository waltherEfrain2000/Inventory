<?php
require_once __DIR__ . '/../../../../config/config.php';
require_once __DIR__ . '/../../model/products/product.php';

$pdo = require __DIR__ . '/../../../../config/config.php';
$productModel = new Product($pdo);

try {
    $id = $_POST['id'];
    $products = $productModel->deleteProduct($id);
    echo json_encode(["success" => true, "data" => $products]);
} catch (\Throwable $th) {
    echo json_encode(["success" => false, "error" => "Error al guardar la categoría"]);
}