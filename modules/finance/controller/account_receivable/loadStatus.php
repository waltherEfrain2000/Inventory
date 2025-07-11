<?php
require_once __DIR__ . '/../../../../config/config.php';
require_once __DIR__ . '/../../model/account_receivable.php';

$pdo = require __DIR__ . '/../../../../config/config.php';
$cxcModel = new CuentasPorCobrar($pdo);

header('Content-Type: application/json');

try {
    $clientes = $cxcModel->cargarEstado();
    echo json_encode(["success" => true, "data" => $clientes]); 
} catch (Exception $e) {
    echo json_encode(["success" => false, "error" => "Error interno: " . $e->getMessage()]);
}
exit;
