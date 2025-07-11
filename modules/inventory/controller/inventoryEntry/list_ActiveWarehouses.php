<?php
require_once __DIR__ . '/../../../../config/config.php';
require_once __DIR__ . '/../../model/inventoryEntry/inventoryEntry.php';

$pdo = require __DIR__ . '/../../../../config/config.php';
$inventoryEntry = new InventoryEntry($pdo);

try {
    
    $bodegas = $inventoryEntry->getActiveWarehouses();
    echo json_encode(["success" => true, "data" => $bodegas]);
} catch (\Throwable $th) {
    echo json_encode(["success" => false, "error" => "Error al listar las bodegas"]);
}