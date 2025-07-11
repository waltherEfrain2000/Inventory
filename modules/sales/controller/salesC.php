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
        case 'guardar':
            $datos = $_POST;

            if ($datos['hacer'] == 1) {
                if (!empty($datos['nremision']) && !empty($datos['destinatario'])) {
                    $response = $VentaModel->guardarVenta($datos);
                    echo json_encode($response);
                } else {
                    echo json_encode(["success" => false, "error" => "Debe incluir el número de remisión y el cliente."]);
                }
            } else {
                $camposRequeridos = [
                    'nremision',
                    'destinatario',
                    'fecha',
                    'remitente',
                    'partida',
                    'destino',
                    'traslado',
                    'fechainicio',
                    'ncertificacion',
                    'emisiones',
                    'km',
                    'transportista',
                    'conductor',
                    'placa',
                    'detalles'
                ];

                $errores = [];
                foreach ($camposRequeridos as $campo) {
                    if (empty($datos[$campo])) {
                        $errores[] = "El campo '$campo' es obligatorio.";
                    }
                }

                // Validar que los detalles no estén vacíos
                if (!empty($datos['detalles']) && is_array($datos['detalles'])) {
                    foreach ($datos['detalles'] as $index => $detalle) {
                        if (empty($detalle['idProducto']) || empty($detalle['idProductor']) || empty($detalle['cantidad'])) {
                            $errores[] = "Todos los campos de los detalles de la venta son obligatorios (detalle $index).";
                        }
                    }
                } else {
                    $errores[] = "Debe incluir al menos un detalle en la venta.";
                }

                if (!empty($errores)) {
                    echo json_encode(["success" => false, "error" => $errores]);
                } else {
                    $response = $VentaModel->guardarVenta($datos);
                    echo json_encode($response);
                }
            }


            break;

        case 'editar':
            $datos = $_POST;

            if ($datos['hacer'] == 1) {
                if (!empty($datos['nremision'])) {
                    $response = $VentaModel->editarVenta($datos);
                    echo json_encode($response);
                } else {
                    echo json_encode(["success" => false, "error" => "Todos los campos son obligatorios"]);
                }
            } else {
                $camposRequeridos = [
                    'nremision',
                    'destinatario',
                    'fecha',
                    'remitente',
                    'partida',
                    'destino',
                    'traslado',
                    'fechainicio',
                    'ncertificacion',
                    'emisiones',
                    'km',
                    'transportista',
                    'conductor',
                    'placa',
                    'detalles'
                ];

                $errores = [];
                foreach ($camposRequeridos as $campo) {
                    if (empty($datos[$campo])) {
                        $errores[] = "El campo '$campo' es obligatorio.";
                    }
                }

                // Validar que los detalles no estén vacíos
                if (!empty($datos['detalles']) && is_array($datos['detalles'])) {
                    foreach ($datos['detalles'] as $index => $detalle) {
                        if (empty($detalle['idProductor']) || empty($detalle['cantidad'])) {
                            $errores[] = "Todos los campos de los detalles de la venta son obligatorios (detalle $index).";
                        }
                    }
                } else {
                    $errores[] = "Debe incluir al menos un detalle en la venta.";
                }

                if (!empty($errores)) {
                    echo json_encode(["success" => false, "error" => $errores]);
                } else {
                    $response = $VentaModel->editarVenta($datos);
                    echo json_encode($response);
                }
            }
            break;



        case 'listar':
            $Ventas = $VentaModel->obtenerVentas();
            echo json_encode($Ventas);
            break;

        //============================================
        case 'guardarventadirecta':
            $data = $_POST;
            $allowed = array('pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png');


                if (isset($_FILES['fileboleta']) && $_FILES['fileboleta']['error'] === 0) {
                    $file = $_FILES['fileboleta'];
                    $fileName = $file['name'];
                    $fileTmpName = $file['tmp_name'];
                    $fileSize = $file['size'];
                    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                    if (in_array($fileExt, $allowed)) {
                        if ($fileSize < 1000000) { // Limitar el tamaño del archivo a 1MB
                            // Obtener el valor de "boleta" enviado desde el AJAX
                            $boletaValue = isset($data['boleta']) ? $data['boleta'] : 'default';
                            $fileNameNew = 'boleta_' . $boletaValue . '.' . $fileExt;
                            $fileDestination = './../uploads/' . $fileNameNew;
                            if (move_uploaded_file($fileTmpName, $fileDestination)) {
                                $data['fileboleta'] = $fileNameNew; // Agregar el nombre del archivo a los datos
                            } else {
                                echo json_encode(["success" => false, "error" => "Error al mover el archivo fileboleta"]);
                                exit;
                            }
                        } else {
                            echo json_encode(["success" => false, "error" => "El archivo fileboleta es muy grande"]);
                            exit;
                        }
                    } else {
                        echo json_encode(["success" => false, "error" => "El archivo fileboleta tiene un formato no permitido"]);
                        exit;
                    }
                }
            // Procesar fileboleta
            if ($data['hacer'] == 1) {
                    $response = $VentaModel->guardarventadirecta($data);
                    echo json_encode($response);
            } else {
                if ($data) {
                    $response = $VentaModel->guardarventadirecta($data);
                    if (is_array($response) && isset($response['success']) && !$response['success']) {
                        // Si el modelo retorna un error, enviarlo al cliente
                        echo json_encode($response);
                    } else {
                        echo json_encode(["success" => $response]);
                    }
                } else {
                    echo json_encode(["success" => false, "error" => "Datos inválidos"]);
                }
            }
            break;

        case 'editarventadirecta':
            $data = $_POST;
            $allowed = array('pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png');

            // Procesar fileboleta si se envía un archivo
            if (isset($_FILES['fileboleta']) && $_FILES['fileboleta']['error'] === 0) {
                $file = $_FILES['fileboleta'];
                $fileName = $file['name'];
                $fileTmpName = $file['tmp_name'];
                $fileSize = $file['size'];
                $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                if (in_array($fileExt, $allowed)) {
                    if ($fileSize < 1000000) { // Limitar el tamaño del archivo a 1MB
                        // Obtener el valor de "boleta" enviado desde el AJAX
                        $boletaValue = isset($data['boleta']) ? $data['boleta'] : 'default';
                        $fileNameNew = 'boleta_' . $boletaValue . '.' . $fileExt;
                        $fileDestination = './../uploads/' . $fileNameNew;
                        if (move_uploaded_file($fileTmpName, $fileDestination)) {
                            $data['fileboleta'] = $fileNameNew; // Agregar el nombre del archivo a los datos
                        } else {
                            echo json_encode(["success" => false, "error" => "Error al mover el archivo fileboleta"]);
                            exit;
                        }
                    } else {
                        echo json_encode(["success" => false, "error" => "El archivo fileboleta es muy grande"]);
                        exit;
                    }
                } else {
                    echo json_encode(["success" => false, "error" => "El archivo fileboleta tiene un formato no permitido"]);
                    exit;
                }
            }

            // Validar que los datos sean válidos
            if ($data) {
                $response = $VentaModel->editarventadirecta($data);
                if (is_array($response) && isset($response['success']) && !$response['success']) {
                    // Si el modelo retorna un error, enviarlo al cliente
                    echo json_encode($response);
                } else {
                    echo json_encode(["success" => $response]);
                }
            } else {
                echo json_encode(["success" => false, "error" => "Datos inválidos"]);
            }
            break;

        case 'cargarVentasTerceros':
            $Ventas = $VentaModel->cargarVentasTerceros();
            echo json_encode($Ventas);
            break;
        case 'listarventadirecta':
            $id = $_POST['id'] ?? null;
            $Ventas = $VentaModel->listarventadirecta($id);

            if ($Ventas) {
                echo json_encode([
                    'success' => true,
                    'data' => $Ventas
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'No se encontraron datos'
                ]);
            }
            break;
        case 'listarpagosventa':
            $id = $_POST['id'] ?? null;
            $Ventas = $VentaModel->listarpagosventa($id);

            if ($Ventas) {
                echo json_encode([
                    'success' => true,
                    'data' => $Ventas
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'No se encontraron datos'
                ]);
            }
            break;


        case 'recibirPagoTerceros':
            $data = $_POST;
            if ($data) {
                $response = $VentaModel->recibirPagoTerceros($data);
                echo json_encode(["success" => $response]);
            } else {
                echo json_encode(["success" => false, "error" => "ID inválido"]);
            }
            break;

        case 'saldarPagoTerceros':
            $data = $_POST;
            if ($data) {
                $response = $VentaModel->saldarPagoTerceros($data);
                echo json_encode(["success" => $response]);
            } else {
                echo json_encode(["success" => false, "error" => "ID inválido"]);
            }
            break;

        case 'cancelarVentaTerceros':
            $data = $_POST;
            if ($data) {
                $response = $VentaModel->cancelarVentaTerceros($data);
                echo json_encode(["success" => $response]);
            } else {
                echo json_encode(["success" => false, "error" => "ID inválido"]);
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
