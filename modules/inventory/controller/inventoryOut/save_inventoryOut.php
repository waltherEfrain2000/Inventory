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

    // Puedes ajustar estos valores si tienes un sistema de sesión con usuarios logueados
  $UsuarioCreador = isset($_SESSION['usuario_id']) ? intval($_SESSION['usuario_id']) : 1;


    $TipoSalida = $input['TipoSalida'];
    $IdCliente = $input['idCliente'];

    $Comentarios = $input['Comentarios'];
    $TotalSalida = $input['TotalSalida'];
    $ImpuestoSalida = $input['ImpuestoSalida'];
    $detalles = $input['detalles'];


    //Validar los campos que son para al credito

    $EsCredito = $input['EsCredito'] ?? false;

    $FechaVencimiento = $input['FechaVencimiento'] ?? null;

    $result = $inventoryOut->saveInventoryOut(
        $UsuarioCreador, $TipoSalida,  $IdCliente, $Comentarios,
        $TotalSalida, $ImpuestoSalida, $detalles, $EsCredito, $FechaVencimiento
    );

    if ($result) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => "No se pudo guardar la salida."]);
    }
} catch (Exception $e) {
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}
