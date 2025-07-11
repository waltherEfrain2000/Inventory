<?php
class Modelo {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    //Método para guardar un nuevo modelo
    public function guardarModelo($nombre, $id_marca) {
        try {
            $creado_por=1;
            $sql = "INSERT INTO flota_modelos (nombre, id_marca, creado_por) VALUES (:nombre, :id_marca, :creado_por)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':id_marca', $id_marca, PDO::PARAM_INT);
            $stmt->bindParam(':creado_por', $creado_por, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new PDOException("Error al guardar el modelo: " . $e->getMessage());
        }
    }

    // Método para editar un modelo
    public function editarModelo($id, $nombre, $id_marca){
        try {
            $modificado_por=1;
            $sql = "UPDATE flota_modelos SET nombre=:nombre, id_marca=:id_marca, modificado_por=:modificado_por, fecha_modificado=CURRENT_TIMESTAMP WHERE id=:id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':id_marca', $id_marca, PDO::PARAM_INT);
            $stmt->bindParam(':modificado_por', $modificado_por, PDO::PARAM_INT);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new PDOException("Error al actualizar el modelo: " . $e->getMessage());
        }
    }

    //Método para eliminar un modelo por ID
    public function eliminarModelo($id) {
        try {
            $modificado_por=1;
            $sql = "UPDATE flota_modelos SET estado=2, modificado_por=:modificado_por, fecha_modificado=CURRENT_TIMESTAMP WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':modificado_por', $modificado_por, PDO::PARAM_INT);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            die("Error al eliminar el modelo: " . $e->getMessage());
        }
    }

    //Método para obtener todas los Modelos
    public function obtenerModelos() {
        try {
            $sql = "SELECT mo.id, mo.nombre, mo.id_marca, ma.nombre as marca FROM flota_modelos AS mo 
                    INNER JOIN flota_marcas ma ON ma.id=mo.id_marca
                    WHERE mo.estado=1";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener los modelos: " . $e->getMessage());
        }
    }
}
?>
