<?php
require_once __DIR__ . '/../../../../config/config.php';
require_once __DIR__ . '/../../model/warehouses/warehouse.php';

$pdo = require __DIR__ . '/../../../../config/config.php';
$warehouseModel = new Warehouse($pdo);


try {
    $id = $_POST['id'];
    $warehouse = $warehouseModel->deleteWarehouse($id);
    echo json_encode(["success" => true, "data" => $warehouse]);
} catch (\Throwable $th) {
    echo json_encode(["success" => false, "error" => "Error al guardar la categoría"]);
}