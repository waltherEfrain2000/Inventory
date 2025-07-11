<?php
require_once '../../../config/config.php';
require_once '../model/conductores.php';

$pdo = require '../../../config/config.php';
$ConductorModel = new Conductor($pdo);

try {
    // Verifica si se pasó una acción válida
    if (!isset($_GET['action']) && !isset($_POST['action'])) {
        echo json_encode(["success" => false, "error" => "Acción no especificada"]);
        exit;
    }

    $action = $_GET['action'] ?? $_POST['action'];

    switch ($action) {
        case 'guardar':
            $nombre = $_POST['nombre'] ?? '';
            $rtn = $_POST['rtn'] ?? '';
            $telefono = $_POST['telefono'] ?? '';
            $direccion = $_POST['direccion'] ?? '';
            $acopio = $_POST['acopio'] ?? '';
            $creado_por = $_POST['creado_por'] ?? 1;

            if (!empty($nombre)  && !empty($acopio)) {
                $response = $ConductorModel->guardarConductor($nombre, $rtn, $telefono, $direccion, $acopio, $creado_por);
                echo json_encode(["success" => $response]);
            } else {
                echo json_encode(["success" => false, "error" => "Todos los campos son obligatorios"]);
            }
            break;

        case 'editar':
            $id = $_POST['conductorId'] ?? '';
            $nombre = $_POST['nombre'] ?? '';
            $rtn = $_POST['rtn'] ?? '';
            $telefono = $_POST['telefono'] ?? '';
            $direccion = $_POST['direccion'] ?? '';
            $acopio = $_POST['acopio'] ?? '';
            $modificado_por = $_POST['modificado_por'] ?? 2;

            if (!empty($id) && !empty($nombre) && !empty($acopio)) {
                $response = $ConductorModel->editarConductor($id, $nombre, $rtn, $telefono, $direccion, $acopio, $modificado_por);
                echo json_encode(["success" => $response]);
            } else {
                echo json_encode(["success" => false, "error" => "Todos los campos son obligatorios"]);
            }
            break;

        case 'eliminar':
            $id = $_POST['conductorId'] ?? null;
            if ($id) {
                $response = $ConductorModel->eliminarConductor($id);
                echo json_encode(["success" => $response]);
            } else {
                echo json_encode(["success" => false, "error" => "ID inválido"]);
            }
            break;

        case 'listar':
            $Conductor = $ConductorModel->obtenerConductor();
            echo json_encode($Conductor);
            break;

        default:
            echo json_encode(["success" => false, "error" => "Acción no válida"]);
            break;
    }
} catch (Exception $e) {
    echo json_encode(["success" => false, "error" => "Error interno: " . $e->getMessage()]);
}
exit;
