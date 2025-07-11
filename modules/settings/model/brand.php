<?php
class Marca {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    //Método para guardar una nueva marca
    public function guardarMarca($nombre) {
        try {
            $creado_por=1;
            $sql = "INSERT INTO flota_marcas (nombre, creado_por) VALUES (:nombre, :creado_por)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':creado_por', $creado_por, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            if ($e->errorInfo[1] == 1062) { // Código de error de duplicado
                throw new PDOException(1062);
            }else {
                throw new PDOException("Error al guardar la marca: " . $e->getMessage());
            }
        }
    }

    public function editarMarca($id, $nombre){
        try {
            $modificado_por=1;
            $sql = "UPDATE flota_marcas SET nombre=:nombre, modificado_por=:modificado_por, fecha_modificado=CURRENT_TIMESTAMP WHERE id=:id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':modificado_por', $modificado_por, PDO::PARAM_INT);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            if ($e->errorInfo[1] == 1062) { // Código de error de duplicado
                throw new PDOException(1062);
            }else {
                throw new PDOException("Error al actualizar la marca: " . $e->getMessage());
            }
        }
    }

    //Método para eliminar una marca por ID
    public function eliminarMarca($id) {
        try {
            $modificado_por=1;
            $sql = "UPDATE flota_marcas SET estado=2, modificado_por=:modificado_por, fecha_modificado=CURRENT_TIMESTAMP WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':modificado_por', $modificado_por, PDO::PARAM_INT);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            die("Error al eliminar la marca: " . $e->getMessage());
        }
    }

    //Método para obtener todas las marcas
    public function obtenerMarcas() {
        try {
            $sql = "SELECT id, nombre FROM flota_marcas WHERE estado=1";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener las marcas: " . $e->getMessage());
        }
    }
}
?>
