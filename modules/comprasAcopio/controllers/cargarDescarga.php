<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../models/mdl_ingresoFruta.php';

$pdo = require __DIR__ . '/../../../config/config.php';
$modeloAcopio = new mdlcomprasAcopio($pdo);

try {
    $id = isset($_POST['id']) ? intval($_POST['id']) : null;
    $descargas = $modeloAcopio->listarDescargas($id);
    echo json_encode(["success" => true, "data" => $descargas]);
} catch (\Throwable $th) {
    echo json_encode(["success" => false, "data" => "Error al obtener descargas"]);
}