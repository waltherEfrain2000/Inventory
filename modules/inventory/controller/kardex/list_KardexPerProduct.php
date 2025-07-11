<?php
require_once __DIR__ . '/../../../../config/config.php';
require_once __DIR__ . '/../../model/kardex/kardex.php';

$pdo = require __DIR__ . '/../../../../config/config.php';
$KardexModel = new Kardex($pdo);

try {
    $id = $_GET['productoId'] ?? null; 
    $idBodega = isset($_GET['bodegaId']) ? $_GET['bodegaId'] : null;
    if (!$id) {
        echo json_encode(["success" => false, "error" => "ID de producto no proporcionado."]);
        exit;
    }

$kardex = $KardexModel->getKardex($id, $idBodega);
    echo json_encode($kardex); 
} catch (\Throwable $th) {
    echo json_encode(["success" => false, "error" => "Error al extraer la información"]);
}
