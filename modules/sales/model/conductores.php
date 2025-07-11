<?php
class Conductor
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    //Método para guardar una nueva Conductor
    public function guardarConductor($nombre, $rtn, $telefono, $direccion, $acopio, $creado_por)
    {
        try {
            $sql = "INSERT INTO ventas_configuracion_conductores (nombre, identificacion, cel, direccion, sucursal, estado, usuarioCreado, fechaCreado)
            VALUES (:nombre, :rtn, :telefono, :direccion, :acopio, 1, :creado_por, NOW())";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':rtn', $rtn, PDO::PARAM_STR);
            $stmt->bindParam(':telefono', $telefono, PDO::PARAM_STR);
            $stmt->bindParam(':direccion', $direccion, PDO::PARAM_STR);
            $stmt->bindParam(':acopio', $acopio, PDO::PARAM_INT);
            $stmt->bindParam(':creado_por', $creado_por, PDO::PARAM_INT);
            $stmt->execute();

            return true;



        } catch (PDOException $e) {
            die("Error al guardar la Conductor: " . $e->getMessage());
        }
    }

    //Método para editar una Conductor
    public function editarConductor($id, $nombre, $rtn, $telefono, $direccion, $acopio, $modificado_por )
    {
        try {
            $sql = "UPDATE ventas_configuracion_conductores SET nombre = :nombre, identificacion = :rtn, cel = :telefono, direccion = :direccion, sucursal = :acopio, usuarioModificado = :modificado_por, fechaModificado = NOW() WHERE idConductor = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':rtn', $rtn, PDO::PARAM_STR);
            $stmt->bindParam(':telefono', $telefono, PDO::PARAM_STR);
            $stmt->bindParam(':direccion', $direccion, PDO::PARAM_STR);
            $stmt->bindParam(':acopio', $acopio, PDO::PARAM_INT);
            $stmt->bindParam(':modificado_por', $modificado_por, PDO::PARAM_INT);
            $stmt->execute();

            return true;

        } catch (PDOException $e) {
            die("Error al editar la Conductor: " . $e->getMessage());
        }
    }

    //Método para eliminar una Conductor por ID
    public function eliminarConductor($id)
    {
        try {
            $sql = "UPDATE ventas_configuracion_conductores set estado = 0 WHERE idConductor = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            return true;

        } catch (PDOException $e) {
            die("Error al eliminar la Conductor: " . $e->getMessage());
        }
    }

    //Método para obtener todas las Conductorer
    public function obtenerConductor()
    {
        try {
            $sql = "SELECT * from ventas_configuracion_conductores WHERE estado = 1";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener los Conductorer: " . $e->getMessage());
            return [];
        }
    }

    

}
