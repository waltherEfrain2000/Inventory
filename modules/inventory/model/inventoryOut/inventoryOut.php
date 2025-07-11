<?php
class InventoryOut
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getinventoryOut()
    {
        try {
            $sql = "SELECT isi.`Id`, isi.`Estado`, isi.`FechaCreacion`, isi.`FechaModificacion`,
            isi.`IdCliente`, isi.`ImpuestoSalida`,isi.`UsuarioCreador`, isi.`Comentarios`, isi.`TipoSalida`,
            isi.`TotalSalida`,
            cc.`Nombre` as NombreCliente, isi.`Financiado`, isi.PagoCompleto 
             FROM inventario_SalidaInventario  as isi
            Left JOIN contabilidad_clientes as cc ON cc.id = isi.`IdCliente`
            WHERE isi.Estado = 1 ORDER BY isi.FechaCreacion DESC";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener las salidas de inventario: " . $e->getMessage());
        }
    }

    public function getinventoryHistoryOuts()
    {
        try {
            $sql = "SELECT * FROM inventario_vw_HistorialSalidaBodegas";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener las salidas de inventario: " . $e->getMessage());
        }
    }
    public function getinventoryOutFromJobOrders()
    {
        try {
            $sql = "SELECT  isv.`FechaCreacion`, isv.`NombreArticulo`, fv.placa, ftv.nombre as TipoVehiculo, isv.`CantidadSalida`  FROM `inventario_vw_Salidas` as isv
            INNER JOIN flota_mantenimiento as fm ON fm.id = isv.id_mantenimiento
            INNER JOIN flota_vehiculos as fv ON fv.id = fm.id_vehiculo
            INNER JOIN flota_tipo_vehiculo as ftv ON ftv.id = fv.id_tipo_vehiculo";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener las salidas de inventario: " . $e->getMessage());
        }
    }
    public function getClients()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM contabilidad_clientes WHERE estado = 1 ORDER BY nombre ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getEntregas()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM inventario_vw_Salidas WHERE id_mantenimiento IS NULL");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProductStock($id)
    {
        $stmt = $this->pdo->prepare("
        SELECT 
            b.id AS idBodega,
            b.NombreBodega,
            SUM(i.Existencia) AS Existencias,
            a.PrecioVenta
        FROM inventario_vw_InventarioActual i
        INNER JOIN inventario_Bodegas b ON b.id = i.idBodega
        INNER JOIN inventario_Articulos a ON a.id = i.idArticulo
        WHERE i.idArticulo = :id
        GROUP BY b.id, b.NombreBodega, a.PrecioVenta
        HAVING Existencias > 0
    ");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $bodegas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Calcular total de existencias
        $totalExistencias = 0;
        foreach ($bodegas as $bodega) {
            $totalExistencias += $bodega['Existencias'];
        }

        return [
            "bodegas" => $bodegas,
            "totalExistencias" => $totalExistencias
        ];
    }


    public function saveInventoryOut(
        $UsuarioCreador,
        $TipoSalida,
        $idCliente,
        $Comentarios,
        $TotalSalida,
        $ImpuestoSalida,
        $detalles,
        $EsCredito = false,
        $FechaVencimiento = null
    ) {
        try {
            $this->pdo->beginTransaction();

            // 1. Insertar encabezado de salida
            $sqlSalida = "INSERT INTO inventario_SalidaInventario (
                            Estado, UsuarioCreador, FechaCreacion,
                            TipoSalida, IdCliente,
                            TotalSalida, ImpuestoSalida, Comentarios, Financiado
                        ) VALUES (
                            1, :UsuarioCreador, NOW(),
                            :TipoSalida, :idCliente,
                            :TotalSalida, :ImpuestoSalida, :Comentarios, :Financiado
                        )";

            $stmtSalida = $this->pdo->prepare($sqlSalida);
            $stmtSalida->bindParam(':UsuarioCreador', $UsuarioCreador, PDO::PARAM_INT);
            $stmtSalida->bindParam(':TipoSalida', $TipoSalida, PDO::PARAM_INT);
            $stmtSalida->bindParam(':idCliente', $idCliente, PDO::PARAM_INT);
            $stmtSalida->bindParam(':TotalSalida', $TotalSalida, PDO::PARAM_STR);
            $stmtSalida->bindParam(':ImpuestoSalida', $ImpuestoSalida, PDO::PARAM_STR);
            $stmtSalida->bindParam(':Comentarios', $Comentarios, PDO::PARAM_STR);
            $stmtSalida->bindParam(':Financiado', $EsCredito, PDO::PARAM_STR);
            $stmtSalida->execute();

            $idSalida = $this->pdo->lastInsertId();

            // 2. Insertar detalles de la salida
            $sqlDetalle = "INSERT INTO inventario_SalidaDetalle (
                            idSalida, idBodega, idArticulo,
                            CantidadSalida, Estado, UsuarioCreador, FechaCreacion,
                            PrecioSalida
                        ) VALUES (
                            :idSalida, :idBodega, :idArticulo,
                            :CantidadSalida, 1, :UsuarioCreador, NOW(),
                            :PrecioSalida
                        )";

            $stmtDetalle = $this->pdo->prepare($sqlDetalle);

            foreach ($detalles as $detalle) {
                $stmtDetalle->bindParam(':idSalida', $idSalida, PDO::PARAM_INT);
                $stmtDetalle->bindParam(':idBodega', $detalle['idBodega'], PDO::PARAM_INT);
                $stmtDetalle->bindParam(':idArticulo', $detalle['idArticulo'], PDO::PARAM_INT);
                $stmtDetalle->bindParam(':CantidadSalida', $detalle['cantidad'], PDO::PARAM_STR);
                $stmtDetalle->bindParam(':UsuarioCreador', $UsuarioCreador, PDO::PARAM_INT);
                $stmtDetalle->bindParam(':PrecioSalida', $detalle['PrecioUnitario'], PDO::PARAM_STR); // o PrecioSalida
                $stmtDetalle->execute();
            }



            if ($EsCredito) {

                $sqlDocumento = "INSERT INTO contabilidad_documentos (
                            idCliente, noDocumento, fechaEmision, fechaVencimiento,
                            monto, creadoPor,observaciones, estado
                        ) VALUES (
                             :idCliente,:noDocumento,  NOW(),
                            :fechaVencimiento, :monto,
                            :creadoPor, :observaciones, :estado
                        )";

                $stmtDocumento = $this->pdo->prepare($sqlDocumento);
                $stmtDocumento->bindParam(':idCliente', $idCliente, PDO::PARAM_INT);
                $stmtDocumento->bindParam(':noDocumento', $idSalida, PDO::PARAM_STR);
                $stmtDocumento->bindParam(':fechaVencimiento', $FechaVencimiento, PDO::PARAM_STR);
                $stmtDocumento->bindParam(':monto', $TotalSalida, PDO::PARAM_INT);
                $stmtDocumento->bindParam(':creadoPor', $UsuarioCreador, PDO::PARAM_STR);
                $observaciones = "Generado desde salidas de inventario.";
                $stmtDocumento->bindParam(':observaciones', $observaciones, PDO::PARAM_STR);
                $estado = 1;
                $stmtDocumento->bindParam(':estado', $estado, PDO::PARAM_STR);
                $stmtDocumento->execute();
                
            }
            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            die("Error al guardar la salida: " . $e->getMessage());
        }
    }

    public function updateInventoryOut(
        $id,
        $UsuarioCreador,
        $TipoSalida,
        $idCliente,
        $Comentarios,
        $TotalSalida,
        $ImpuestoSalida,
        $detalles
    ) {
        try {
            $this->pdo->beginTransaction();

            // 1. Actualizar encabezado
            $sqlUpdateSalida = "UPDATE inventario_SalidaInventario
                            SET Estado = 1, UsuarioModificador = :UsuarioCreador,
                                FechaModificacion = NOW(), TipoSalida = :TipoSalida,
                                IdCliente = :idCliente, Comentarios = :Comentarios,
                                TotalSalida = :TotalSalida, ImpuestoSalida = :ImpuestoSalida
                            WHERE id = :id";

            $stmtUpdateSalida = $this->pdo->prepare($sqlUpdateSalida);
            $stmtUpdateSalida->bindParam(':id', $id, PDO::PARAM_INT);
            $stmtUpdateSalida->bindParam(':UsuarioCreador', $UsuarioCreador, PDO::PARAM_INT);
            $stmtUpdateSalida->bindParam(':TipoSalida', $TipoSalida, PDO::PARAM_INT);
            if (empty($idCliente)) {
                $idCliente = null;
            }
            // Bind null value for idCliente if it's empty

            $stmtUpdateSalida->bindParam(':idCliente', $idCliente, $idCliente === null ? PDO::PARAM_NULL : PDO::PARAM_INT);

            $stmtUpdateSalida->bindParam(':Comentarios', $Comentarios, PDO::PARAM_STR);
            $stmtUpdateSalida->bindParam(':TotalSalida', $TotalSalida, PDO::PARAM_STR);
            $stmtUpdateSalida->bindParam(':ImpuestoSalida', $ImpuestoSalida, PDO::PARAM_STR);
            $stmtUpdateSalida->execute();

            // 2. Marcar detalles anteriores como inactivos
            $sqlUpdateDetalle = "UPDATE inventario_SalidaDetalle
                             SET Estado = 0
                             WHERE idSalida = :idSalida";
            $stmtUpdateDetalle = $this->pdo->prepare($sqlUpdateDetalle);
            $stmtUpdateDetalle->bindParam(':idSalida', $id, PDO::PARAM_INT);
            $stmtUpdateDetalle->execute();

            // 3. Insertar o actualizar detalles
            foreach ($detalles as $detalle) {
                $sqlSelect = "SELECT id FROM inventario_SalidaDetalle
                          WHERE idSalida = :idSalida AND idArticulo = :idArticulo AND idBodega = :idBodega";
                $stmtSelect = $this->pdo->prepare($sqlSelect);
                $stmtSelect->bindParam(':idSalida', $id, PDO::PARAM_INT);
                $stmtSelect->bindParam(':idArticulo', $detalle['idArticulo'], PDO::PARAM_INT);
                $stmtSelect->bindParam(':idBodega', $detalle['idBodega'], PDO::PARAM_INT);
                $stmtSelect->execute();

                if ($stmtSelect->rowCount() > 0) {
                    // Ya existe el detalle, reactívalo y actualiza
                    $sqlUpdate = "UPDATE inventario_SalidaDetalle
                              SET CantidadSalida = :CantidadSalida, Estado = 1,
                                  UsuarioModificador = :UsuarioModificador, FechaModificacion = NOW(),
                                  PrecioSalida = :PrecioSalida
                              WHERE idSalida = :idSalida AND idArticulo = :idArticulo AND idBodega = :idBodega";
                    $stmtUpdate = $this->pdo->prepare($sqlUpdate);
                    $stmtUpdate->bindParam(':idSalida', $id, PDO::PARAM_INT);
                    $stmtUpdate->bindParam(':idArticulo', $detalle['idArticulo'], PDO::PARAM_INT);
                    $stmtUpdate->bindParam(':idBodega', $detalle['idBodega'], PDO::PARAM_INT);
                    $stmtUpdate->bindParam(':CantidadSalida', $detalle['cantidad'], PDO::PARAM_STR);
                    $stmtUpdate->bindParam(':UsuarioModificador', $UsuarioCreador, PDO::PARAM_INT);
                    $stmtUpdate->bindParam(':PrecioSalida', $detalle['PrecioUnitario'], PDO::PARAM_STR);
                    $stmtUpdate->execute();
                } else {
                    // Insertar nuevo detalle
                    $sqlInsert = "INSERT INTO inventario_SalidaDetalle (
                                idSalida, idBodega, idArticulo, CantidadSalida, Estado,
                                UsuarioCreador, FechaCreacion, PrecioSalida
                              ) VALUES (
                                :idSalida, :idBodega, :idArticulo, :CantidadSalida, 1,
                                :UsuarioCreador, NOW(), :PrecioSalida
                              )";
                    $stmtInsert = $this->pdo->prepare($sqlInsert);
                    $stmtInsert->bindParam(':idSalida', $id, PDO::PARAM_INT);
                    $stmtInsert->bindParam(':idBodega', $detalle['idBodega'], PDO::PARAM_INT);
                    $stmtInsert->bindParam(':idArticulo', $detalle['idArticulo'], PDO::PARAM_INT);
                    $stmtInsert->bindParam(':CantidadSalida', $detalle['cantidad'], PDO::PARAM_STR);
                    $stmtInsert->bindParam(':UsuarioCreador', $UsuarioCreador, PDO::PARAM_INT);
                    $stmtInsert->bindParam(':PrecioSalida', $detalle['PrecioUnitario'], PDO::PARAM_STR);
                    $stmtInsert->execute();
                }
            }

            $this->pdo->commit();

            // Consultar nuevamente el encabezado y los detalles
            $encabezado = $this->getOutHeader($id);
            $detalles = $this->getOutDetail($id);

            // Devolver el objeto actualizado
            return [
                "encabezado" => $encabezado,
                "detalles" => $detalles
            ];
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            die("Error al actualizar la salida: " . $e->getMessage());
        }
    }


    public function getOutHeader($id)
    {
        try {
            $sql = "SELECT * FROM inventario_SalidaInventario WHERE id = :id AND Estado = 1";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener el encabezado de salida " . $e->getMessage());
        }
    }
    public function getOutDetail($id)
    {

        try {
            $sql = "SELECT * FROM inventario_SalidaDetalle WHERE idSalida= :id AND Estado = 1";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener el detalle de salida " . $e->getMessage());
        }
    }
}
