<?php
require_once __DIR__ . '/../../../../config/config.php';
require_once __DIR__ . '/../../model/inventoryOut/inventoryOut.php';

header('Content-Type: application/json');

$pdo = require __DIR__ . '/../../../../config/config.php';
$inventoryOut = new InventoryOut($pdo);

try {
    // Obtener el cuerpo de la solicitud
    $jsonData = file_get_contents("php://input");
    $input = json_decode($jsonData, true);
    

    if (!$input) {
        throw new Exception("Datos no válidos.");
    }
    $UsuarioCreador = isset($_SESSION['usuario_id']) ? intval($_SESSION['usuario_id']) : 1;

    $id = $input['Id'];
    $TipoSalida = $input['TipoSalida'];
    $IdCliente = $input['idCliente'];

    $Comentarios = $input['Comentarios'];
    $TotalSalida = $input['TotalSalida'];
    $ImpuestoSalida = $input['ImpuestoSalida'];
    $detalles = $input['detalles'];

    $result = $inventoryOut->updateInventoryOut($id,
        $UsuarioCreador, $TipoSalida,  $IdCliente, $Comentarios,
        $TotalSalida, $ImpuestoSalida, $detalles
    );

    if ($result) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => "No se pudo guardar la salida."]);
    }
} catch (Exception $e) {
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
