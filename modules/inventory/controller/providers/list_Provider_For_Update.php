<?php
require_once __DIR__ . '/../../../../config/config.php';
require_once __DIR__ . '/../../model/providers/providers.php';

$pdo = require __DIR__ . '/../../../../config/config.php';
$productModel = new Provider($pdo);

try {
    $id = $_GET['id'];
    $products = $productModel->getProviderById($id);
    echo json_encode(["success" => true, "data" => $products]);
} catch (\Throwable $th) {
    echo json_encode(["success" => false, "error" => "Error al guardar la categoría"]);
}