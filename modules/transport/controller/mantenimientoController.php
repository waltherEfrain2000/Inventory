<?php

// header para trabajar con json
header('Content-Type: application/json');

// Se importa la conexión y el modelo
require_once '../../../config/config.php'; 
require_once '../model/mantenimiento.php'; 

$model = new Mantenimiento($pdo); 

// obtiene el método enviado por el cliente
switch ($_SERVER['REQUEST_METHOD']) {

    case 'GET':
        $action = isset($_GET['action']) ? $_GET['action'] : '';

        switch ($action) {
            case 'getMantenimientos':
                // OBTENER LOS MANTENIMIENTOS
                try {
                    $mantenimientos = $model->obtenerMantenimientosTodos();
                    http_response_code(200);
                    echo json_encode($mantenimientos);
                } catch (PDOException $e) {
                    http_response_code(500);
                    echo json_encode(["success" => false, "message" => "Error al obtener los mantenimientos"]);
                }
                
                break;

            case 'getTodasLasOT':
                // OBTENER LAS OT
                try {
                    $mantenimientos = $model->obtenerOTTodas();
                    http_response_code(200);
                    echo json_encode($mantenimientos);
                } catch (PDOException $e) {
                    http_response_code(500);
                    echo json_encode(["success" => false, "message" => "Error al obtener los mantenimientos"]);
                }
                
                break;

            case 'getTiposMantenimiento':
                 // LISTAR TIPOS DE MANTENIMIENTO
                try {
                    $tiposMantenimiento = $model->obtenerTiposMantenimiento();
                    http_response_code(200);
                    echo json_encode($tiposMantenimiento);
                } catch (PDOException $e) {
                    http_response_code(500);
                    echo json_encode(["success" => false, "message" => "Error al obtener los tipos de mantenimiento"]);
                }

                break;

            case 'getTiposServicio':
                // LISTAR LOS TIPOS DE SERVICIO       
                try {
                    $tiposServicio = $model->obtenerTiposServicio();
                    http_response_code(200);
                    echo json_encode($tiposServicio);
                } catch (PDOException $e) {
                    http_response_code(500);
                    echo json_encode(["success" => false, "message" => "Error al obtener los tipos de servicio"]);
                }
               
                break;

            case 'getTalleres':
                // OBTENER LOS TALLERES
                try {
                    $talleres = $model->obtenerTalleres();
                    http_response_code(200);
                    echo json_encode($talleres);
                } catch (PDOException $e) {
                    http_response_code(500);
                    echo json_encode(["success" => false, "message" => "Error al obtener los talleres"]);
                }
                
                break;

            case 'getCatalogo':
                // OBTENER EL CATALOGO
                $id_tipo_mantenimiento = $_GET['id_tipo_mantenimiento'] ?? null;
                try {
                    $catalogo = $model->obtenerCatalogoPorTipo($id_tipo_mantenimiento);
                    http_response_code(200);
                    echo json_encode($catalogo);
                } catch (PDOException $e) {
                    http_response_code(500);
                    echo json_encode(["success" => false, "message" => "Error al obtener el catálogo"]);
                }
                
                break;

            case 'getArticulos':
                // OBTENER LOS ARTICULOS
                try {
                    $articulos = $model->obtenerArticulos();
                    http_response_code(200);
                    echo json_encode($articulos);
                } catch (PDOException $e) {
                    http_response_code(500);
                    echo json_encode(["success" => false, "message" => "Error al obtener el catálogo"]);
                }
                
                break;

            case 'getBodegas':
                // OBTENER LAS BODEGAS
                try {
                    $bodegas = $model->obtenerBodegas();
                    http_response_code(200);
                    echo json_encode($bodegas);
                } catch (PDOException $e) {
                    http_response_code(500);
                    echo json_encode(["success" => false, "message" => "Error al obtener las bodegas"]);
                }
                
                break;

            case 'getCountMantenimientosEstados':
                // OBTENER LOS ESTAODS DE LOS MANTENIMIENTOS PARA LAS TARJETAS
                try {
                    $mantenimientosEstados = $model->cantidadMantenimientosPorEstados();
                    http_response_code(200);
                    echo json_encode($mantenimientosEstados);
                } catch (PDOException $e) {
                    http_response_code(500);
                    echo json_encode(["success" => false, "message" => "Error al obtener la información para las tarjetas"]);
                }
                
                break;

            case 'getCountOTEstados':
                // OBTENER LOS ESTAODS DE LAS OT PARA LAS TARJETAS
                try {
                    $mantenimientosEstados = $model->cantidadOTPorEstados();
                    http_response_code(200);
                    echo json_encode($mantenimientosEstados);
                } catch (PDOException $e) {
                    http_response_code(500);
                    echo json_encode(["success" => false, "message" => "Error al obtener la información para las tarjetas"]);
                }
                
                break;

            case 'getMantenimientoPorId':
                // OBTENER MANTENIMIENTO POR ID
                $id = $_GET['id'] ?? null;
                if ($id) {
                    try {
                        $mantenimiento = $model->obtenerMantenimientoPorId($id);
                        http_response_code(200);
                        echo json_encode($mantenimiento);
                    } catch (PDOException $e) {
                        http_response_code(500);
                        echo json_encode(["success" => false, "message" => "Error al obtener el mantenimiento"]);
                    }
                } else {
                    http_response_code(400);
                    echo json_encode(["success" => false, "message" => "ID inválido"]);
                }
                break;

            case 'getArticulosBodega':
                // OBTENER ARTICULOS POR BODEGA
                $id_bodega = $_GET['id_bodega'] ?? null;
                if ($id_bodega) {
                    try {
                        $articulosBodega = $model->obtenerArticulosPorBodega($id_bodega);
                        http_response_code(200);
                        echo json_encode($articulosBodega);
                    } catch (PDOException $e) {
                        http_response_code(500);
                        echo json_encode(["success" => false, "message" => "Error al obtener los artículos de la bodega"]);
                    }
                } else {
                    http_response_code(400);
                    echo json_encode(["success" => false, "message" => "ID de bodega inválido"]);
                }
                break;
    
            default:
                echo json_encode(['error' => 'Acción no válida']);
                break;
        }

        break;

    case 'POST':
        // GUARDAR MANTENIMIENTO
        $data = getJsonData();

        $accion = trim($data['accion'] ?? '');
        if($accion === 'guardarMantenimiento'){

            $id_vehiculo = $data['id_vehiculo'] ?? null;
            $id_tipo_mantenimiento = $data['id_tipo_mantenimiento'] ?? null;
            $id_tipo_servicio = $data['id_tipo_servicio'] ?? null;

            if ($id_vehiculo && $id_tipo_mantenimiento && $id_tipo_servicio) {
                try {
                    $mantenimientoObj = (object) $data;
                    $response = $model->guardarMantenimiento($mantenimientoObj);
                    http_response_code(201);
                    echo json_encode(["success" => true, "message" => "Registro guardado exitosamente"]);
                } catch (PDOException $e) {
                    http_response_code(500);
                    echo json_encode([
                        "success" => false,
                        "message" => "Ocurrió un error al guardar el registro. Inténtalo de nuevo o contacta soporte."
                    ]);
                    
                }
            } else {
                http_response_code(400);
                echo json_encode(["success" => false, "message" => "Hay campos obligatorios que no han sido llenados"]);
            }
        }

        break;

    case 'PATCH':
        
        $data = getJsonData();

        $accion = trim($data['accion'] ?? '');

        switch ($accion) {
            case 'aprobarMantenimiento':
                // APROBAR MANTENIMIENTO
                $id = $data['id'] ?? null;
                $id_vehiculo = $data['id_vehiculo'] ?? null;
                $kilometrajeMantenimiento = $data['kilometrajeMantenimiento'] ?? null;
                $kilometrajeVehiculo = $data['kilometrajeVehiculo'] ?? null;

                if ($id) {
                    try {
                        $response = $model->aprobarMantenimiento($id, $id_vehiculo, $kilometrajeMantenimiento, $kilometrajeVehiculo);

                        if ($response['status'] === 'SUCCESS') {
                            http_response_code(201);
                            echo json_encode(["success" => true, "message" => $response['message'], "id_salida" => $response['id_salida']]);
                        } else {
                            http_response_code(500);
                            echo json_encode([
                                "success" => false,
                                "message" => $response['message'],
                                "id_salida" => $response['id_salida']
                            ]);
                        }
                    } catch (PDOException $e) {
                        http_response_code(500);
                        echo json_encode([
                            "success" => false,
                            "message" => "Ocurrió un error al aprobar el registro. Inténtalo de nuevo o contacta soporte."
                        ]);
                        
                    }
                } else {
                    http_response_code(400);
                    echo json_encode(["success" => false, "message" => "ID inválido"]);
                }
                break;
            case 'ejecutarMantenimiento':
                // INICIAR MANTENIMIENTO
                $id = $data['id'] ?? null;
                $id_vehiculo = $data['id_vehiculo'] ?? null;
    
                if ($id && $id_vehiculo) {
                    try {
                        $response = $model->ejecutarMantenimiento($id, $id_vehiculo);
    
                        http_response_code(201);
                        echo json_encode(["success" => true, "message" => "Mantenimiento iniciado exitosamente"]);
                    } catch (PDOException $e) {
                        http_response_code(500);
                        echo json_encode([
                            "success" => false,
                            "message" => "Ocurrió un error al iniciar el mantenimiento. Inténtalo de nuevo o contacta soporte."
                        ]);
                        
                    }
                } else {
                    http_response_code(400);
                    echo json_encode(["success" => false, "message" => "ID inválido"]);
                }
                break;

            case 'finalizarMantenimiento':
                // FINALIZAR MANTENIMIENTO
                $id = $data['id'] ?? null;
                $id_vehiculo = $data['id_vehiculo'] ?? null;
    
                if ($id && $id_vehiculo) {
                    try {
                        $response = $model->finalizarMantenimiento($id, $id_vehiculo);
    
                        http_response_code(201);
                        echo json_encode(["success" => true, "message" => "Mantenimiento completado exitosamente"]);
                    } catch (PDOException $e) {
                        http_response_code(500);
                        echo json_encode([
                            "success" => false,
                            "message" => "Ocurrió un error al completar el mantenimiento. Inténtalo de nuevo o contacta soporte."
                        ]);
                        
                    }
                } else {
                    http_response_code(400);
                    echo json_encode(["success" => false, "message" => "ID inválido"]);
                }
                break;
            default:
                echo json_encode(['error' => 'Acción no válida']);
                break;
        }

        break;

    case 'PUT':
        // EDITAR MANTENIMIENTO
        $data = getJsonData();

        $accion = trim($data['accion'] ?? '');
        if($accion === 'editarMantenimiento'){

            $id_mantenimiento = $data['id_mantenimiento'] ?? null;
            $id_vehiculo = $data['id_vehiculo'] ?? null;
            $id_tipo_mantenimiento = $data['id_tipo_mantenimiento'] ?? null;
            $id_tipo_servicio = $data['id_tipo_servicio'] ?? null;

            if ($id_mantenimiento && $id_vehiculo && $id_tipo_mantenimiento && $id_tipo_servicio) {
                try {
                    $mantenimientoObj = (object) $data;
                    $response = $model->editarMantenimiento($mantenimientoObj);
                    http_response_code(201);
                    echo json_encode(["success" => true, "message" => "Registro actualizado exitosamente"]);
                } catch (PDOException $e) {
                    http_response_code(500);
                    echo json_encode([
                        "success" => false,
                        "message" => "Ocurrió un error al actualizar el registro. Inténtalo de nuevo o contacta soporte."
                    ]);
                    
                }
            } else {
                http_response_code(400);
                echo json_encode(["success" => false, "message" => "Hay campos obligatorios que no han sido llenados"]);
            }
        }

        break;

    case 'DELETE':
        // RECHAZAR MANTENIMIENTO
        $data = getJsonData();

        $accion = trim($data['accion'] ?? '');
        if($accion === 'rechazarMantenimiento'){

            $id = $data['id'] ?? null;
            $motivo_rechazo = $data['motivo_rechazo'] ?? null;
            if ($id) {
                try {
                    $mantenimientoObj = (object) $data;
                    $response = $model->rechazarMantenimiento($id, $motivo_rechazo);
                    http_response_code(201);
                    echo json_encode(["success" => true, "message" => "Mantenimiento rechazado exitosamente"]);
                } catch (PDOException $e) {
                    http_response_code(500);
                    echo json_encode([
                        "success" => false,
                        "message" => "Ocurrió un error al rechazar el mantenimiento. Inténtalo de nuevo o contacta soporte."
                    ]);
                    
                }
            } else {
                http_response_code(400);
                echo json_encode(["success" => false, "message" => "Hay campos incompletos"]);
            }
        }

        break;

    default:
        http_response_code(405);
        echo json_encode(["success" => false, "message" => "Método no permitido"]);
        break;
}

function getJsonData() {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "JSON inválido"]);
        exit;
    }

    return $data;
}

?>
