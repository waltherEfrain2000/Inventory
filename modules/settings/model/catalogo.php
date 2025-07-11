<?php
class Catalogo {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    //Método para guardar un nuevo catálogo
    public function guardarCatalogo($nombre, $id_tipo_mantenimiento) {
        try {
            $creado_por=1;
            $sql = "INSERT INTO flota_catalogo (nombre, id_tipo_mantenimiento, creado_por) VALUES (:nombre, :id_tipo_mantenimiento, :creado_por)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':id_tipo_mantenimiento', $id_tipo_mantenimiento, PDO::PARAM_INT);
            $stmt->bindParam(':creado_por', $creado_por, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new PDOException("Error al guardar el registro: " . $e->getMessage());
        }
    }

    // Método para editar un catálogo
    public function editarCatalogo($id, $nombre, $id_tipo_mantenimiento){
        try {
            $modificado_por=1;
            $sql = "UPDATE flota_catalogo SET nombre=:nombre, id_tipo_mantenimiento=:id_tipo_mantenimiento, modificado_por=:modificado_por, fecha_modificado=CURRENT_TIMESTAMP WHERE id=:id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':id_tipo_mantenimiento', $id_tipo_mantenimiento, PDO::PARAM_INT);
            $stmt->bindParam(':modificado_por', $modificado_por, PDO::PARAM_INT);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new PDOException("Error al actualizar el registro: " . $e->getMessage());
        }
    }

    //Método para eliminar un catálogo por ID
    public function eliminarCatalogo($id) {
        try {
            $modificado_por=1;
            $sql = "UPDATE flota_catalogo SET estado=2, modificado_por=:modificado_por, fecha_modificado=CURRENT_TIMESTAMP WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':modificado_por', $modificado_por, PDO::PARAM_INT);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            die("Error al eliminar el registro: " . $e->getMessage());
        }
    }

    //Método para obtener todos los catálogos
    public function obtenerCatalogos() {
        try {
            $sql = "SELECT cat.id, cat.nombre, cat.id_tipo_mantenimiento, tm.nombre as tipoMantenimiento FROM flota_catalogo AS cat 
                    INNER JOIN flota_tipo_mantenimiento tm ON tm.id=cat.id_tipo_mantenimiento
                    WHERE cat.estado=1";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener el catálogo: " . $e->getMessage());
        }
    }

    //Método para obtener todos los catálogos
    public function obtenerTiposMantenimiento() {
        try {
            $sql = "SELECT id, nombre FROM flota_tipo_mantenimiento WHERE estado = 1";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener los tipos de mantenimiento: " . $e->getMessage());
        }
    }
}
?>
