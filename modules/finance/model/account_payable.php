<?php
class CuentasPorPagar
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function cargarProveedores()
    {
        try {
            $sql = "SELECT  id as id,
                            nombre 
                    FROM mddesarr_fptrax.contabilidad_proveedores 
                    WHERE estado=1;";

            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener los proveedors/productores: " . $e->getMessage());
        }
    }

    public function cargarEstado()
    {
        try {
            $sql = "SELECT * FROM mddesarr_fptrax.contabilidad_estados;";

            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener los proveedors/productores: " . $e->getMessage());
        }
    }

    public function cargarMetodosPagos()
    {
        try {
            $sql = "SELECT * FROM mddesarr_fptrax.contabilidad_metodosPago;";

            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener los proveedors/productores: " . $e->getMessage());
        }
    }


    public function agregarPago($losDatos)
    {
        try {
            $sql = "INSERT INTO mddesarr_fptrax.contabilidad_cxp_anticipos
                    (idProveedor, noDocumento, fechaEmision, estado, monto, creadoPor, observaciones)
                    VALUES (:idProveedor, :noDocumento, :fechaEmision, :estado, :monto, :creadoPor, :observaciones)";
    
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':idProveedor'   => $losDatos->proveedor,
                ':noDocumento'   => $losDatos->numeroDocumento,
                ':fechaEmision'  => $losDatos->fechaEmision,
                ':estado'        => 1,
                ':monto'         => $losDatos->monto,
                ':creadoPor'     => 1, 
                ':observaciones' => $losDatos->observaciones
            ]);
    
            return ["success" => true, "message" => "Anticipo guardado correctamente"];
        } catch (PDOException $e) {
            return ["success" => false, "error" => "Error al insertar el anticipo: " . $e->getMessage()];
        }
    }
    
    public function actualizarPago($losDatos)
    {
   
        try {
            $sql = "UPDATE mddesarr_fptrax.contabilidad_cxp_anticipos
                    SET 
                        idProveedor   = :idProveedor,
                        noDocumento   = :noDocumento,
                        fechaEmision  = :fechaEmision,
                        estado        = :estado,
                        monto         = :monto,
                        creadoPor     = :creadoPor,
                        observaciones = :observaciones
                    WHERE id = :idAnticipo";
    
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':idProveedor'   => $losDatos->proveedor,
                ':noDocumento'   => $losDatos->numeroDocumento,
                ':fechaEmision'  => $losDatos->fechaEmision,
                ':estado'        => 1,
                ':monto'         => $losDatos->monto,
                ':creadoPor'     => 1,
                ':observaciones' => $losDatos->observaciones,
                ':idAnticipo'    => $losDatos->id 
            ]);
    
            return ["success" => true, "message" => "Anticipo actualizado correctamente"];
        } catch (PDOException $e) {
            return ["success" => false, "error" => "Error al actualizar el anticipo: " . $e->getMessage()];
        }
    }

    public function eliminarPago($id)
    {
        try {
            $sql = "UPDATE mddesarr_fptrax.contabilidad_cxp_anticipos
                    SET estado = :estado
                    WHERE id = :idAnticipo";
    
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':estado'     => 2,
                ':idAnticipo' => $id
            ]);
    
            return ["success" => true, "message" => "Anticipo actualizado correctamente"];
        } catch (PDOException $e) {
            return ["success" => false, "error" => "Error al actualizar el anticipo: " . $e->getMessage()];
        }
    }
    
    
    
    public function agregarAbono($losDatos)
    {
        try {
            $sql = "INSERT INTO mddesarr_fptrax.contabilidad_pagosCXP
                    (idDocumento, monto, metodoPago, fechaAbono, creadoPor, fechaCreado) 
                    VALUES (:idDocumento, :monto, :metodoPago, :fechaAbono, :creadoPor, NOW())";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':idDocumento' => $losDatos->idDocumento,
                ':monto'       => $losDatos->monto,
                ':metodoPago'  => $losDatos->metodoPago,
                ':fechaAbono'  => $losDatos->fecha,
                ':creadoPor'   => 1
            ]);

            return ["success" => true, "message" => "Abono registrado correctamente"];
        } catch (PDOException $e) {
            return ["success" => false, "error" => "Error al insertar el abono: " . $e->getMessage()];
        }
    }

    public function cargarCuentasPorPagar()
    {
        try {
            $sql = "SELECT a.id, p.id as idProveedor,p.nombre AS proveedor,a.noDocumento,a.monto,a.observaciones,a.fechaEmision
                    FROM mddesarr_fptrax.contabilidad_cxp_anticipos a
                    INNER JOIN mddesarr_fptrax.contabilidad_proveedores p ON p.id=a.idProveedor
                    WHERE a.estado=1";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener las cuentas por cobrar: " . $e->getMessage());
        }
    }
    public function cargarCuentasPorPagarID($idProveedor)
    {
        try {
            $sql = "SELECT 
                        a.id, 
                        p.id AS idProveedor, 
                        p.nombre AS proveedor, 
                        a.noDocumento, 
                        a.monto, 
                        a.observaciones, 
                        a.fechaEmision
                    FROM mddesarr_fptrax.contabilidad_cxp_anticipos a
                    INNER JOIN mddesarr_fptrax.contabilidad_proveedores p ON p.id = a.idProveedor
                    WHERE a.estado = 1 AND a.idProveedor = :idProveedor";
    
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':idProveedor' => $idProveedor]);
    
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener las cuentas por pagar: " . $e->getMessage());
        }
    }
    public function cargarCuentasPagadasID($idProveedor)
    {
        try {
            $sql = "SELECT 
                        a.id, 
                        p.id AS idProveedor, 
                        p.nombre AS proveedor, 
                        a.noDocumento, 
                        a.monto, 
                        a.observaciones, 
                        a.fechaEmision
                    FROM mddesarr_fptrax.contabilidad_cxp_anticipos a
                    INNER JOIN mddesarr_fptrax.contabilidad_proveedores p ON p.id = a.idProveedor
                    WHERE a.estado = 1 AND a.idProveedor = :idProveedor";
    
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':idProveedor' => $idProveedor]);
    
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener las cuentas por pagar: " . $e->getMessage());
        }
    }

    public function cargarPagos($idDocumento)
    {
        try {
            $sql = "SELECT a.id,monto,metodoPago,DATE(a.fechaAbono) AS fechaAbono,mp.id as idMetodoPago,mp.descripcion as metodoPago 
                    FROM mddesarr_fptrax.contabilidad_pagosCXP a
                    INNER JOIN mddesarr_fptrax.contabilidad_metodosPago mp on mp.id=a.metodoPago
                    WHERE a.idDocumento = :idDocumento AND a.estado=1";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':idDocumento', $idDocumento, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener los abonos: " . $e->getMessage());
        }
    }

    public function anularAbono($id)
    {
        try {
            $sql = "UPDATE mddesarr_fptrax.contabilidad_pagosCXP
                    SET estado = 2
                    WHERE id = :id";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ✅ Se corrigió el parámetro
            $stmt->execute();

            return ["success" => true, "message" => "Abono anulado correctamente"];
        } catch (PDOException $e) {
            return ["success" => false, "error" => "Error al anular el abono: " . $e->getMessage()];
        }
    }


    public function cargarDocumento($id)
    {
        try {
            // Consulta segura con parámetro
            $sql = "SELECT * 
                    FROM mddesarr_fptrax.contabilidad_cxp_anticipos
                    WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener el documento: " . $e->getMessage());
        }
    }

    public function montoDisponible($id)
    {
        try {
            $sql = "SELECT 
                        COALESCE(
                            (SELECT SUM(monto) 
                             FROM mddesarr_fptrax.contabilidad_cxp_anticipos 
                             WHERE idProveedor = :idProveedor AND estado = 1), 0
                        ) 
                        -
                        COALESCE(
                            (SELECT SUM(monto) 
                             FROM mddesarr_fptrax.ventas_directas 
                             WHERE idProveedor = :idProveedor AND estado = 1), 0
                        ) AS montoDisponible";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':idProveedor', $id, PDO::PARAM_INT);
            $stmt->execute();
    
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener el documento: " . $e->getMessage());
        }
    }

    public function montoAnticipado($id)
    {
        try {
            $sql = "SELECT COALESCE( SUM(monto),0 ) AS anticipado
                    FROM mddesarr_fptrax.contabilidad_cxp_anticipos 
                    WHERE idProveedor = :idProveedor AND estado = 1";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':idProveedor', $id, PDO::PARAM_INT);
            $stmt->execute();
    
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener el documento: " . $e->getMessage());
        }
    }
    public function montoPagado($id)
    {
        try {
            $sql = "SELECT COALESCE( SUM(monto),0 ) AS pagado
                    FROM mddesarr_fptrax.ventas_directas 
                    WHERE idProveedor = :idProveedor AND estado = 1";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':idProveedor', $id, PDO::PARAM_INT);
            $stmt->execute();
    
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener el documento: " . $e->getMessage());
        }
    }
    
}
