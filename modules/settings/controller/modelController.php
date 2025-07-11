<?php

// header para trabajar con json
header('Content-Type: application/json');

// Se importa la conexión y el modelo
require_once '../../../config/config.php'; 
require_once '../model/model.php'; 

$modeloModel = new Modelo($pdo); 

// obtiene el método enviado por el cliente
switch ($_SERVER['REQUEST_METHOD']) {

    case 'GET':
        // LISTAR MODELOS
        try {
            $modelos = $modeloModel->obtenerModelos();
            http_response_code(200);
            echo json_encode($modelos);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(["success" => false, "message" => "Error al obtener los modelos"]);
        }
        break;

    case 'POST':
        // GUARDAR MODELO
        $data = getJsonData();

        $accion = trim($data['accion'] ?? '');
        if($accion === 'guardar'){
            $nombre = trim($data['nombre'] ?? '');
            $id_marca = $data['id_marca'] ?? null;

            if ($id_marca && strlen($nombre) > 0) {
                try {
                    $response = $modeloModel->guardarModelo($nombre, $id_marca);
                    http_response_code(201);
                    echo json_encode(["success" => true, "message" => "Modelo guardado exitosamente"]);
                } catch (PDOException $e) {
                    http_response_code(500);
                    echo json_encode([
                        "success" => false,
                        "message" => "Ocurrió un error al guardar el modelo. Inténtalo de nuevo o contacta soporte."
                    ]);
                    
                }
            } else {
                http_response_code(400);
                echo json_encode(["success" => false, "message" => "El nombre o la marca no pueden estar vacíos"]);
            }
        }
        
        break;

    case 'DELETE':
        // ELIMINAR MODELO
        $data = getJsonData();

        $accion = trim($data['accion'] ?? '');
        if($accion === 'eliminar'){
            $id = $data['id'] ?? null;

            if ($id) {
                try {
                    $response = $modeloModel->eliminarModelo($id);
                    if ($response) {
                        http_response_code(200);
                        echo json_encode(["success" => true, "message" => "Modelo eliminado exitosamente"]);
                    } else {
                        http_response_code(404);
                        echo json_encode(["success" => false, "message" => "Modelo no encontrado"]);
                    }
                } catch (PDOException $e) {
                    http_response_code(500);
                    echo json_encode(["success" => false, "message" => "Error al eliminar el modelo"]);
                }
            } else {
                http_response_code(400);
                echo json_encode(["success" => false, "message" => "ID inválido"]);
            }
        }
        break;

    case 'PUT':
        // EDITAR MODELO
        $data = getJsonData();

        $accion = trim($data['accion'] ?? '');
        if($accion === 'editar'){
            $id = $data['id'] ?? null;
            $nombre = trim($data['nombre'] ?? '');
            $id_marca = $data['id_marca'] ?? null;

            if ($id_marca && $id && strlen($nombre) > 0) {
                try {
                    $response = $modeloModel->editarModelo($id, $nombre, $id_marca);
                    if ($response) {
                        http_response_code(200);
                        echo json_encode(["success" => true, "message" => "Modelo actualizado exitosamente"]);
                    } else {
                        http_response_code(404);
                        echo json_encode(["success" => false, "message" => "Modelo no encontrado"]);
                    }
                } catch (PDOException $e) {
                    http_response_code(500);
                    echo json_encode([
                        "success" => false, 
                        "message" => "Ocurrió un error al actualizar el modelo. Inténtalo de nuevo o contacta soporte."
                    ]);
                }
            } else {
                http_response_code(400);
                echo json_encode(["success" => false, "message" => "Información inválida"]);
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
