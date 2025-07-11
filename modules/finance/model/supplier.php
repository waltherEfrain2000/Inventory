<?php
class Proveedor
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    //Método para guardar una nueva Proveedor
    public function guardarProveedor($nombre, $rtn, $telefono, $direccion, $correo, $acopio, $precio, $creado_por)
    {
        try {
            $sql = "INSERT INTO contabilidad_proveedores (nombre, rtn, telefono, direccion, correo, idAcopio, preciocompra, estado, creadoPor, fechaCreado)
            VALUES (:nombre, :rtn, :telefono, :direccion, :correo, :acopio, :precio, 1, :creado_por, NOW())";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':rtn', $rtn, PDO::PARAM_STR);
            $stmt->bindParam(':telefono', $telefono, PDO::PARAM_STR);
            $stmt->bindParam(':direccion', $direccion, PDO::PARAM_STR);
            $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
            $stmt->bindParam(':acopio', $acopio, PDO::PARAM_INT);
            $stmt->bindParam(':precio', $precio, PDO::PARAM_INT);
            $stmt->bindParam(':creado_por', $creado_por, PDO::PARAM_INT);
            $stmt->execute();


            //insertar precios en contabilidad_historial_preciocompra
            $idProveedor = $this->pdo->lastInsertId();
            $sql = "INSERT INTO contabilidad_historial_preciocompra (idProveedor, preciocompra, estado, fechaCreado, creadoPor, sucursal)
            VALUES (:idProveedor, :precio, 1, NOW(), 1, 1)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':idProveedor', $idProveedor, PDO::PARAM_INT);
            $stmt->bindParam(':precio', $precio, PDO::PARAM_INT);
            $stmt->execute();
            return true;



        } catch (PDOException $e) {
            die("Error al guardar la Proveedor: " . $e->getMessage());
        }
    }

    //Método para editar una Proveedor
    public function editarProveedor($id, $nombre, $rtn, $telefono, $direccion, $correo, $acopio, $precio, $modificado_por, $tipoCambio )
    {
        try {
            $sql = "UPDATE contabilidad_proveedores SET nombre = :nombre, rtn = :rtn, telefono = :telefono, direccion = :direccion, correo = :correo, idAcopio = :acopio, preciocompra = :precio, modificadoPor = :modificado_por, fechaModificado = NOW() WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':rtn', $rtn, PDO::PARAM_STR);
            $stmt->bindParam(':telefono', $telefono, PDO::PARAM_STR);
            $stmt->bindParam(':direccion', $direccion, PDO::PARAM_STR);
            $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);
            $stmt->bindParam(':acopio', $acopio, PDO::PARAM_INT);
            $stmt->bindParam(':precio', $precio, PDO::PARAM_INT);
            $stmt->bindParam(':modificado_por', $modificado_por, PDO::PARAM_INT);
            $stmt->execute();



            // seleccionar el precio de venta anterior del proveedor
            $sql = "SELECT preciocompra FROM contabilidad_historial_preciocompra WHERE idProveedor = :idProveedor AND estado = 1 ORDER BY id DESC LIMIT 1";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':idProveedor', $id, PDO::PARAM_INT);
            $stmt->execute();
            $precioAnterior = $stmt->fetchColumn();
            // Determinar el nuevo precio basado en tipoCambio (1: suma directa, 0: porcentaje)
            $nuevoPrecio = ($tipoCambio == 1)
                ? $precioAnterior + $precio  // Suma directa
                : $precioAnterior + ($precioAnterior * ($precio / 100));  // Incremento porcentual
            // si el precio es menor a 0, tipoCambio = 2
            if ($nuevoPrecio < 0) {
                $tipoCambio = 2;
                $nuevoPrecio = $nuevoPrecio;
            }
            // Desactivar el precio actual
            $sqlDesactivar = "UPDATE contabilidad_historial_preciocompra 
                              SET estado = 0 
                              WHERE idProveedor = :idProveedor AND estado = 1";
            $stmtDesactivar = $this->pdo->prepare($sqlDesactivar);
            $stmtDesactivar->bindParam(':idProveedor', $id, PDO::PARAM_INT);
            $stmtDesactivar->execute();
            // Insertar el nuevo precio para el proveedor
            $sqlInsert = "INSERT INTO contabilidad_historial_preciocompra 
                          (idProveedor, preciocompra, incremento, tipoincremento, estado, fechaCreado, creadoPor, sucursal)
                          VALUES (:idProveedor, :nuevoPrecio, :incremento, :tipoincremento,  1, NOW(), :modificado_por, 1)";
            $stmtInsert = $this->pdo->prepare($sqlInsert);
            $stmtInsert->bindParam(':idProveedor', $id, PDO::PARAM_INT);
            $stmtInsert->bindParam(':nuevoPrecio', $nuevoPrecio, PDO::PARAM_STR);
            $stmtInsert->bindParam(':incremento', $precio, PDO::PARAM_STR);
            $stmtInsert->bindParam(':tipoincremento', $tipoCambio, PDO::PARAM_INT);
            $stmtInsert->bindParam(':modificado_por', $modificado_por, PDO::PARAM_INT);
            $stmtInsert->execute();


            return true;

        } catch (PDOException $e) {
            die("Error al editar la Proveedor: " . $e->getMessage());
        }
    }

    //Método para eliminar una Proveedor por ID
    public function eliminarProveedor($id)
    {
        try {
            $sql = "UPDATE contabilidad_proveedores set estado = 0 WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();


            //actualizar el estado a 0 de contabilidad_historial_preciocompra
            $sql = "UPDATE contabilidad_historial_preciocompra SET estado = 0 WHERE idProveedor = :idProveedor AND estado = 1";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':idProveedor', $id, PDO::PARAM_INT);
            $stmt->execute();
            return true;

        } catch (PDOException $e) {
            die("Error al eliminar la Proveedor: " . $e->getMessage());
        }
    }

    //Método para obtener todas las Proveedores
    public function obtenerProveedores()
    {
        try {
            $sql = "SELECT c.*, chp.preciocompra as precioActual
            FROM contabilidad_proveedores c
                    INNER JOIN contabilidad_historial_preciocompra chp ON c.id = chp.idProveedor
                    WHERE c.estado = 1 AND chp.estado = 1";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener los Proveedores: " . $e->getMessage());
            return [];
        }
    }
    //Método para obtener el historial de precios de un Proveedor

    public function obtenerHistorialPrecios($idProveedor)
    {
        try {
            $sql = "SELECT * 
            FROM contabilidad_historial_preciocompra 
            WHERE idProveedor = :idProveedor
            ORDER by id DESC
            LIMIT 5";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':idProveedor', $idProveedor, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener el historial de precios: " . $e->getMessage());
            return [];
        }
    }

    public function actualizarPreciosProveedores($datos)
    {
        try {
            $this->pdo->beginTransaction();  // Iniciar la transacción
    
            // Obtener todos los proveedores activos con su precio actual
            $sqlProveedores = "SELECT idProveedor, preciocompra 
                            FROM contabilidad_historial_preciocompra 
                            WHERE estado = 1";
            $stmt = $this->pdo->query($sqlProveedores);
            $proveedores = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            // Recorrer cada proveedor y calcular el nuevo precio
            foreach ($proveedores as $proveedor) {
                $idProveedor = $proveedor['idProveedor'];
                $precioActual = $proveedor['preciocompra'];
                $tipoCambio = $datos["tipoCambio"];
                // Determinar el nuevo precio basado en tipoCambio (1: suma directa, 0: porcentaje)
                $nuevoPrecio = ($tipoCambio == 1)
                    ? $precioActual + $datos["precio"]  // Suma directa
                    : $precioActual + ($precioActual * ($datos["precio"] / 100));  // Incremento porcentual
                // si el precio es menor a 0, tipoCambio = 2
                if ($nuevoPrecio < 0) {
                    $tipoCambio = 2;
                    $nuevoPrecio = $nuevoPrecio;
                }
    
                // Desactivar el precio actual
                $sqlDesactivar = "UPDATE contabilidad_historial_preciocompra 
                                  SET estado = 0 
                                  WHERE idProveedor = :idProveedor AND estado = 1";
                $stmtDesactivar = $this->pdo->prepare($sqlDesactivar);
                $stmtDesactivar->bindParam(':idProveedor', $idProveedor, PDO::PARAM_INT);
                $stmtDesactivar->execute();
    
                // Insertar el nuevo precio para el proveedor
                $sqlInsert = "INSERT INTO contabilidad_historial_preciocompra 
                              (idProveedor, preciocompra, incremento, tipoincremento, estado, fechaCreado, creadoPor, sucursal)
                              VALUES (:idProveedor, :nuevoPrecio, :incremento, :tipoincremento,  1, NOW(), 1, 1)";
                $stmtInsert = $this->pdo->prepare($sqlInsert);
                $stmtInsert->bindParam(':idProveedor', $idProveedor, PDO::PARAM_INT);
                $stmtInsert->bindParam(':nuevoPrecio', $nuevoPrecio, PDO::PARAM_STR);
                $stmtInsert->bindParam(':incremento', $datos["precio"], PDO::PARAM_STR);
                $stmtInsert->bindParam(':tipoincremento', $tipoCambio, PDO::PARAM_INT);
                $stmtInsert->execute();
            }
    
            $this->pdo->commit();  // Confirmar la transacción
            return true;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            error_log("Error al actualizar los precios: " . $e->getMessage());
            return false;
        }
    }
    

}
