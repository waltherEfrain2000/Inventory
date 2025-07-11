<?php
class TipoVehiculo {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    //Método para guardar un nuevo tipo
    public function guardarTipoVehiculo($nombre) {
        try {
            $creado_por=1;
            $sql = "INSERT INTO flota_tipo_vehiculo (nombre, creado_por) VALUES (:nombre, :creado_por)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':creado_por', $creado_por, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            if ($e->errorInfo[1] == 1062) { // Código de error de duplicado
                throw new PDOException(1062);
            }else {
                throw new PDOException("Error al guardar el tipo de vehículo: " . $e->getMessage());
            }
        }
    }

    // Editar un tipo
    public function editarTipoVehiculo($id, $nombre){
        try {
            $modificado_por=1;
            $sql = "UPDATE flota_tipo_vehiculo SET nombre=:nombre, modificado_por=:modificado_por, fecha_modificado=CURRENT_TIMESTAMP WHERE id=:id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':modificado_por', $modificado_por, PDO::PARAM_INT);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            if ($e->errorInfo[1] == 1062) { // Código de error de duplicado
                throw new PDOException(1062);
            }else {
                throw new PDOException("Error al actualizar el tipo de vehículo: " . $e->getMessage());
            }
        }
    }

    //Método para eliminar un tipo por ID
    public function eliminarTipoVehiculo($id) {
        try {
            $modificado_por=1;
            $sql = "UPDATE flota_tipo_vehiculo SET estado=2, modificado_por=:modificado_por, fecha_modificado=CURRENT_TIMESTAMP WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':modificado_por', $modificado_por, PDO::PARAM_INT);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            die("Error al eliminar el tipo de vehículo: " . $e->getMessage());
        }
    }

    //Método para obtener todos los tipos
    public function obtenerTiposVehiculo() {
        try {
            $sql = "SELECT id, nombre FROM flota_tipo_vehiculo WHERE estado=1";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener los tipos de vehículo: " . $e->getMessage());
        }
    }
}
?>
