<?php
require_once __DIR__ . '/../../../../config/config.php';
require_once __DIR__ . '/../../model/categories/category.php';

session_start(); // Iniciar la sesión para acceder a los datos del usuario

$pdo = require __DIR__ . '/../../../../config/config.php';
$categoryModel = new Category($pdo);

try {

    $idCategoria = $_POST['idCategoria'];
    $subcategoriaNombre = $_POST['subCategoriaNombre'];
    $subcategoriaDescripcion = $_POST['subCategoriaDescripcion'];

      if (!isset($subcategoriaNombre, $subcategoriaDescripcion, $idCategoria)) {
        echo json_encode(["success" => false, "error" => "Datos incompletos"]);
        exit;
    }
    
    $usuario_id = isset($_SESSION['usuario_id']) ? intval($_SESSION['usuario_id']) : 1;

    $resultado = $categoryModel->saveSubCategory($subcategoriaNombre, $subcategoriaDescripcion, $usuario_id, $idCategoria);

    if ($resultado) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => "Error al guardar la subcategoría"]);
    }
} catch (\Throwable $th) {
    echo json_encode(["success" => false, "error" => "Error al guardar la subcategoría"]);
}