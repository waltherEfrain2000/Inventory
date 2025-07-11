<?php
require_once __DIR__ . '/../../../../config/config.php';
require_once __DIR__ . '/../../model/inventoryOut/inventoryOut.php';

$pdo = require __DIR__ . '/../../../../config/config.php';
$inventoryOut = new InventoryOut($pdo);

try {
    
    $salidas = $inventoryOut->getinventoryHistoryOuts();
    echo json_encode(["success" => true, "data" => $salidas]);
} catch (\Throwable $th) {
    echo json_encode(["success" => false, "error" => "Error al listar los historicos de salidas"]);
}