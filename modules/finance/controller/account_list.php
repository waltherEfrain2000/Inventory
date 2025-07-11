<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../model/account.php';

$pdo = require __DIR__ . '/../../../config/config.php';
$cuentaModel = new CuentaContable($pdo);

header('Content-Type: application/json');

try {
    // Verifica si se pasó una acción válida
    if (!isset($_GET['action']) && !isset($_POST['action'])) {
        echo json_encode(["success" => false, "error" => "Acción no especificada"]);
        exit;
    }

    $action = $_GET['action'] ?? $_POST['action'];

    switch ($action) {
        case 'listar':
            // Listar todo el catálogo contable
            $cuentas = $cuentaModel->obtenerCuentas();
            echo json_encode(["success" => true, "data" => $cuentas]);
            break;

        case 'listarPadres':
            // Validar que el nivel fue proporcionado y es numérico
            if (!isset($_GET['nivel']) || !is_numeric($_GET['nivel'])) {
                echo json_encode(["success" => false, "error" => "Nivel requerido y debe ser un número"]);
                exit;
            }

            $nivel = intval($_GET['nivel']);

            // Solo niveles mayores a 1 pueden tener cuentas padre
            if ($nivel > 1) {
                $cuentasPadre = $cuentaModel->obtenerCuentasPadre($nivel);
                echo json_encode(["success" => true, "data" => $cuentasPadre]);
            } else {
                echo json_encode(["success" => true, "data" => []]); // Nivel 1 no tiene cuentas padre
            }
            break;

        case 'obtener':
            // Obtener una cuenta contable por ID
            if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
                echo json_encode(["success" => false, "error" => "ID requerido y debe ser un número"]);
                exit;
            }

            $id = intval($_GET['id']);
            $cuenta = $cuentaModel->obtenerCuentaPorId($id);

            if ($cuenta) {
                echo json_encode(["success" => true, "data" => $cuenta]);
            } else {
                echo json_encode(["success" => false, "error" => "Cuenta no encontrada"]);
            }
            break;

        case 'guardar':
            // Guardar una nueva cuenta contable
            if (!isset($_POST['codigo'], $_POST['nombre'], $_POST['tipo_id'], $_POST['nivel'])) {
                echo json_encode(["success" => false, "error" => "Datos incompletos"]);
                exit;
            }

            $codigo = $_POST['codigo'];
            $nombre = $_POST['nombre'];
            $tipo_id = intval($_POST['tipo_id']);
            $nivel = intval($_POST['nivel']);
            $padre_id = isset($_POST['padre_id']) && $_POST['padre_id'] !== "" ? intval($_POST['padre_id']) : null;

            $resultado = $cuentaModel->guardarCuenta($codigo, $nombre, $tipo_id, $nivel, $padre_id);

            if ($resultado) {
                echo json_encode(["success" => true]);
            } else {
                echo json_encode(["success" => false, "error" => "Error al guardar la cuenta"]);
            }
            break;
        case 'actualizar':
            if (!isset($_POST['id'], $_POST['codigo'], $_POST['nombre'], $_POST['tipo_id'], $_POST['nivel'])) {
                echo json_encode(["success" => false, "error" => "Datos incompletos"]);
                exit;
            }

            $id = intval($_POST['id']);
            $codigo = $_POST['codigo'];
            $nombre = $_POST['nombre'];
            $tipo_id = intval($_POST['tipo_id']);
            $nivel = intval($_POST['nivel']);
            $padre_id = isset($_POST['padre_id']) && $_POST['padre_id'] !== "" ? intval($_POST['padre_id']) : null;

            $resultado = $cuentaModel->actualizarCuenta($id, $codigo, $nombre, $tipo_id, $nivel, $padre_id);

            echo json_encode($resultado);
            break;

        case 'eliminar':
            // Eliminar una cuenta contable
            if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
                echo json_encode(["success" => false, "error" => "ID requerido y debe ser un número"]);
                exit;
            }

            $id = intval($_POST['id']);
            $resultado = $cuentaModel->eliminarCuenta($id);

            if ($resultado) {
                echo json_encode(["success" => true]);
            } else {
                echo json_encode(["success" => false, "error" => "Error al eliminar la cuenta"]);
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
