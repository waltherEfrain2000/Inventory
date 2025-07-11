<?php
require_once __DIR__ . '/../../../../config/config.php';
require_once __DIR__ . '/../../model/inventoryEntry/inventoryEntry.php';

$pdo = require __DIR__ . '/../../../../config/config.php';
$inventoryEntry = new InventoryEntry($pdo);

header('Content-Type: application/json');


$jsonData = file_get_contents("php://input");
$data = json_decode($jsonData, true);


if (!$data) {
    echo json_encode(["success" => false, "error" => "No se recibieron datos o el JSON es inválido.", "rawData" => $jsonData]);
    exit;
}



$UsuarioCreador = isset($_SESSION['usuario_id']) ? intval($_SESSION['usuario_id']) : 1;

$AjusteInventario = 0;
$detalles = $data['detalles'];
$id = $data['idIngreso'];
$IdProveedor = $data['IdProveedor'];
$NumeroFactura = $data['NumeroFactura'];
$FechaFactura = $data['FechaFactura'];
$Comentarios = $data['Comentarios'];
$TotalFactura = $data['TotalFactura'];
$ImpuestoFactura = $data['ImpuestoFactura'];

if (!is_array($detalles) || count($detalles) === 0) {
    echo json_encode(["success" => false, "error" => "Debe agregar al menos un detalle.", "receivedDetails" => $detalles]);
    exit;
}


$result = $inventoryEntry->updateInventoryEntry($id,$UsuarioCreador, $AjusteInventario, $detalles,
$IdProveedor, $NumeroFactura, $FechaFactura,
    $Comentarios, $TotalFactura, $ImpuestoFactura);

if ($result) {
    echo json_encode(["success" => true, "message" => "Ingreso de inventario guardado correctamente."]);
} else {
    echo json_encode(["success" => false, "error" => "No se pudo guardar el ingreso."]);
}
