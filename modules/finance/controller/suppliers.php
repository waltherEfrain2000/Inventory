<?php
require_once '../../../config/config.php';
require_once '../model/supplier.php';

$pdo = require '../../../config/config.php';
$ProveedorModel = new Proveedor($pdo);

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
            $correo = $_POST['correo'] ?? '';
            $acopio = $_POST['acopio'] ?? '';
            $precio = $_POST['precio'] ?? '';
            $creado_por = $_POST['creado_por'] ?? 1;

            if (!empty($nombre) && !empty($acopio) && !empty($precio)) {
                $response = $ProveedorModel->guardarProveedor($nombre, $rtn, $telefono, $direccion, $correo, $acopio, $precio, $creado_por);
                echo json_encode(["success" => $response]);
            } else {
                echo json_encode(["success" => false, "error" => "Todos los campos son obligatorios"]);
            }
            break;

        case 'editar':
            $id = $_POST['proveedorId'] ?? '';
            $nombre = $_POST['nombre'] ?? '';
            $rtn = $_POST['rtn'] ?? '';
            $telefono = $_POST['telefono'] ?? '';
            $direccion = $_POST['direccion'] ?? '';
            $correo = $_POST['correo'] ?? '';
            $acopio = $_POST['acopio'] ?? '';
            $precio = $_POST['precio'] ?? '';
            $modificado_por = $_POST['modificado_por'] ?? 2;
            $tipoCambio = $_POST['valorSeleccionado'] ?? 1;

            if (!empty($id) && !empty($nombre) && !empty($acopio) && !empty($precio)) {
                $response = $ProveedorModel->editarProveedor($id, $nombre, $rtn, $telefono, $direccion, $correo, $acopio, $precio, $modificado_por, $tipoCambio);
                echo json_encode(["success" => $response]);
            } else {
                echo json_encode(["success" => false, "error" => "Todos los campos son obligatorios"]);
            }
            break;

        case 'eliminar':
            $id = $_POST['proveedorId'] ?? null;
            if ($id) {
                $response = $ProveedorModel->eliminarProveedor($id);
                echo json_encode(["success" => $response]);
            } else {
                echo json_encode(["success" => false, "error" => "ID inválido"]);
            }
            break;

        case 'historial':
            $id = $_POST['proveedorId'] ?? null;
            if ($id) {
                $historial = $ProveedorModel->obtenerHistorialPrecios($id);
                echo json_encode($historial);
            } else {
                echo json_encode(["success" => false, "error" => "ID inválido"]);
            }
            break;

        case 'listar':
            $Proveedores = $ProveedorModel->obtenerProveedores();
            echo json_encode($Proveedores);
            break;

        case 'cambiarPrecio':
            $datos = $_POST;
            if (!empty($datos['precio'])) {
                $response = $ProveedorModel->actualizarPreciosProveedores($datos);
                echo json_encode(["success" => $response]);
            } else {
                echo json_encode(["success" => false, "error" => "Todos los campos son obligatorios"]);
            }
            break;

        default:
            echo json_encode(["success" => false, "error" => "Acción no válida"]);
            break;
    }
} catch (Exception $e) {
    echo json_encode(["success" => false, "error" => "Error interno: " . $e->getMessage()]);
}
exit;
