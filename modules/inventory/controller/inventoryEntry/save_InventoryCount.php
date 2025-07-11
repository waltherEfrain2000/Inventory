<?php
require_once __DIR__ . '/../../../../config/config.php';
require_once __DIR__ . '/../../model/inventoryEntry/inventoryEntry.php';

$pdo = require __DIR__ . '/../../../../config/config.php';
$inventoryEntry = new InventoryEntry($pdo);

header('Content-Type: application/json');

// Leer el JSON recibido
$jsonData = file_get_contents("php://input");
$data = json_decode($jsonData, true);

// Validar si hay datos
if (!$data) {
    echo json_encode([
        "success" => false,
        "error" => "No se recibieron datos o el JSON es inválido.",
        "rawData" => $jsonData
    ]);
    exit;
}

$UsuarioCreador = isset($_SESSION['usuario_id']) ? intval($_SESSION['usuario_id']) : 1;


// Validaciones básicas
$datosRevision = $data['detalles'];  // Este arreglo debe contener idArticulo, idBodega, cantidadSistema, cantidadFisica
$comentarios = isset($data['Comentarios']) ? $data['Comentarios'] : '';

if (!is_array($datosRevision) || count($datosRevision) === 0) {
    echo json_encode(["success" => false, "error" => "Debe agregar al menos un detalle."]);
    exit;
}

// Guardar la revisión
$result = $inventoryEntry->saveInventoryRevision($UsuarioCreador, $datosRevision, $comentarios);

if ($result) {
    echo json_encode(["success" => true, "message" => "Toma de inventario guardada correctamente."]);
} else {
    echo json_encode(["success" => false, "error" => "No se pudo guardar la toma de inventario."]);
}
