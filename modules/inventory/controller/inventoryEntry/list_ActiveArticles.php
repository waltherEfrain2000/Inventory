<?php
require_once __DIR__ . '/../../../../config/config.php';
require_once __DIR__ . '/../../model/inventoryEntry/inventoryEntry.php';

$pdo = require __DIR__ . '/../../../../config/config.php';
$inventoryEntry = new InventoryEntry($pdo);

try {
    // Verificar si viene el parámetro idBodega
    $idBodega = isset($_GET['idBodega']) ? intval($_GET['idBodega']) : null;

    // Llamar al método con el parámetro (puede ser null)
    $articles = $inventoryEntry->getActiveArticles($idBodega);

    echo json_encode(["success" => true, "data" => $articles]);
} catch (\Throwable $th) {
    echo json_encode(["success" => false, "error" => "Error al listar los artículos"]);
}
