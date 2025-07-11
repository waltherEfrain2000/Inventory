<?php
class Cliente
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    //Método para guardar una nueva Cliente
    public function guardarCliente($nombre, $rtn, $telefono, $direccion, $correo, $acopio, $precio, $creado_por)
    {
        try {
            $sql = "INSERT INTO clientes (nombre, rtn, telefono, direccion, correo, idAcopio, precioVenta, estado, creadoPor, fechaCreado)
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


            //insertar precios en clientes_historial_preciosventa
            $idCliente = $this->pdo->lastInsertId();
            $sql = "INSERT INTO clientes_historial_preciosventa (idCliente, precioVenta, estado, fechaCreado, creadoPor, sucursal)
            VALUES (:idCliente, :precio, 1, NOW(), 1, 1)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':idCliente', $idCliente, PDO::PARAM_INT);
            $stmt->bindParam(':precio', $precio, PDO::PARAM_INT);
            $stmt->execute();
            return true;



        } catch (PDOException $e) {
            die("Error al guardar la Cliente: " . $e->getMessage());
        }
    }

    //Método para editar una Cliente
    public function editarCliente($id, $nombre, $rtn, $telefono, $direccion, $correo, $acopio, $precio, $modificado_por, $tipoCambio )
    {
        try {
            $sql = "UPDATE clientes SET nombre = :nombre, rtn = :rtn, telefono = :telefono, direccion = :direccion, correo = :correo, idAcopio = :acopio, precioVenta = :precio, modificadoPor = :modificado_por, fechaModificado = NOW() WHERE idCliente = :id";
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



            // seleccionar el precio de venta anterior del cliente
            $sql = "SELECT precioVenta FROM clientes_historial_preciosventa WHERE idCliente = :idCliente AND estado = 1 ORDER BY id DESC LIMIT 1";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':idCliente', $id, PDO::PARAM_INT);
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
            $sqlDesactivar = "UPDATE clientes_historial_preciosventa 
                              SET estado = 0 
                              WHERE idCliente = :idCliente AND estado = 1";
            $stmtDesactivar = $this->pdo->prepare($sqlDesactivar);
            $stmtDesactivar->bindParam(':idCliente', $id, PDO::PARAM_INT);
            $stmtDesactivar->execute();
            // Insertar el nuevo precio para el cliente
            $sqlInsert = "INSERT INTO clientes_historial_preciosventa 
                          (idCliente, precioVenta, incremento, tipoincremento, estado, fechaCreado, creadoPor, sucursal)
                          VALUES (:idCliente, :nuevoPrecio, :incremento, :tipoincremento,  1, NOW(), :modificado_por, 1)";
            $stmtInsert = $this->pdo->prepare($sqlInsert);
            $stmtInsert->bindParam(':idCliente', $id, PDO::PARAM_INT);
            $stmtInsert->bindParam(':nuevoPrecio', $nuevoPrecio, PDO::PARAM_STR);
            $stmtInsert->bindParam(':incremento', $precio, PDO::PARAM_STR);
            $stmtInsert->bindParam(':tipoincremento', $tipoCambio, PDO::PARAM_INT);
            $stmtInsert->bindParam(':modificado_por', $modificado_por, PDO::PARAM_INT);
            $stmtInsert->execute();


            return true;

        } catch (PDOException $e) {
            die("Error al editar la Cliente: " . $e->getMessage());
        }
    }

    //Método para eliminar una Cliente por ID
    public function eliminarCliente($id)
    {
        try {
            $sql = "UPDATE clientes set estado = 0 WHERE idCliente = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();


            //actualizar el estado a 0 de clientes_historial_preciosventa
            $sql = "UPDATE clientes_historial_preciosventa SET estado = 0 WHERE idCliente = :idCliente AND estado = 1";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':idCliente', $id, PDO::PARAM_INT);
            $stmt->execute();
            return true;

        } catch (PDOException $e) {
            die("Error al eliminar la Cliente: " . $e->getMessage());
        }
    }

    //Método para obtener todas las Clientes
    public function obtenerClientes()
    {
        try {
            $sql = "SELECT c.*, chp.precioVenta as precioActual
            FROM clientes c
                    INNER JOIN clientes_historial_preciosventa chp ON c.idCliente = chp.idCliente
                    WHERE c.estado = 1 AND chp.estado = 1";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener los Clientes: " . $e->getMessage());
            return [];
        }
    }
    //Método para obtener el historial de precios de un Cliente

    public function obtenerHistorialPrecios($idCliente)
    {
        try {
            $sql = "SELECT * 
            FROM clientes_historial_preciosventa 
            WHERE idCliente = :idCliente
            ORder by id DESC
            LIMIT 5";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':idCliente', $idCliente, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener el historial de precios: " . $e->getMessage());
            return [];
        }
    }

    public function actualizarPreciosClientes($datos)
    {
        try {
            $this->pdo->beginTransaction();  // Iniciar la transacción
    
            // Obtener todos los clientes activos con su precio actual
            $sqlClientes = "SELECT idCliente, precioVenta 
                            FROM clientes_historial_preciosventa 
                            WHERE estado = 1";
            $stmt = $this->pdo->query($sqlClientes);
            $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            // Recorrer cada cliente y calcular el nuevo precio
            foreach ($clientes as $cliente) {
                $idCliente = $cliente['idCliente'];
                $precioActual = $cliente['precioVenta'];
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
                $sqlDesactivar = "UPDATE clientes_historial_preciosventa 
                                  SET estado = 0 
                                  WHERE idCliente = :idCliente AND estado = 1";
                $stmtDesactivar = $this->pdo->prepare($sqlDesactivar);
                $stmtDesactivar->bindParam(':idCliente', $idCliente, PDO::PARAM_INT);
                $stmtDesactivar->execute();
    
                // Insertar el nuevo precio para el cliente
                $sqlInsert = "INSERT INTO clientes_historial_preciosventa 
                              (idCliente, precioVenta, incremento, tipoincremento, estado, fechaCreado, creadoPor, sucursal)
                              VALUES (:idCliente, :nuevoPrecio, :incremento, :tipoincremento,  1, NOW(), 1, 1)";
                $stmtInsert = $this->pdo->prepare($sqlInsert);
                $stmtInsert->bindParam(':idCliente', $idCliente, PDO::PARAM_INT);
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
