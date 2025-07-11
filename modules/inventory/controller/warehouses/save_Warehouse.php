<?php
require_once __DIR__ . '/../../../../config/config.php';
require_once __DIR__ . '/../../model/warehouses/warehouse.php';

$pdo = require __DIR__ . '/../../../../config/config.php';
$warehouseModel = new Warehouse($pdo);



try {
    session_start(); // Iniciar la sesión para acceder a los datos del usuario



    $NombreBodega = $_POST['NombreBodega'];
    $DescripcionBodega = $_POST['DescripcionBodega'];
    $Estado = $_POST['Estado'];

      if (!isset($NombreBodega, $DescripcionBodega)) {
        echo json_encode(["success" => false, "error" => "Datos incompletos"]);
        exit;
    }
    $usuario_id = isset($_SESSION['usuario_id']) ? intval($_SESSION['usuario_id']) : 1;

    $resultado = $warehouseModel->saveWarehouse($NombreBodega, $DescripcionBodega,$Estado, $usuario_id);

    if ($resultado) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => "Error al guardar la bodega"]);
    }
} catch (\Throwable $th) {
    echo json_encode(["success" => false, "error" => "Error al guardar la bodega"]);
}