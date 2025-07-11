<?php

// header para trabajar con json
header('Content-Type: application/json');

// Se importa la conexión y el modelo
require_once '../../../config/config.php'; 
require_once '../model/taller.php'; 

$model = new Taller($pdo); 

// obtiene el método enviado por el cliente
switch ($_SERVER['REQUEST_METHOD']) {

    case 'GET':
        // LISTAR TALLERES
        try {
            $talleres = $model->obtenerTalleres();
            http_response_code(200);
            echo json_encode($talleres);
        } catch (PDOException $e) {
            error_log("Error al listar los talleres: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(["success" => false, "message" => "Error al obtener los talleres"]);
        }
        break;

    case 'POST':
        // GUARDAR TALLER
        $data = getJsonData();

        $accion = trim($data['accion'] ?? '');
        if($accion === 'guardar'){
            $nombre = trim($data['nombre'] ?? '');
            $telefono = trim($data['telefono'] ?? null);
            $ubicacion = trim($data['ubicacion'] ?? null);

            if (strlen($nombre) > 0) {
                try {
                    $response = $model->guardarTaller($nombre, $telefono, $ubicacion);
                    http_response_code(201);
                    echo json_encode(["success" => true, "message" => "Taller guardado exitosamente"]);
                } catch (PDOException $e) {
                    error_log("Error al guardar el taller: " . $e->getMessage());
                    http_response_code(500);
                    echo json_encode([
                        "success" => false,
                        "message" => $e->getMessage() == 1062 ? "El taller ya existe en la base de datos" : "Ocurrió un error al guardar el taller. Inténtalo de nuevo o contacta soporte."
                    ]);
                    
                }
            } else {
                http_response_code(400);
                echo json_encode(["success" => false, "message" => "El nombre no puede estar vacío"]);
            }
        }
        
        break;

    case 'DELETE':
        // ELIMINAR TALLER
        $data = getJsonData();

        $accion = trim($data['accion'] ?? '');
        if($accion === 'eliminar'){
            $id = $data['id'] ?? null;

            if ($id) {
                try {
                    $response = $model->eliminarTaller($id);
                    if ($response) {
                        http_response_code(200);
                        echo json_encode(["success" => true, "message" => "Taller eliminado exitosamente"]);
                    } else {
                        http_response_code(404);
                        echo json_encode(["success" => false, "message" => "Taller no encontrado"]);
                    }
                } catch (PDOException $e) {
                    error_log("Error al eliminar el taller: " . $e->getMessage());
                    http_response_code(500);
                    echo json_encode(["success" => false, "message" => "Error al eliminar el taller"]);
                }
            } else {
                http_response_code(400);
                echo json_encode(["success" => false, "message" => "ID inválido"]);
            }
        }
        break;

    case 'PUT':
        // EDITAR TALLER
        $data = getJsonData();

        $accion = trim($data['accion'] ?? '');
        if($accion === 'editar'){
            $id = $data['id'] ?? null;
            $nombre = trim($data['nombre'] ?? '');
            $telefono = trim($data['telefono'])?? null;
            $ubicacion = trim($data['ubicacion']) ?? null;

            if ($id && strlen($nombre) > 0) {
                try {
                    $response = $model->editarTaller($id, $nombre, $telefono, $ubicacion);
                    if ($response) {
                        http_response_code(200);
                        echo json_encode(["success" => true, "message" => "Taller actualizado exitosamente"]);
                    } else {
                        http_response_code(404);
                        echo json_encode(["success" => false, "message" => "Taller no encontrado"]);
                    }
                } catch (PDOException $e) {
                    error_log("Error al editar el taller: " . $e->getMessage());
                    http_response_code(500);
                    echo json_encode([
                        "success" => false, 
                        "message" => $e->getMessage() == 1062 ? "El taller ya existe en la base de datos" : "Ocurrió un error al actualizar el taller. Inténtalo de nuevo o contacta soporte."
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
