<?php
require_once __DIR__ . '/../../../../config/config.php';
require_once __DIR__ . '/../../model/providers/providers.php';

$pdo = require __DIR__ . '/../../../../config/config.php';
$providerModel = new Provider($pdo);

try {
    
    $provider = $providerModel->getProviders();
    echo json_encode(["success" => true, "data" => $provider]);
} catch (\Throwable $th) {
    echo json_encode(["success" => false, "error" => "Error al guardar la categoría"]);
}