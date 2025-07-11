<?php
require_once '../../../config/config.php';
require_once '../model/salesModel.php';

$pdo = require '../../../config/config.php';
$VentaModel = new Venta($pdo);

try {
    // Verifica si se pasó una acción válida
    if (!isset($_GET['action']) && !isset($_POST['action'])) {
        echo json_encode(["success" => false, "error" => "Acción no especificada"]);
        exit;
    }

    $action = $_GET['action'] ?? $_POST['action'];


    switch ($action) {
        case 'listarclientes':
            $Ventas = $VentaModel->listarclientes();
            echo json_encode($Ventas);
            break;

        case 'listarproductores':
            $Ventas = $VentaModel->listarproductores();
            echo json_encode($Ventas);
            break;

        case 'listarmotivostraslado':
            $Ventas = $VentaModel->listarmotivostraslado();
            echo json_encode($Ventas);
            break;

        case 'listartipoCertificacion':
            $Ventas = $VentaModel->listartipoCertificacion();
            echo json_encode($Ventas);
            break;

        case 'listarcertificacion':
            $Ventas = $VentaModel->listarcertificacion();
            echo json_encode($Ventas);
            break;
        
        case 'listartransportistas':
            $Ventas = $VentaModel->listartransportistas();
            echo json_encode($Ventas);
            break;

        case 'listarconductores':
            $data = $_POST;
            $Ventas = $VentaModel->listarconductores($data);
            echo json_encode($Ventas);
            break;

        case 'listarvehiculos':
            $Ventas = $VentaModel->listarvehiculos();
            echo json_encode($Ventas);
            break;


        //=======================================================================================================

        case 'listarProveedores':
            $Ventas = $VentaModel->listarProveedores();
            echo json_encode($Ventas);
            break;
        
        //=======================================================================================================
        case 'listarEditar':
            $data = $_POST['idventasedit'];
            $Ventas = $VentaModel->listarEditar($data);
            echo json_encode($Ventas);
            break;

        default:
            echo json_encode(["success" => false, "error" => "Acción no válida"]);
            break;
    }
} catch (Exception $e) {
    echo json_encode(["success" => false, "error" => "Error interno: " . $e->getMessage()]);
}
exit;
