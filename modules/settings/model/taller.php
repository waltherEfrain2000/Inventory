<?php

class Taller {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    //Método para guardar un nuevo taller
    public function guardarTaller($nombre, $telefono, $ubicacion) {
        try {
            $creado_por=1;
            $sql = "INSERT INTO flota_taller (nombre, telefono, ubicacion, creado_por) VALUES (:nombre, :telefono, :ubicacion, :creado_por)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':telefono', $telefono, PDO::PARAM_STR);
            $stmt->bindParam(':ubicacion', $ubicacion, PDO::PARAM_STR);
            $stmt->bindParam(':creado_por', $creado_por, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new PDOException("Error al guardar el taller: " . $e->getMessage());
        }
    }

    // Editar taller
    public function editarTaller($id, $nombre, $telefono, $ubicacion){
        try {
            $modificado_por=1;
            $sql = "UPDATE flota_taller SET nombre=:nombre, telefono=:telefono, ubicacion=:ubicacion, modificado_por=:modificado_por, fecha_modificado=CURRENT_TIMESTAMP WHERE id=:id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':telefono', $telefono, PDO::PARAM_STR);
            $stmt->bindParam(':ubicacion', $ubicacion, PDO::PARAM_STR);
            $stmt->bindParam(':modificado_por', $modificado_por, PDO::PARAM_INT);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new PDOException("Error al actualizar el taller: " . $e->getMessage());
        }
    }

    //Método para eliminar un taller por ID
    public function eliminarTaller($id) {
        try {
            $modificado_por=1;
            $sql = "UPDATE flota_taller SET estado=2, modificado_por=:modificado_por, fecha_modificado=CURRENT_TIMESTAMP WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':modificado_por', $modificado_por, PDO::PARAM_INT);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            die("Error al eliminar el taller: " . $e->getMessage());
        }
    }

    //Método para obtener todos los talleres
    public function obtenerTalleres() {
        try {
            $sql = "SELECT id, nombre, telefono, ubicacion FROM flota_taller WHERE estado=1";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener los talleres: " . $e->getMessage());
        }
    }
}
?>
