<?php

// Importaciones (BD y modelo)
require_once __DIR__ . "/../../../../config/config.php";
require_once __DIR__ . "/../../model/account_payable.php";

$pdo = require __DIR__ . '/../../../../config/config.php';
$cxpModel = new CuentasPorPagar($pdo);

header('Content-Type: application/json');

// Obtener los datos JSON de la solicitud
$inputJSON = file_get_contents("php://input");
$datos = json_decode($inputJSON);

// Verificar si el ID llegó correctamente
if (!isset($datos->id)) {
    echo json_encode(["success" => false, "error" => "ID del pago no recibido"]);
    exit;
}

try {
    $resultado = $cxpModel->eliminarPago($datos->id); // Ahora enviamos solo el ID
    echo json_encode($resultado);
} catch (Exception $e) {
    echo json_encode(["success" => false, "error" => "Error interno: " . $e->getMessage()]);
}
exit;
