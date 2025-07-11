<?php
require_once __DIR__ . '/../../../../config/config.php';
require_once __DIR__ . '/../../model/account_payable.php';

$pdo = require __DIR__ . '/../../../../config/config.php';
$cxcModel = new CuentasPorPagar($pdo);

header('Content-Type: application/json');

// Obtener el ID del proveedor desde GET
$idProveedor = $_GET['idProveedor'] ?? null;

if (!$idProveedor) {
    echo json_encode(["success" => false, "error" => "ID del proveedor no recibido"]);
    exit;
}

try {
    $clientes = $cxcModel->cargarCuentasPorPagarID($idProveedor);
    echo json_encode(["success" => true, "data" => $clientes]); 
} catch (Exception $e) {
    echo json_encode(["success" => false, "error" => "Error interno: " . $e->getMessage()]);
}
exit;
