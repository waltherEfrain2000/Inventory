<?php
require_once __DIR__ . '/../../../../config/config.php';
require_once __DIR__ . '/../../model/categories/category.php';

session_start(); // Iniciar la sesión para acceder al usuario logueado

$pdo = require __DIR__ . '/../../../../config/config.php';
$categoryModel = new Category($pdo);

try {
 
    $categoriaNombre = $_POST['categoriaNombre'] ?? null;
    $categoriaDescripcion = $_POST['categoriaDescripcion'] ?? null;

    if (!$categoriaNombre || !$categoriaDescripcion) {
        echo json_encode(["success" => false, "error" => "Datos incompletos"]);
        exit;
    }


    $usuario_id = isset($_SESSION['usuario_id']) ? intval($_SESSION['usuario_id']) : 1;


    $resultado = $categoryModel->saveCategory($categoriaNombre, $categoriaDescripcion, $usuario_id);

    if ($resultado) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => "Error al guardar la categoría"]);
    }
} catch (\Throwable $th) {
    echo json_encode(["success" => false, "error" => "Error al guardar la categoría"]);
}
