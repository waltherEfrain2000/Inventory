<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../models/mdl_ingresoFruta.php';

$pdo = require __DIR__ . '/../../../config/config.php';
$cargarproductorModel = new mdlcomprasAcopio($pdo);

try {
    $productores = $cargarproductorModel->obtenerProductor();
    echo json_encode(["success" => true, "data" => $productores]);
} catch (\Throwable $th) {
    echo json_encode(["success" => false, "data" => "Error al obtener productores"]);
}