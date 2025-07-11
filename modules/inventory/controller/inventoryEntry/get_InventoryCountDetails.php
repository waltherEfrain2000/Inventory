<?php
require_once __DIR__ . '/../../../../config/config.php';
require_once __DIR__ . '/../../model/inventoryEntry/inventoryEntry.php';

$pdo = require __DIR__ . '/../../../../config/config.php';
$inventoryEntry = new InventoryEntry($pdo);

try {
 $id = $_GET['id'];
 
    $revisions = $inventoryEntry->getInventoryCountsDetail($id);
    echo json_encode(["success" => true, "data" => $revisions]);
} catch (\Throwable $th) {
    echo json_encode(["success" => false, "error" => "Error al listar los registros "]);
}