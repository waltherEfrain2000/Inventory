<?php

// header para trabajar con json
header('Content-Type: application/json');

// Se importa la conexión y el modelo
require_once '../../../config/config.php'; 
require_once '../model/vehiculo.php'; 

$model = new Vehiculo($pdo); 

// obtiene el método enviado por el cliente
switch ($_SERVER['REQUEST_METHOD']) {

    case 'GET':
        $action = isset($_GET['action']) ? $_GET['action'] : '';

        switch ($action) {
            case 'getModelos':
                 // LISTAR MODELOS POR MARCA
                $id_marca = $_GET['id_marca'] ?? null;
                if ($id_marca) {
                    try {
                        $modelos = $model->obtenerModelosPorMarca($id_marca);
                        http_response_code(200);
                        echo json_encode($modelos);
                    } catch (PDOException $e) {
                        http_response_code(500);
                        echo json_encode(["success" => false, "message" => "Error al obtener los modelos"]);
                    }
                } else {
                    http_response_code(400);
                    echo json_encode(["success" => false, "message" => "ID inválido"]);
                }
                break;

            case 'getVehiculos':
                // LISTAR TODOS LOS VEHICULOS                  
                try {
                    $vehiculos = $model->obtenerVehiculos();
                    http_response_code(200);
                    echo json_encode($vehiculos);
                } catch (PDOException $e) {
                    http_response_code(500);
                    echo json_encode(["success" => false, "message" => "Error al obtener los vehículos"]);
                }
               
                break;

            case 'getCountVehiculosEstados':
                // OBTENER LA CANTIDAD DE VEHÍCULOS POR ESTADOS
                try {
                    $vehiculos = $model->cantidadVehiculosPorEstados();
                    http_response_code(200);
                    echo json_encode($vehiculos);
                } catch (PDOException $e) {
                    http_response_code(500);
                    echo json_encode(["success" => false, "message" => "Error al obtener los vehículos"]);
                }
                
                break;

            case 'getVehiculoId':
                // Obtener Vehiulo por id
                $id = $_GET['id'] ?? null;
                if($id){
                    try {
                        $vehiculo = $model->obtenerVehiculoId($id);
                        http_response_code(200);
                        echo json_encode($vehiculo);
                    } catch (PDOException $e) {
                        http_response_code(500);
                        echo json_encode(["success" => false, "message" => "Error al obtener el vehículo"]);
                    }
                } else {
                    http_response_code(400);
                    echo json_encode(["success" => false, "message" => "ID inválido"]);
                }
                
                break;
    
            case 'getInspecciones':
                // LISTAR LAS INSPECCIONES
                try {
                    $inspecciones = $model->obtenerInspecciones();
                    http_response_code(200);
                    echo json_encode($inspecciones);
                } catch (PDOException $e) {
                    http_response_code(500);
                    echo json_encode(["success" => false, "message" => "Error al obtener las inspecciones"]);
                }
                
                break;

            case 'getOdometros':
                // LISTAR LOS ODOMETROS
                try {
                    $odometros = $model->obtenerOdometros();
                    http_response_code(200);
                    echo json_encode($odometros);
                } catch (PDOException $e) {
                    http_response_code(500);
                    echo json_encode(["success" => false, "message" => "Error al obtener los odómetros"]);
                }
                
                break;
    
            case 'getAlertaMantenimientos':
                // LISTAR LAS ALERTAS DE MANTENIMIENTO
                try {
                    $alertas = $model->obtenerAlertasMantenimientos();
                    http_response_code(200);
                    echo json_encode($alertas);
                } catch (PDOException $e) {
                    http_response_code(500);
                    echo json_encode(["success" => false, "message" => "Error al obtener las alertas de mantenimiento"]);
                }
                
                break;

            default:
                echo json_encode(['error' => 'Acción no válida']);
                break;
        }

        break;

    case 'POST':
        // GUARDAR VEHICULO
        $data = getJsonData();

        $accion = trim($data['accion'] ?? '');
        if($accion === 'guardar'){
            $id_marca = $data['id_marca'] ?? null;
            $id_modelo = $data['id_modelo'] ?? null;
            $placa = trim($data['placa'] ?? '');
            $anio = $data['anio'] ?? null;
            $color = $data['color'] ?? null;
            $id_tipo_vehiculo = $data['id_tipo_vehiculo'] ?? null;
            $id_pertenencia = $data['id_pertenencia'] ?? null;
            $kilometraje_actual = $data['kilometraje_actual'] ?? null;
            $intervalo_mantenimiento = $data['intervalo_mantenimiento'] ?? null;

            if ($id_marca && $id_modelo && $id_tipo_vehiculo && $id_pertenencia && strlen($placa) > 0) {
                try {
                    $response = $model->guardarVehiculo($id_marca, $id_modelo, $placa, $anio, $color, $id_tipo_vehiculo, $id_pertenencia, $kilometraje_actual, $intervalo_mantenimiento);
                    http_response_code(201);
                    echo json_encode(["success" => true, "message" => "Registro guardado exitosamente"]);
                } catch (PDOException $e) {
                    http_response_code(500);
                    echo json_encode([
                        "success" => false,
                        "message" => $e->getMessage() == 1062 ? "La placa ya existe en la base de datos" :  "Ocurrió un error al guardar el registro. Inténtalo de nuevo o contacta soporte."
                    ]);
                    
                }
            } else {
                http_response_code(400);
                echo json_encode(["success" => false, "message" => "Hay campos vacíos"]);
            }
        } 
        
        if ($accion === 'guardarInspeccion') {
            $id_vehiculo = $data['id_vehiculo'] ?? null;
            $kilometraje = $data['kilometraje'] ?? null;
            $observaciones = $data['observaciones'] ?? null;

            if ($id_vehiculo && $kilometraje && $observaciones) {
                try {
                    $response = $model->guardarInspeccion($id_vehiculo, $kilometraje, $observaciones);
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
                echo json_encode(["success" => false, "message" => "Hay campos vacíos"]);
            }
        }

        break;

    case 'DELETE':
        // INACTIVAR VEHICULO
        $data = getJsonData();

        $accion = trim($data['accion'] ?? '');
        if($accion === 'eliminar'){
            $id = $data['id'] ?? null;

            if ($id) {
                try {
                    $response = $model->cambiarEstadoVehiculo($id); // en esta funcion sino se envía segundo parámetro por defecto se establece estado inactivo
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
                echo json_encode(["success" => false, "message" => "ID del vehículo no es válido"]);
            }
        }

        break;

    case 'PUT':
        // EDITAR VEHICULO
        $data = getJsonData();

        $accion = trim($data['accion'] ?? '');
        if($accion === 'editar'){
            $id = $data['id'] ?? null;
            $id_marca = $data['id_marca'] ?? null;
            $id_modelo = $data['id_modelo'] ?? null;
            $placa = trim($data['placa'] ?? '');
            $anio = $data['anio'] ?? null;
            $color = $data['color'] ?? null;
            $id_tipo_vehiculo = $data['id_tipo_vehiculo'] ?? null;
            $id_pertenencia = $data['id_pertenencia'] ?? null;
            $intervalo_mantenimiento = $data['intervalo_mantenimiento'] ?? null;

            if ($id && $id_marca && $id_modelo && $id_tipo_vehiculo && $id_pertenencia && strlen($placa) > 0) {
                try {
                    $response = $model->editarVehiculo($id, $id_marca, $id_modelo, $placa, $anio, $color, $id_tipo_vehiculo, $id_pertenencia, $intervalo_mantenimiento);
                    http_response_code(201);
                    echo json_encode(["success" => true, "message" => "Registro actualizado exitosamente"]);
                } catch (PDOException $e) {
                    http_response_code(500);
                    echo json_encode([
                        "success" => false,
                        "message" => $e->getMessage() == 1062 ? "La placa ya se encuentra en la base de datos" :  "Ocurrió un error al actualizar el registro. Inténtalo de nuevo o contacta soporte."
                    ]);
                    
                }
            } else {
                http_response_code(400);
                echo json_encode(["success" => false, "message" => "Hay campos vacíos"]);
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
