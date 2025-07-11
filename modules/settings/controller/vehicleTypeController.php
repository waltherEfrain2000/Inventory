<?php

// header para trabajar con json
header('Content-Type: application/json');

// Se importa la conexión y el modelo
require_once '../../../config/config.php'; 
require_once '../model/vehicle_type.php'; 

$model = new TipoVehiculo($pdo); 

// obtiene el método enviado por el cliente
switch ($_SERVER['REQUEST_METHOD']) {

    case 'GET':
        // LISTAR TIPO VEHICULO
        try {
            $tiposVehiculo = $model->obtenerTiposVehiculo();
            http_response_code(200);
            echo json_encode($tiposVehiculo);
        } catch (PDOException $e) {
            error_log("Error al listar los tipos de vehículo: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(["success" => false, "message" => "Error al obtener los tipos de vehículo"]);
        }
        break;

    case 'POST':
        // GUARDAR TIPO VEHICULO
        $data = getJsonData();

        $accion = trim($data['accion'] ?? '');
        if($accion === 'guardar'){
            $nombre = trim($data['nombre'] ?? '');

            if (strlen($nombre) > 0) {
                try {
                    $response = $model->guardarTipoVehiculo($nombre);
                    http_response_code(201);
                    echo json_encode(["success" => true, "message" => "Tipo de vehículo guardado exitosamente"]);
                } catch (PDOException $e) {
                    error_log("Error al guardar el tipo de vehículo: " . $e->getMessage());
                    http_response_code(500);
                    echo json_encode([
                        "success" => false,
                        "message" => $e->getMessage() == 1062 ? "El tipo de vehículo ya existe en la base de datos" : "Ocurrió un error al guardar el tipo de vehículo. Inténtalo de nuevo o contacta soporte."
                    ]);
                    
                }
            } else {
                http_response_code(400);
                echo json_encode(["success" => false, "message" => "El nombre no puede estar vacío"]);
            }
        }
        
        break;

    case 'DELETE':
        // ELIMINAR TIPO VEHICULO
        $data = getJsonData();

        $accion = trim($data['accion'] ?? '');
        if($accion === 'eliminar'){
            $id = $data['id'] ?? null;

            if ($id) {
                try {
                    $response = $model->eliminarTipoVehiculo($id);
                    if ($response) {
                        http_response_code(200);
                        echo json_encode(["success" => true, "message" => "Tipo de vehículo eliminado exitosamente"]);
                    } else {
                        http_response_code(404);
                        echo json_encode(["success" => false, "message" => "Tipo de vehículo no encontrado"]);
                    }
                } catch (PDOException $e) {
                    error_log("Error al eliminar el tipo de vehículo: " . $e->getMessage());
                    http_response_code(500);
                    echo json_encode(["success" => false, "message" => "Error al eliminar el tipo de vehículo"]);
                }
            } else {
                http_response_code(400);
                echo json_encode(["success" => false, "message" => "ID inválido"]);
            }
        }
        break;

    case 'PUT':
        // EDITAR TIPO VEHICULO
        $data = getJsonData();

        $accion = trim($data['accion'] ?? '');
        if($accion === 'editar'){
            $id = $data['id'] ?? null;
            $nombre = trim($data['nombre'] ?? '');

            if ($id && strlen($nombre) > 0) {
                try {
                    $response = $model->editarTipoVehiculo($id, $nombre);
                    if ($response) {
                        http_response_code(200);
                        echo json_encode(["success" => true, "message" => "Tipo de vehículo actualizado exitosamente"]);
                    } else {
                        http_response_code(404);
                        echo json_encode(["success" => false, "message" => "Tipo de vehículo no encontrado"]);
                    }
                } catch (PDOException $e) {
                    error_log("Error al editar el tipo de vehículo: " . $e->getMessage());
                    http_response_code(500);
                    echo json_encode([
                        "success" => false, 
                        "message" => $e->getMessage() == 1062 ? "El tipo de vehículo ya existe en la base de datos" : "Ocurrió un error al actualizar el tipo de vehículo. Inténtalo de nuevo o contacta soporte."
                    ]);
                }
            } else {
                http_response_code(400);
                echo json_encode(["success" => false, "message" => "ID o nombre inválido"]);
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
