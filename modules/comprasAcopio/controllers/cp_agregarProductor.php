<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../models/mdl_ingresoFruta.php';

// Conexión a la base de datos
$pdo = require __DIR__ . '/../../../config/config.php';
$acopioModel = new mdlcomprasAcopio($pdo);

// Verificar si se enviaron datos
if (!isset($_POST['losDatos'])) {
    echo json_encode(["success" => false, "data" => "No se recibieron datos"]);
    exit;
}

// Decodificar los datos enviados como JSON
$datosProductor = json_decode($_POST['losDatos'], true);

// Verificar si la decodificación fue exitosa
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(["success" => false, "data" => "Formato de datos inválido"]);
    exit;
}
try {
    // Convertir el arreglo a un objeto para pasarlo al modelo
    $descarga = $acopioModel->guardarProductor((object)$datosProductor);
    echo json_encode(["success" => true, "data" => $descarga]);
} catch (\Throwable $th) {
    echo json_encode([
        "success" => false,
        "data" => "Error al procesar la solicitud: " . $th->getMessage(),
    ]);
}
