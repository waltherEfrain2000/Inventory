<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../models/mdl_ingresoFruta.php';

// Conexión a la base de datos
$pdo = require __DIR__ . '/../../../config/config.php';
$acopioModel = new mdlcomprasAcopio($pdo);

// Verificar si se enviaron datos
if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    echo json_encode(["success" => false, "data" => "ID inválido"]);
    exit;
}

$id = $_POST['id']; // Obtener el id directamente

try {
    // Eliminar el pesaje con el ID proporcionado
    $descarga = $acopioModel->eliminarProductor($id); // Pasando solo el ID como argumento
    echo json_encode([
        "success" => true, 
        "data" => [
            "mensaje" => "Productor eliminado correctamente", 
            "id" => $id
        ]
    ]);
} catch (\Throwable $th) {
    echo json_encode([
        "success" => false,
        "data" => "Error al procesar la solicitud: " . $th->getMessage(),
    ]);
}
