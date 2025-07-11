<?php
require_once __DIR__ . '/../../../../config/config.php';
require_once __DIR__ . '/../../model/inventoryEntry/inventoryEntry.php';

$pdo = require __DIR__ . '/../../../../config/config.php';
$inventoryEntry = new InventoryEntry($pdo);

try {
    
    $entries = $inventoryEntry->getHistoricIngresos();
    echo json_encode(["success" => true, "data" => $entries]);
} catch (\Throwable $th) {
    echo json_encode(["success" => false, "error" => "Error al listar los articulos"]);
}