<?php
require_once __DIR__ . '/../../../../config/config.php';
require_once __DIR__ . '/../../model/products/product.php';

// Obtener la conexión PDO
$pdo = require __DIR__ . '/../../../../config/config.php';
// Instanciar el modelo de producto
$productModel = new Product($pdo);

// Manejar la solicitud de inserción de artículo
try {
    // Verificar si los datos necesarios han sido enviados en la solicitud
    if (isset( $_POST['NombreArticulo'], $_POST['DescripcionArticulo'])) {
        
        // Obtener los datos enviados por POST
       // $idSubCategoria = $_POST['idSubCategoria'];
        $idSubCategoria =$_POST['idSubCategoria'];
//        $UsuarioCreador = $_POST['UsuarioCreador'];
        $NombreArticulo = $_POST['NombreArticulo'];
        $DescripcionArticulo = $_POST['DescripcionArticulo'];
        $Estado = $_POST['Estado'];
       // $ProveedorID = $_POST['ProveedorID'];
        $CantidadInicial = $_POST['CantidadInicial'];
        $PrecioCompra = $_POST['PrecioCompra'];
        $PrecioVenta = $_POST['PrecioVenta'];
        $idUnidadMedida = $_POST['idUnidadMedida'];
        $usuario_id = isset($_SESSION['usuario_id']) ? intval($_SESSION['usuario_id']) : 1;

        // Llamar a la función saveArticle del modelo
        $result = $productModel->saveArticle($idSubCategoria, $usuario_id, $NombreArticulo, $DescripcionArticulo, $Estado, $CantidadInicial, $PrecioCompra, $PrecioVenta, $idUnidadMedida);

        // Verificar si la inserción fue exitosa
        if ($result) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "error" => "No se pudo guardar el artículo."]);
        }
    } else {
        // Si falta algún parámetro necesario
        echo json_encode(["success" => false, "error" => "Faltan parámetros para guardar el artículo."]);
    }
} catch (\Throwable $th) {
    // Manejo de excepciones
    echo json_encode(["success" => false, "error" => "Error al guardar el artículo: " . $th->getMessage()]);
}
