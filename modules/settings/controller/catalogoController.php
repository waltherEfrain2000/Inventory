<?php

// header para trabajar con json
header('Content-Type: application/json');

// Se importa la conexión y el modelo
require_once '../../../config/config.php'; 
require_once '../model/catalogo.php'; 

$model = new Catalogo($pdo); 

// obtiene el método enviado por el cliente
switch ($_SERVER['REQUEST_METHOD']) {

    case 'GET':
        $action = isset($_GET['action']) ? $_GET['action'] : '';

        switch ($action) {
            case 'getCatalogo':
                 // LISTAR CATÁLOGOS
                try {
                    $catalogos = $model->obtenerCatalogos();
                    http_response_code(200);
                    echo json_encode($catalogos);
                } catch (PDOException $e) {
                    http_response_code(500);
                    echo json_encode(["success" => false, "message" => "Error al obtener el catálogo"]);
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
        
            default:
                echo json_encode(['error' => 'Acción no válida']);
                break;
        }

       
        break;

    case 'POST':
        // GUARDAR CATÁLOGO
        $data = getJsonData();

        $accion = trim($data['accion'] ?? '');
        if($accion === 'guardar'){
            $nombre = trim($data['nombre'] ?? '');
            $id_tipo_mantenimiento = $data['id_tipo_mantenimiento'] ?? null;

            if ($id_tipo_mantenimiento && strlen($nombre) > 0) {
                try {
                    $response = $model->guardarCatalogo($nombre, $id_tipo_mantenimiento);
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
                echo json_encode(["success" => false, "message" => "El nombre o el tipo de mantenimiento no pueden estar vacíos"]);
            }
        }
        
        break;

    case 'DELETE':
        // ELIMINAR CATÁLOGO
        $data = getJsonData();

        $accion = trim($data['accion'] ?? '');
        if($accion === 'eliminar'){
            $id = $data['id'] ?? null;

            if ($id) {
                try {
                    $response = $model->eliminarCatalogo($id);
                    if ($response) {
                        http_response_code(200);
                        echo json_encode(["success" => true, "message" => "Registro eliminado exitosamente"]);
                    } else {
                        http_response_code(404);
                        echo json_encode(["success" => false, "message" => "Registro no encontrado"]);
                    }
                } catch (PDOException $e) {
                    http_response_code(500);
                    echo json_encode(["success" => false, "message" => "Error al eliminar el registro"]);
                }
            } else {
                http_response_code(400);
                echo json_encode(["success" => false, "message" => "ID inválido"]);
            }
        }
        break;

    case 'PUT':
        // EDITAR CATÁLOGO
        $data = getJsonData();

        $accion = trim($data['accion'] ?? '');
        if($accion === 'editar'){
            $id = $data['id'] ?? null;
            $nombre = trim($data['nombre'] ?? '');
            $id_tipo_mantenimiento = $data['id_tipo_mantenimiento'] ?? null;

            if ($id_tipo_mantenimiento && $id && strlen($nombre) > 0) {
                try {
                    $response = $model->editarCatalogo($id, $nombre, $id_tipo_mantenimiento);
                    if ($response) {
                        http_response_code(200);
                        echo json_encode(["success" => true, "message" => "Registro actualizado exitosamente"]);
                    } else {
                        http_response_code(404);
                        echo json_encode(["success" => false, "message" => "Registro no encontrado"]);
                    }
                } catch (PDOException $e) {
                    http_response_code(500);
                    echo json_encode([
                        "success" => false, 
                        "message" => "Ocurrió un error al actualizar el registro. Inténtalo de nuevo o contacta soporte."
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
