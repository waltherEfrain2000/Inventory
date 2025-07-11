<?php

// Importaciones (BD y modelo)
require_once __DIR__ . "/../../../../config/config.php";
require_once __DIR__ . "/../../model/account_payable.php";

$pdo = require __DIR__ . '/../../../../config/config.php';
$cxpModel = new CuentasPorPagar($pdo);

header('Content-Type: application/json');

// Obtener los datos JSON de la solicitud
$inputJSON = file_get_contents("php://input");
$datosPago = json_decode($inputJSON);

// Verificar si los datos llegaron correctamente
if (!$datosPago) {
    echo json_encode(["success" => false, "error" => "Datos de pago no recibidos correctamente"]);
    exit;
}

try {
    $elRegistro = $cxpModel->actualizarPago($datosPago);
    echo json_encode($elRegistro);
} catch (Exception $e) {
    echo json_encode(["success" => false, "error" => "Error interno: " . $e->getMessage()]);
}
exit;
