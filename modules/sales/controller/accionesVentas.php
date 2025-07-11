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
        case 'guardarnotarecepcion':
            $datos = $_POST;

            if (!empty($datos['nrecepcion'])) {
                $allowed = array('pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png');

                // Procesar file1 (nrecepcion)
                if (isset($_FILES['file1']) && $_FILES['file1']['error'] === 0) {
                    $file1 = $_FILES['file1'];
                    $fileName1 = $file1['name'];
                    $fileTmpName1 = $file1['tmp_name'];
                    $fileSize1 = $file1['size'];
                    $fileExt1 = strtolower(pathinfo($fileName1, PATHINFO_EXTENSION));

                    if (in_array($fileExt1, $allowed)) {
                        if ($fileSize1 < 1000000) {
                            $fileNameNew1 = $datos['idVenta'] . '_' . $datos['nrecepcion'] . '.' . $fileExt1;
                            $fileDestination1 = './../uploads/' . $fileNameNew1;
                            if (move_uploaded_file($fileTmpName1, $fileDestination1)) {
                                $datos['file1'] = $fileNameNew1;
                            } else {
                                echo json_encode(["success" => false, "error" => "Error al mover el archivo file1"]);
                                exit;
                            }
                        } else {
                            echo json_encode(["success" => false, "error" => "El archivo file1 es muy grande"]);
                            exit;
                        }
                    } else {
                        echo json_encode(["success" => false, "error" => "El archivo file1 tiene un formato no permitido"]);
                        exit;
                    }
                }

                // Procesar file2 (nrecepcionraqui)
                if (isset($_FILES['file2']) && $_FILES['file2']['error'] === 0) {
                    $file2 = $_FILES['file2'];
                    $fileName2 = $file2['name'];
                    $fileTmpName2 = $file2['tmp_name'];
                    $fileSize2 = $file2['size'];
                    $fileExt2 = strtolower(pathinfo($fileName2, PATHINFO_EXTENSION));

                    if (in_array($fileExt2, $allowed)) {
                        if ($fileSize2 < 1000000) {
                            $fileNameNew2 = $datos['idVenta'] . '_' . $datos['nrecepcionraqui'] . '.' . $fileExt2;
                            $fileDestination2 = './../uploads/' . $fileNameNew2;
                            if (move_uploaded_file($fileTmpName2, $fileDestination2)) {
                                $datos['file2'] = $fileNameNew2;
                            } else {
                                echo json_encode(["success" => false, "error" => "Error al mover el archivo file2"]);
                                exit;
                            }
                        } else {
                            echo json_encode(["success" => false, "error" => "El archivo file2 es muy grande"]);
                            exit;
                        }
                    } else {
                        echo json_encode(["success" => false, "error" => "El archivo file2 tiene un formato no permitido"]);
                        exit;
                    }
                }

                // Aquí puedes continuar con el procesamiento de $datos
                $response = $VentaModel->finalizarventa($datos);

                if ($response['success']) {
                    echo json_encode(["success" => true]);
                } else {
                    echo json_encode(["success" => false, "error" => $response['error']]);
                }
            } else {
                echo json_encode(["success" => false, "error" => "Todos los campos son obligatorios"]);
            }
            break;

        case 'listarSaldo':
            $datos = $_POST;
            $response = $VentaModel->listarSaldo($datos);
            echo json_encode(["success" => $response]);
            break;

        case 'recibirpago':
            $datos = $_POST;
            if (!empty($datos['idVenta']) && !empty($datos['monto'])) {
                $response = $VentaModel->recibirpago($datos);
                echo json_encode($response);
            } else {
                echo json_encode(["success" => false, "error" => "Todos los campos son obligatorios"]);
            }
            break;

        case 'cancelar':
            $datos = $_POST;
            if (!empty($datos['idVenta']) || !empty($datos['placa'])) {
                $response = $VentaModel->cancelarventa($datos);
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
