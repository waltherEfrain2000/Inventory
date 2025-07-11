<?php
require_once __DIR__ . '/../../../../config/config.php';
require_once __DIR__ . '/../../model/inventoryOut/inventoryOut.php';

$pdo = require __DIR__ . '/../../../../config/config.php';
$inventoryOut = new InventoryOut($pdo);

try {
    
    $clients = $inventoryOut->getClients();
    echo json_encode(["success" => true, "data" => $clients]);
} catch (\Throwable $th) {
    echo json_encode(["success" => false, "error" => "Error al listar los clientes"]);
}