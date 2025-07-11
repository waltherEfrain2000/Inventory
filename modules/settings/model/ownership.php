<?php
class Pertenencia {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    //Método para guardar una nueva Pertenencia
    public function guardarPertenencia($nombre) {
        try {
            $creado_por=1;
            $sql = "INSERT INTO flota_pertenencia (nombre, creado_por) VALUES (:nombre, :creado_por)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':creado_por', $creado_por, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            if ($e->errorInfo[1] == 1062) { // Código de error de duplicado
                throw new PDOException(1062);
            }else {
                throw new PDOException("Error al guardar la pertenencia: " . $e->getMessage());
            }
        }
    }

    public function editarPertenencia($id, $nombre){
        try {
            $modificado_por=1;
            $sql = "UPDATE flota_pertenencia SET nombre=:nombre, modificado_por=:modificado_por, fecha_modificado=CURRENT_TIMESTAMP WHERE id=:id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':modificado_por', $modificado_por, PDO::PARAM_INT);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            if ($e->errorInfo[1] == 1062) { // Código de error de duplicado
                throw new PDOException(1062);
            }else {
                throw new PDOException("Error al actualizar la pertenencia: " . $e->getMessage());
            }
        }
    }

    //Método para eliminar una Pertenencia por ID
    public function eliminarPertenencia($id) {
        try {
            $modificado_por=1;
            $sql = "UPDATE flota_pertenencia SET estado=2, modificado_por=:modificado_por, fecha_modificado=CURRENT_TIMESTAMP WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':modificado_por', $modificado_por, PDO::PARAM_INT);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            die("Error al eliminar la pertenencia: " . $e->getMessage());
        }
    }

    //Método para obtener todas las Pertenencias
    public function obtenerPertenencias() {
        try {
            $sql = "SELECT id, nombre FROM flota_pertenencia WHERE estado=1";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener las pertenencias: " . $e->getMessage());
        }
    }
}
?>
