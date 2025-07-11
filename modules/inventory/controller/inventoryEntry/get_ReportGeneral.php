<?php
require_once __DIR__ . '/../../../../config/config.php';
require_once __DIR__ . '/../../model/inventoryEntry/inventoryEntry.php';

$pdo = require __DIR__ . '/../../../../config/config.php';
$inventoryEntry = new InventoryEntry($pdo);

try {

    // Llamar al método con el parámetro (puede ser null)
    $entries = $inventoryEntry->getGeneralReport();

    echo json_encode(["success" => true, "data" => $entries]);
} catch (\Throwable $th) {
    echo json_encode(["success" => false, "error" => "Error al listar el reporte general de entradas: " . $th->getMessage()]);
}
