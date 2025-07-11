<?php

// header para trabajar con json
header('Content-Type: application/json');

// Se importa la conexión y el modelo
require_once '../../../config/config.php'; 
require_once '../model/ownership.php'; 

$model = new Pertenencia($pdo); 

// obtiene el método enviado por el cliente
switch ($_SERVER['REQUEST_METHOD']) {

    case 'GET':
        // LISTAR PERTENENCIAS
        try {
            $pertenencias = $model->obtenerPertenencias();
            http_response_code(200);
            echo json_encode($pertenencias);
        } catch (PDOException $e) {
            error_log("Error al listar las pertenencias: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(["success" => false, "message" => "Error al obtener las pertenencias"]);
        }
        break;

    case 'POST':
        // GUARDAR PERTENENCIA
        $data = getJsonData();

        $accion = trim($data['accion'] ?? '');
        if($accion === 'guardar'){
            $nombre = trim($data['nombre'] ?? '');

            if (strlen($nombre) > 0) {
                try {
                    $response = $model->guardarPertenencia($nombre);
                    http_response_code(201);
                    echo json_encode(["success" => true, "message" => "Pertenencia guardada exitosamente"]);
                } catch (PDOException $e) {
                    error_log("Error al guardar la pertenencia: " . $e->getMessage());
                    http_response_code(500);
                    echo json_encode([
                        "success" => false,
                        "message" => $e->getMessage() == 1062 ? "La pertenencia ya existe en la base de datos" : "Ocurrió un error al guardar la pertenencia. Inténtalo de nuevo o contacta soporte."
                    ]);
                    
                }
            } else {
                http_response_code(400);
                echo json_encode(["success" => false, "message" => "El nombre no puede estar vacío"]);
            }
        }
        
        break;

    case 'DELETE':
        // ELIMINAR PERTENENCIA
        $data = getJsonData();

        $accion = trim($data['accion'] ?? '');
        if($accion === 'eliminar'){
            $id = $data['id'] ?? null;

            if ($id) {
                try {
                    $response = $model->eliminarPertenencia($id);
                    if ($response) {
                        http_response_code(200);
                        echo json_encode(["success" => true, "message" => "Pertenencia eliminada exitosamente"]);
                    } else {
                        http_response_code(404);
                        echo json_encode(["success" => false, "message" => "Pertenencia no encontrada"]);
                    }
                } catch (PDOException $e) {
                    error_log("Error al eliminar la pertenencia: " . $e->getMessage());
                    http_response_code(500);
                    echo json_encode(["success" => false, "message" => "Error al eliminar la pertenencia"]);
                }
            } else {
                http_response_code(400);
                echo json_encode(["success" => false, "message" => "ID inválido"]);
            }
        }
        break;

    case 'PUT':
        // EDITAR PERTENENCIA
        $data = getJsonData();

        $accion = trim($data['accion'] ?? '');
        if($accion === 'editar'){
            $id = $data['id'] ?? null;
            $nombre = trim($data['nombre'] ?? '');

            if ($id && strlen($nombre) > 0) {
                try {
                    $response = $model->editarPertenencia($id, $nombre);
                    if ($response) {
                        http_response_code(200);
                        echo json_encode(["success" => true, "message" => "Pertenencia actualizada exitosamente"]);
                    } else {
                        http_response_code(404);
                        echo json_encode(["success" => false, "message" => "Pertenencia no encontrada"]);
                    }
                } catch (PDOException $e) {
                    error_log("Error al editar la pertenencia: " . $e->getMessage());
                    http_response_code(500);
                    echo json_encode([
                        "success" => false, 
                        "message" => $e->getMessage() == 1062 ? "La pertenencia ya existe en la base de datos" : "Ocurrió un error al actualizar la pertenencia. Inténtalo de nuevo o contacta soporte."
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
