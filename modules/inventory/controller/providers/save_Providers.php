<?php
require_once __DIR__ . '/../../../../config/config.php';
require_once __DIR__ . '/../../model/providers/providers.php';

// Obtener la conexión PDO
$pdo = require __DIR__ . '/../../../../config/config.php';
// Instanciar el modelo de proveedor
$productModel = new Provider($pdo);

// Manejar la solicitud de inserción o actualización de proveedor
try {
    // Verificar si los datos necesarios han sido enviados en la solicitud
    if (isset($_POST['Nombre'], $_POST['Descripcion'])) {
        
        // Obtener los datos enviados por POST
        $id = isset($_POST['id']) ? $_POST['id'] : null; // Verificar si se está enviando un ID para actualización
        $Nombre = $_POST['Nombre'];
        $Descripcion = $_POST['Descripcion'];
        $numeroCelular = $_POST['numeroCelular'];
        $Estado = $_POST['Estado'];
        $direccionProveedor = $_POST['direccionProveedor'];
        $nombreContacto = $_POST['nombreContacto'];

        // Llamar a la función saveProvider del modelo
        // Si el ID está presente, se actualiza, si no, se inserta
        $result = $productModel->saveProvider($id, $Nombre, $Descripcion, $Estado, 1, $numeroCelular, $direccionProveedor, $nombreContacto);

        // Verificar si la operación fue exitosa
        if ($result) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "error" => "No se pudo guardar o actualizar el proveedor."]);
        }
    } else {
        // Si falta algún parámetro necesario
        echo json_encode(["success" => false, "error" => "Faltan parámetros para guardar o actualizar el proveedor."]);
    }
} catch (\Throwable $th) {
    // Manejo de excepciones
    echo json_encode(["success" => false, "error" => "Error al guardar o actualizar el proveedor: " . $th->getMessage()]);
}
?>
