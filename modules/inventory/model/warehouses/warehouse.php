<?php
class Warehouse
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }


    public function getWarehouses()
    {
        try {
            $sql = "SELECT * FROM inventario_Bodegas ORDER BY nombreBodega";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener las categorías: " . $e->getMessage());
        }
    }

    public function deleteWarehouse($id)
    {
        try {
            $sql = "UPDATE inventario_Bodegas SET Estado = 0 WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            return ["success" => false, "error" => "Error al actualizar el estado de la categoría: " . $e->getMessage()];
        }
    }

    public function saveWarehouse($NombreBodega, $DescripcionBodega, $Estado, $UsuarioCreador)
    {
        try {
            $sql = "INSERT INTO inventario_Bodegas (
                        NombreBodega,
                        DescripcionBodega,
                        Estado,
                        UsuarioCreador,
                        FechaCreacion
                    ) VALUES (
                        :NombreBodega,
                        :DescripcionBodega,
                        :Estado,
                        :UsuarioCreador,
                        NOW()
                    )";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':NombreBodega', $NombreBodega, PDO::PARAM_STR);
            $stmt->bindParam(':DescripcionBodega', $DescripcionBodega, PDO::PARAM_STR);
            $stmt->bindParam(':Estado', $Estado, PDO::PARAM_INT); // Usar el valor de $Estado
            $stmt->bindParam(':UsuarioCreador', $UsuarioCreador, PDO::PARAM_STR);

            return $stmt->execute();
        } catch (PDOException $e) {
            die("Error al guardar la bodega: " . $e->getMessage());
        }
    }

    public function getWarehouseById($id)
    {
        try {
            $sql = "SELECT * FROM inventario_Bodegas WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener la categoría: " . $e->getMessage());
        }
    }
    public function updateWarehouse($NombreBodega, $DescripcionBodega, $Estado, $UsuarioModificador, $id)
    {
        try {
            // Validar parámetros
            if (empty($NombreBodega) || empty($DescripcionBodega) || empty($Estado) || empty($id)) {
                return ["success" => false, "error" => "Parámetros inválidos o incompletos."];
            }

            // Consulta SQL corregida
            $sql = "UPDATE inventario_Bodegas 
                    SET NombreBodega = :NombreBodega, 
                        DescripcionBodega = :DescripcionBodega, 
                        UsuarioModificador = :UsuarioModificador,
                        Estado = :Estado, 
                        FechaModificacion = NOW()
                    WHERE id = :id";

            $stmt = $this->pdo->prepare($sql);

            // Asignar valores a los parámetros
            $stmt->bindValue(':NombreBodega', $NombreBodega, PDO::PARAM_STR);
            $stmt->bindValue(':DescripcionBodega', $DescripcionBodega, PDO::PARAM_STR);
            $stmt->bindValue(':UsuarioModificador', $UsuarioModificador, PDO::PARAM_STR);
            $stmt->bindValue(':Estado', $Estado, PDO::PARAM_INT);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);

            // Ejecutar la consulta
            if ($stmt->execute()) {
                return ["success" => true];
            } else {
                return ["success" => false, "error" => "No se pudo actualizar la Bodega."];
            }
        } catch (PDOException $e) {
            // Manejo de errores de base de datos
            return ["success" => false, "error" => "Error en la base de datos: " . $e->getMessage()];
        }
    }

}