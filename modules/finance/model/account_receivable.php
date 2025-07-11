<?php
class CuentasPorCobrar
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function cargarClientes()
    {
        try {
            $sql = "SELECT  idProductor as id,
                            nombre, 
                            identificacion 
                    FROM mddesarr_fptrax.compras_productores 
                    WHERE estado=1;";

            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener los clientes/productores: " . $e->getMessage());
        }
    }

    public function cargarEstado()
    {
        try {
            $sql = "SELECT * FROM mddesarr_fptrax.contabilidad_estados;";

            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener los clientes/productores: " . $e->getMessage());
        }
    }

    public function cargarMetodosPagos()
    {
        try {
            $sql = "SELECT * FROM mddesarr_fptrax.contabilidad_metodosPago;";

            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener los clientes/productores: " . $e->getMessage());
        }
    }


    public function agregarPago($losDatos)
    {
        try {
            $sql = "INSERT INTO mddesarr_fptrax.contabilidad_documentos 
                    (idCliente, noDocumento, fechaEmision, fechaVencimiento, estado, monto, creadoPor, fechaCreado, observaciones) 
                    VALUES (:idCliente, :noDocumento, :fechaEmision, :fechaVencimiento, :estado, :monto, :creadoPor, NOW(), :observaciones)";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':idCliente'       => $losDatos->cliente,
                ':noDocumento'     => $losDatos->numeroDocumento,
                ':fechaEmision'    => $losDatos->fechaEmision,
                ':fechaVencimiento' => $losDatos->fechaVencimiento,
                ':estado'          => 1,
                ':monto'           => $losDatos->monto,
                ':creadoPor'       => 1,
                ':observaciones'   => $losDatos->observaciones
            ]);

            return ["success" => true, "message" => "Pago registrado correctamente"];
        } catch (PDOException $e) {
            return ["success" => false, "error" => "Error al insertar el pago: " . $e->getMessage()];
        }
    }

    public function agregarAbono($losDatos)
    {
        try {
            $sql = "INSERT INTO mddesarr_fptrax.contabilidad_abonos 
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

    public function cargarCuentasPorCobrar()
    {
        try {
            // Ejecutar el procedimiento almacenado antes de obtener los datos
            $this->pdo->exec("CALL contabilidad_sp_actualizarEstadosDocumentos();");

            // Ahora consulta la vista con los datos actualizados
            $sql = "SELECT * FROM mddesarr_fptrax.contabilidad_vw_documentos;";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener las cuentas por cobrar: " . $e->getMessage());
        }
    }

    public function cargarAbonos($idDocumento)
    {
        try {
            $sql = "SELECT a.id,monto,metodoPago,DATE(a.fechaAbono) AS fechaAbono,mp.id as idMetodoPago,mp.descripcion as metodoPago 
                    FROM mddesarr_fptrax.contabilidad_abonos a
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
            $sql = "UPDATE mddesarr_fptrax.contabilidad_abonos
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
            $sql = "SELECT * , (monto - COALESCE((SELECT SUM(a.monto)
                                        FROM contabilidad_abonos a
                                        WHERE (a.idDocumento = d.id AND a.estado=1)),0)) AS saldoPendiente
                    FROM mddesarr_fptrax.contabilidad_documentos d 
                    WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener el documento: " . $e->getMessage());
        }
    }

    /* Cards */

    public function obtenerPendienteCobro()
    {
        try {
            $sql = "SELECT COALESCE(SUM(saldoPendiente), 0) AS pendienteCobro
                FROM mddesarr_fptrax.contabilidad_vw_cxc_pagosPendientesPago";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC)['pendienteCobro'];
        } catch (PDOException $e) {
            die("Error al obtener pendiente por cobrar: " . $e->getMessage());
        }
    }

    public function obtenerVencenMes()
    {
        try {
            $sql = "SELECT COALESCE(COUNT(monto), 0) AS vencenMes
                FROM mddesarr_fptrax.contabilidad_vw_cxc_pagosVencenEsteMes";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC)['vencenMes'];
        } catch (PDOException $e) {
            die("Error al obtener pagos que vencen este mes: " . $e->getMessage());
        }
    }
    public function obtenerSaldoVencido()
    {
        try {
            $sql = "SELECT COALESCE(SUM(saldoPendiente),0) AS saldoVencido 
                FROM mddesarr_fptrax.contabilidad_vw_cxc_pagosVencidos";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC)['saldoVencido'];
        } catch (PDOException $e) {
            die("Error al obtener saldo vencido: " . $e->getMessage());
        }
    }
    public function obtenerAbonosMetodoActual()
    {
        try {
            $sql = "SELECT COALESCE(SUM(monto),0) AS abonosMetodoActual
                FROM mddesarr_fptrax.contabilidad_vw_cxc_abonosMesActual";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC)['abonosMetodoActual'];
        } catch (PDOException $e) {
            die("Error al obtener abonos del mes actual: " . $e->getMessage());
        }
    }
}
