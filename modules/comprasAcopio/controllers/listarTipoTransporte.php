<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../models/mdl_ingresoFruta.php';

$pdo = require __DIR__ . '/../../../config/config.php';

$id = isset($_POST['id']) ? $_POST['id'] : null;
$cargarproductorModel = new mdlcomprasAcopio($pdo);

try {
    $transportes = $cargarproductorModel->obtenerTiposTransportes($id);
    echo json_encode(["success" => true, "data" => $transportes]);
} catch (\Throwable $th) {
    echo json_encode(["success" => false, "data" => "Error al obtener productores"]);
}
