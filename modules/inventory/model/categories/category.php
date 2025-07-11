<?php
class Category
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Método para guardar una nueva categoría
    public function saveCategory($nombreCategoria, $DescripcionCategoria, $UsuarioCreador)
    {
        try {
            $sql = "INSERT INTO inventario_Categorias (nombreCategoria,
             DescripcionCategoria,Estado,
              UsuarioCreador,FechaCreacion) 
                    VALUES (:nombreCategoria, :DescripcionCategoria,1,:UsuarioCreador,now())";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':nombreCategoria', $nombreCategoria, PDO::PARAM_STR);
            $stmt->bindParam(':DescripcionCategoria', $DescripcionCategoria, PDO::PARAM_STR);
            $stmt->bindParam(':UsuarioCreador', $UsuarioCreador, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            die("Error al guardar la categoría: " . $e->getMessage());
        }
    }

    // Método para eliminar una categoría por ID
    public function deleteCategory($id)
    {
        try {
            $sql = "UPDATE inventario_Categorias SET Estado = 0 WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            return ["success" => false, "error" => "Error al actualizar el estado de la categoría: " . $e->getMessage()];
        }
    }


    // Método para obtener todas las categorías
    public function getCategories()
    {
        try {
            $sql = "SELECT * FROM inventario_Categorias  WHERE Estado = 1 ORDER BY nombreCategoria";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener las categorías: " . $e->getMessage());
        }
    }

    // Método para obtener una categoría por ID
    public function getCategoryById($id)
    {
        try {
            $sql = "SELECT * FROM inventario_Categorias WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener la categoría: " . $e->getMessage());
        }
    }


    public function updateCategory($categoriaNombre, $categoriaDescripcion, $UsuarioModificador, $id)
    {
        try {
            // Validar parámetros
            if (empty($categoriaNombre) || empty($categoriaDescripcion) || empty($UsuarioModificador) || empty($id)) {
                return ["success" => false, "error" => "Parámetros inválidos o incompletos."];
            }

            // Consulta SQL corregida
            $sql = "UPDATE inventario_Categorias 
                    SET nombreCategoria = :nombreCategoria, 
                        DescripcionCategoria = :DescripcionCategoria, 
                        UsuarioModificador = :UsuarioModificador, 
                        FechaModificacion = NOW()
                    WHERE id = :id";

            $stmt = $this->pdo->prepare($sql);

            // Asignar valores a los parámetros
            $stmt->bindValue(':nombreCategoria', $categoriaNombre, PDO::PARAM_STR);
            $stmt->bindValue(':DescripcionCategoria', $categoriaDescripcion, PDO::PARAM_STR);
            $stmt->bindValue(':UsuarioModificador', $UsuarioModificador, PDO::PARAM_INT);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);

            // Ejecutar la consulta
            if ($stmt->execute()) {
                return ["success" => true];
            } else {
                return ["success" => false, "error" => "No se pudo actualizar la categoría."];
            }
        } catch (PDOException $e) {
            // Manejo de errores de base de datos
            return ["success" => false, "error" => "Error en la base de datos: " . $e->getMessage()];
        }
    }

    /**
     * ! Método para listar subcategorías
     */

    public function getSubCategories()
    {
        try {
            $sql = "SELECT isc.id, isc.idCategoria, isc.nombreSubCategoria, isc.DescripcionSubCategoria, ic.nombreCategoria FROM inventario_SubCategoria  isc 
JOIN inventario_Categorias ic on ic.id  = isc.idCategoria   
WHERE isc .Estado = 1 ORDER BY isc .nombreSubCategoria
";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener las categorías: " . $e->getMessage());
        }
    }

    // Método para eliminar una Subcategoría por ID
    public function deleteSubCategory($id)
    {
        try {
            $sql = "UPDATE inventario_SubCategoria SET Estado = 0 WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            return ["success" => false, "error" => "Error al actualizar el estado de la categoría: " . $e->getMessage()];
        }
    }

    public function saveSubCategory($nombreSubCategoria, $DescripcionSubCategoria, $UsuarioCreador, $idCategoria)
    {
        try {
            // Validar parámetros
            if (empty($nombreSubCategoria) || empty($DescripcionSubCategoria) || empty($UsuarioCreador) || empty($idCategoria)) {
                return ["success" => false, "error" => "Parámetros inválidos o incompletos."];
            }

            // Consulta SQL corregida
            $sql = "INSERT INTO inventario_SubCategoria (
                          idCategoria, 
                           UsuarioCreador, 
                          nombreSubCategoria, 
                          DescripcionSubCategoria, 
                          Estado, 
                          FechaCreacion
                      ) VALUES (
                          :idCategoria, 
                            :UsuarioCreador, 
                          :nombreSubCategoria, 
                          :DescripcionSubCategoria, 
                          1, 
                          NOW()
                      )";

            $stmt = $this->pdo->prepare($sql);

            // Asignar valores a los parámetros
            $stmt->bindValue(':UsuarioCreador', $UsuarioCreador, PDO::PARAM_STR);
            $stmt->bindValue(':idCategoria', $idCategoria, PDO::PARAM_INT);
            $stmt->bindValue(':nombreSubCategoria', $nombreSubCategoria, PDO::PARAM_STR);
            $stmt->bindValue(':DescripcionSubCategoria', $DescripcionSubCategoria, PDO::PARAM_STR);


            // Ejecutar la consulta
            if ($stmt->execute()) {
                return ["success" => true];
            } else {
                return ["success" => false, "error" => "No se pudo guardar la subcategoría."];
            }
        } catch (PDOException $e) {
            // Manejo de errores de base de datos
            return ["success" => false, "error" => "Error en la base de datos: " . $e->getMessage()];
        }
    }


    public function updateSubCategory($id, $idCategoria, $subcategoriaNombre, $DescripcionSubCategoria, $UsuarioModificador)
    {
        try {
            if (empty($id) || empty($idCategoria) || empty($subcategoriaNombre) || empty($DescripcionSubCategoria) || empty($UsuarioModificador)) {
                return ["success" => false, "error" => "Parámetros inválidos o incompletos."];
            }

            $sql = "UPDATE inventario_SubCategoria 
                    SET idCategoria = :idCategoria, 
                        nombreSubCategoria = :subcategoriaNombre, 
                        DescripcionSubCategoria = :DescripcionSubCategoria, 
                        UsuarioModificador = :UsuarioModificador,
                        FechaModificacion = NOW()
                    WHERE id = :id";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':idCategoria', $idCategoria, PDO::PARAM_INT);
            $stmt->bindValue(':subcategoriaNombre', $subcategoriaNombre, PDO::PARAM_STR);
            $stmt->bindValue(':DescripcionSubCategoria', $DescripcionSubCategoria, PDO::PARAM_STR);
            $stmt->bindValue(':UsuarioModificador', $UsuarioModificador, PDO::PARAM_INT);

            if ($stmt->execute()) {
                return ["success" => true];
            } else {
                return ["success" => false, "error" => "No se pudo actualizar la subcategoría."];
            }
        } catch (PDOException $e) {
            return ["success" => false, "error" => "Error en la base de datos: " . $e->getMessage()];
        }
    }

    public function getSubCategoryById($id)
    {
        try {
            $sql = "SELECT * FROM inventario_SubCategoria WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener la categoría: " . $e->getMessage());
        }
    }

    public function getSubCategoriesById($idCategoria)
    {
        try {
            $sql = "SELECT * FROM inventario_SubCategoria WHERE idCategoria = :idCategoria";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':idCategoria', $idCategoria, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener la categoría: " . $e->getMessage());
        }
    }

}
