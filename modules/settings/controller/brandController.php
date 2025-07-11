<?php

// header para trabajar con json
header('Content-Type: application/json');

// Se importa la conexión y el modelo
require_once '../../../config/config.php'; 
require_once '../model/brand.php'; 

$marcaModel = new Marca($pdo); 

// obtiene el método enviado por el cliente
switch ($_SERVER['REQUEST_METHOD']) {

    case 'GET':
        // LISTAR MARCAS
        try {
            $marcas = $marcaModel->obtenerMarcas();
            http_response_code(200);
            echo json_encode($marcas);
        } catch (PDOException $e) {
            error_log("Error al listar las marcas: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(["success" => false, "message" => "Error al obtener las marcas"]);
        }
        break;

    case 'POST':
        // GUARDAR MARCA
        $data = getJsonData();

        $accion = trim($data['accion'] ?? '');
        if($accion === 'guardar'){
            $nombre = trim($data['nombre'] ?? '');

            if (strlen($nombre) > 0) {
                try {
                    $response = $marcaModel->guardarMarca($nombre);
                    http_response_code(201);
                    echo json_encode(["success" => true, "message" => "Marca guardada exitosamente"]);
                } catch (PDOException $e) {
                    error_log("Error al guardar la marca: " . $e->getMessage());
                    http_response_code(500);
                    echo json_encode([
                        "success" => false,
                        "message" => $e->getMessage() == 1062 ? "La marca ya existe en la base de datos" : "Ocurrió un error al guardar la marca. Inténtalo de nuevo o contacta soporte."
                    ]);
                    
                }
            } else {
                http_response_code(400);
                echo json_encode(["success" => false, "message" => "El nombre no puede estar vacío"]);
            }
        }
        
        break;

    case 'DELETE':
        // ELIMINAR MARCA
        $data = getJsonData();

        $accion = trim($data['accion'] ?? '');
        if($accion === 'eliminar'){
            $id = $data['id'] ?? null;

            if ($id) {
                try {
                    $response = $marcaModel->eliminarMarca($id);
                    if ($response) {
                        http_response_code(200);
                        echo json_encode(["success" => true, "message" => "Marca eliminada exitosamente"]);
                    } else {
                        http_response_code(404);
                        echo json_encode(["success" => false, "message" => "Marca no encontrada"]);
                    }
                } catch (PDOException $e) {
                    error_log("Error al eliminar la marca: " . $e->getMessage());
                    http_response_code(500);
                    echo json_encode(["success" => false, "message" => "Error al eliminar la marca"]);
                }
            } else {
                http_response_code(400);
                echo json_encode(["success" => false, "message" => "ID inválido"]);
            }
        }
        break;

    case 'PUT':
        // EDITAR MARCA
        $data = getJsonData();

        $accion = trim($data['accion'] ?? '');
        if($accion === 'editar'){
            $id = $data['id'] ?? null;
            $nombre = trim($data['nombre'] ?? '');

            if ($id && strlen($nombre) > 0) {
                try {
                    $response = $marcaModel->editarMarca($id, $nombre);
                    if ($response) {
                        http_response_code(200);
                        echo json_encode(["success" => true, "message" => "Marca actualizada exitosamente"]);
                    } else {
                        http_response_code(404);
                        echo json_encode(["success" => false, "message" => "Marca no encontrada"]);
                    }
                } catch (PDOException $e) {
                    error_log("Error al editar la marca: " . $e->getMessage());
                    http_response_code(500);
                    echo json_encode([
                        "success" => false, 
                        "message" => $e->getMessage() == 1062 ? "La marca ya existe en la base de datos" : "Ocurrió un error al actualizar la marca. Inténtalo de nuevo o contacta soporte."
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
