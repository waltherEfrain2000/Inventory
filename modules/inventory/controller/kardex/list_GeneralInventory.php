<?php
require_once __DIR__ . '/../../../../config/config.php';
require_once __DIR__ . '/../../model/kardex/kardex.php';

$pdo = require __DIR__ . '/../../../../config/config.php';
$KardexModel = new Kardex($pdo);

try {

$kardex = $KardexModel->getGeneralInventory();
    echo json_encode(["success" => true, "data" => $kardex]);
} catch (\Throwable $th) {
    echo json_encode(["success" => false, "error" => "Error al extraer la información"]);
}
