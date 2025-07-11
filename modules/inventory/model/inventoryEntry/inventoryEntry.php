<?php
class InventoryEntry
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getinventoryEntry()
    {
        try {
            $sql = "SELECT * FROM inventario_IngresoInventario WHERE Estado = 1 ORDER BY FechaCreacion DESC";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener las Articulos: " . $e->getMessage());
        }
    }

        public function getGeneralReport()
    {
        try {
            $sql = "SELECT * FROM inventario_vw_ingresos ";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener los ingresos " . $e->getMessage());
        }
    }

    public function getActiveArticles($idBodega = null)
    {
        try {
            if ($idBodega) {
                // Si se especifica una bodega, se consulta la vista filtrada
                $sql = "SELECT * FROM inventario_vw_InventarioActual WHERE idBodega = :idBodega ORDER BY NombreArticulo";
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':idBodega', $idBodega, PDO::PARAM_INT);
                $stmt->execute();
            } else {
                // Si no se especifica bodega, se consulta directamente a los artículos activos
                $sql = "SELECT * FROM inventario_Articulos WHERE Estado = 1 ORDER BY NombreArticulo";
                $stmt = $this->pdo->query($sql);
            }

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener los Artículos: " . $e->getMessage());
        }
    }



    public function getEntryHeader($id)
    {
        try {
            $sql = "SELECT * FROM inventario_IngresoInventario WHERE id = :id AND Estado = 1";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener el detalle de ingreso " . $e->getMessage());
        }
    }

    public function getEntryDetail($id)
    {

        try {
            $sql = "SELECT * FROM inventario_IngresoDetalle WHERE idIngreso = :id AND Estado = 1";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener el detalle de ingreso " . $e->getMessage());
        }
    }


    public function getActiveWarehouses()
    {
        try {
            $sql = "SELECT * FROM inventario_Bodegas WHERE Estado = 1 ";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener las Articulos: " . $e->getMessage());
        }
    }


    public function getHistoricIngresos()
    {
        try {
            $sql = "SELECT * FROM  inventario_vw_HistorialIngresoBodegas";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener las Articulos: " . $e->getMessage());
        }
    }

    public function saveInventoryEntry(
        $UsuarioCreador,
        $AjusteInventario,
        $detalles,
        $idProveedor,
        $NumeroFactura,
        $FechaFactura,
        $Comentarios,
        $TotalFactura,
        $ImpuestoFactura
    ) {
        try {
            $this->pdo->beginTransaction();

            $sqlIngreso = "INSERT INTO inventario_IngresoInventario (
                            Estado, UsuarioCreador, AjusteInventario, FechaCreacion,
                            IdProveedor, NumeroFactura, FechaFactura, Comentarios, TotalFactura,
                            ImpuestoFactura
                        ) VALUES (
                            1, :UsuarioCreador, :AjusteInventario, NOW(), 
                            :idProveedor, :NumeroFactura, :FechaFactura, 
                            :Comentarios, :TotalFactura, :ImpuestoFactura
                        )";

            $stmtIngreso = $this->pdo->prepare($sqlIngreso);
            $stmtIngreso->bindParam(':UsuarioCreador', $UsuarioCreador, PDO::PARAM_INT);
            $stmtIngreso->bindParam(':AjusteInventario', $AjusteInventario, PDO::PARAM_BOOL);
            $stmtIngreso->bindParam(':idProveedor', $idProveedor, PDO::PARAM_INT);
            $stmtIngreso->bindParam(':NumeroFactura', $NumeroFactura, PDO::PARAM_STR);
            $stmtIngreso->bindParam(':FechaFactura', $FechaFactura, PDO::PARAM_STR);
            $stmtIngreso->bindParam(':Comentarios', $Comentarios, PDO::PARAM_STR);
            $stmtIngreso->bindParam(':TotalFactura', $TotalFactura, PDO::PARAM_STR);
            $stmtIngreso->bindParam(':ImpuestoFactura', $ImpuestoFactura, PDO::PARAM_STR);
            $stmtIngreso->execute();

            $idIngreso = $this->pdo->lastInsertId();

            $sqlDetalle = "INSERT INTO inventario_IngresoDetalle (
                            idIngreso, idBodega, idArticulo, CantidadIngreso, Estado, UsuarioCreador, FechaCreacion,
                            PrecioUnitario,SubTotal
                        ) VALUES (
                            :idIngreso, :idBodega, :idArticulo, :CantidadIngreso, 1, :UsuarioCreador, NOW(),
                            :PrecioUnitario, :SubTotal
                        )";

            $stmtDetalle = $this->pdo->prepare($sqlDetalle);

            foreach ($detalles as $detalle) {
                $stmtDetalle->bindParam(':idIngreso', $idIngreso, PDO::PARAM_INT);
                $stmtDetalle->bindParam(':idBodega', $detalle['idBodega'], PDO::PARAM_INT);
                $stmtDetalle->bindParam(':idArticulo', $detalle['idArticulo'], PDO::PARAM_INT);
                $stmtDetalle->bindParam(':CantidadIngreso', $detalle['cantidad'], PDO::PARAM_STR);
                $stmtDetalle->bindParam(':UsuarioCreador', $UsuarioCreador, PDO::PARAM_INT);
                $stmtDetalle->bindParam(':PrecioUnitario', $detalle['PrecioUnitario'], PDO::PARAM_STR);
                $stmtDetalle->bindParam(':SubTotal', $detalle['SubTotal'], PDO::PARAM_STR);
                $stmtDetalle->execute();
            }

            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            die("Error al guardar el ingreso: " . $e->getMessage());
        }
    }


    public function updateInventoryEntry(
        $id,
        $UsuarioCreador,
        $AjusteInventario,
        $detalles,
        $idProveedor,
        $NumeroFactura,
        $FechaFactura,
        $Comentarios,
        $TotalFactura,
        $ImpuestoFactura
    ) {
        try {
            $this->pdo->beginTransaction();

            // Actualizar el ingreso en la tabla padre con todos los campos
            $sqlUpdateIngreso = "UPDATE inventario_IngresoInventario 
                             SET Estado = 1, UsuarioModificador = :UsuarioCreador,
                                 AjusteInventario = :AjusteInventario, FechaModificacion = NOW(),
                                 IdProveedor = :idProveedor, NumeroFactura = :NumeroFactura,
                                 FechaFactura = :FechaFactura, Comentarios = :Comentarios,
                                 TotalFactura = :TotalFactura, ImpuestoFactura = :ImpuestoFactura
                             WHERE id = :id";

            $stmtUpdateIngreso = $this->pdo->prepare($sqlUpdateIngreso);
            $stmtUpdateIngreso->bindParam(':id', $id, PDO::PARAM_INT);
            $stmtUpdateIngreso->bindParam(':UsuarioCreador', $UsuarioCreador, PDO::PARAM_INT);
            $stmtUpdateIngreso->bindParam(':AjusteInventario', $AjusteInventario, PDO::PARAM_BOOL);
            $stmtUpdateIngreso->bindParam(':idProveedor', $idProveedor, PDO::PARAM_INT);
            $stmtUpdateIngreso->bindParam(':NumeroFactura', $NumeroFactura, PDO::PARAM_STR);
            $stmtUpdateIngreso->bindParam(':FechaFactura', $FechaFactura, PDO::PARAM_STR);
            $stmtUpdateIngreso->bindParam(':Comentarios', $Comentarios, PDO::PARAM_STR);
            $stmtUpdateIngreso->bindParam(':TotalFactura', $TotalFactura, PDO::PARAM_STR);
            $stmtUpdateIngreso->bindParam(':ImpuestoFactura', $ImpuestoFactura, PDO::PARAM_STR);
            $stmtUpdateIngreso->execute();

            // Marcar detalles anteriores como inactivos
            $sqlUpdateDetalle = "UPDATE inventario_IngresoDetalle 
                             SET Estado = 0 
                             WHERE idIngreso = :idIngreso";
            $stmtUpdateDetalle = $this->pdo->prepare($sqlUpdateDetalle);
            $stmtUpdateDetalle->bindParam(':idIngreso', $id, PDO::PARAM_INT);
            $stmtUpdateDetalle->execute();

            // Insertar o actualizar detalles
            foreach ($detalles as $detalle) {
                $sqlSelect = "SELECT id FROM inventario_IngresoDetalle 
                          WHERE idIngreso = :idIngreso AND idArticulo = :idArticulo";
                $stmtSelect = $this->pdo->prepare($sqlSelect);
                $stmtSelect->bindParam(':idIngreso', $id, PDO::PARAM_INT);
                $stmtSelect->bindParam(':idArticulo', $detalle['idArticulo'], PDO::PARAM_INT);
                $stmtSelect->execute();

                if ($stmtSelect->rowCount() > 0) {
                    $sqlUpdate = "UPDATE inventario_IngresoDetalle 
                              SET CantidadIngreso = :CantidadIngreso, Estado = 1, 
                                  UsuarioModificador = :UsuarioModificador, FechaCreacion = NOW(),
                                  PrecioUnitario = :PrecioUnitario, SubTotal = :SubTotal
                              WHERE idIngreso = :idIngreso AND idArticulo = :idArticulo";
                    $stmtUpdate = $this->pdo->prepare($sqlUpdate);
                    $stmtUpdate->bindParam(':idIngreso', $id, PDO::PARAM_INT);
                    $stmtUpdate->bindParam(':idArticulo', $detalle['idArticulo'], PDO::PARAM_INT);
                    $stmtUpdate->bindParam(':CantidadIngreso', $detalle['cantidad'], PDO::PARAM_STR);
                    $stmtUpdate->bindParam(':UsuarioModificador', $UsuarioCreador, PDO::PARAM_INT);
                    $stmtUpdate->bindParam(':PrecioUnitario', $detalle['PrecioUnitario'], PDO::PARAM_STR);
                    $stmtUpdate->bindParam(':SubTotal', $detalle['SubTotal'], PDO::PARAM_STR);
                    $stmtUpdate->execute();
                } else {
                    $sqlInsert = "INSERT INTO inventario_IngresoDetalle (
                                idIngreso, idBodega, idArticulo, CantidadIngreso, Estado, 
                                UsuarioCreador, FechaCreacion, PrecioUnitario, SubTotal
                              ) VALUES (
                                :idIngreso, :idBodega, :idArticulo, :CantidadIngreso, 1,
                                :UsuarioCreador, NOW(), :PrecioUnitario, :SubTotal
                              )";
                    $stmtInsert = $this->pdo->prepare($sqlInsert);
                    $stmtInsert->bindParam(':idIngreso', $id, PDO::PARAM_INT);
                    $stmtInsert->bindParam(':idBodega', $detalle['idBodega'], PDO::PARAM_INT);
                    $stmtInsert->bindParam(':idArticulo', $detalle['idArticulo'], PDO::PARAM_INT);
                    $stmtInsert->bindParam(':CantidadIngreso', $detalle['cantidad'], PDO::PARAM_STR);
                    $stmtInsert->bindParam(':UsuarioCreador', $UsuarioCreador, PDO::PARAM_INT);
                    $stmtInsert->bindParam(':PrecioUnitario', $detalle['PrecioUnitario'], PDO::PARAM_STR);
                    $stmtInsert->bindParam(':SubTotal', $detalle['SubTotal'], PDO::PARAM_STR);
                    $stmtInsert->execute();
                }
            }

            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            die("Error al actualizar el ingreso: " . $e->getMessage());
        }
    }

    /**Funcion para ajuste de inventario */

    public function saveInventoryRevision($UsuarioCreador, $datosRevision, $comentarios = '')
    {
        try {
            $this->pdo->beginTransaction();

            // 1. Insertar encabezado de revisión
            $sqlRevision = "INSERT INTO inventario_RevisionInventario (
                            FechaCorte, Estado, UsuarioCreador, FechaCreacion, Comentarios
                        ) VALUES (
                            NOW(), 1, :UsuarioCreador, NOW(), :Comentarios
                        )";
            $stmtRevision = $this->pdo->prepare($sqlRevision);
            $stmtRevision->bindParam(':UsuarioCreador', $UsuarioCreador, PDO::PARAM_INT);
            $stmtRevision->bindParam(':Comentarios', $comentarios, PDO::PARAM_STR);
            $stmtRevision->execute();
            $idRevision = $this->pdo->lastInsertId();

            // Variables para encabezados de ajuste (si hay diferencia)
            $idIngresoAjuste = null;
            $idSalidaAjuste = null;

            // Preparar sentencias para detalle y ajustes
            $sqlDetalle = "INSERT INTO inventario_RevisionDetalle (
                            idRevision, idArticulo, CantidadSistema, CantidadFisica, Estado,
                            UsuarioCreador, FechaCreacion
                        ) VALUES (
                            :idRevision, :idArticulo, :CantidadSistema, :CantidadFisica, 1,
                            :UsuarioCreador, NOW()
                        )";
            $stmtDetalle = $this->pdo->prepare($sqlDetalle);

            // Preparar sentencias de detalle ingreso/salida
            $sqlIngresoDetalle = "INSERT INTO inventario_IngresoDetalle (
                                idIngreso, idBodega, idArticulo, CantidadIngreso, Estado,
                                UsuarioCreador, FechaCreacion, PrecioUnitario, SubTotal, Motivo, idTomaInventario
                            ) VALUES (
                                :idIngreso, :idBodega, :idArticulo, :CantidadIngreso, 1,
                                :UsuarioCreador, NOW(), :PrecioUnitario, :SubTotal, :Motivo, :idTomaInventario
                            )";
            $stmtIngreso = $this->pdo->prepare($sqlIngresoDetalle);

            $sqlSalidaDetalle = "INSERT INTO inventario_SalidaDetalle (
                                idSalida, idBodega, idArticulo, CantidadSalida, Estado,
                                UsuarioCreador, FechaCreacion, PrecioSalida, Motivo, idTomaInventario
                            ) VALUES (
                                :idSalida, :idBodega, :idArticulo, :CantidadSalida, 1,
                                :UsuarioCreador, NOW(), :PrecioSalida, :Motivo, :idTomaInventario
                            )";
            $stmtSalida = $this->pdo->prepare($sqlSalidaDetalle);

            // Recorremos cada detalle de revisión
            foreach ($datosRevision as $item) {
                $idArticulo = $item['idArticulo'];
                $idBodega = $item['idBodega'];
                $cantidadSistema = floatval($item['cantidadSistema']);
                $cantidadFisica = floatval($item['cantidadFisica']);
                $diferencia = $cantidadFisica - $cantidadSistema;

                // Insertar en detalle de revisión
                $stmtDetalle->execute([
                    ':idRevision' => $idRevision,
                    ':idArticulo' => $idArticulo,
                    ':CantidadSistema' => $cantidadSistema,
                    ':CantidadFisica' => $cantidadFisica,
                    ':UsuarioCreador' => $UsuarioCreador
                ]);

                if ($diferencia != 0) {
                    // Obtener precio actual (puedes cambiar esto por una consulta más elaborada)
                    $stmtPrecio = $this->pdo->prepare("SELECT PrecioCompra FROM inventario_Articulos WHERE id = :idArticulo");
                    $stmtPrecio->bindParam(':idArticulo', $idArticulo, PDO::PARAM_INT);
                    $stmtPrecio->execute();  // sin parámetros 
                    $PrecioUnitario = $stmtPrecio->fetchColumn();
                    if (!$PrecioUnitario) $PrecioUnitario = 0;

                    $SubTotal = abs($diferencia) * $PrecioUnitario;

                    if ($diferencia > 0) {
                        // Generar encabezado ingreso si aún no se ha creado
                        if (!$idIngresoAjuste) {
                            $sqlIngreso = "INSERT INTO inventario_IngresoInventario (
                                            Estado, UsuarioCreador, AjusteInventario, FechaCreacion, Comentarios
                                        ) VALUES (
                                            1, :UsuarioCreador, 1, NOW(), :Comentarios
                                        )";
                            $stmtEncIngreso = $this->pdo->prepare($sqlIngreso);
                            $stmtEncIngreso->execute([
                                ':UsuarioCreador' => $UsuarioCreador,
                                ':Comentarios' => "Ajuste por toma inventario #{$idRevision}"
                            ]);
                            $idIngresoAjuste = $this->pdo->lastInsertId();
                        }

                        // Insertar detalle ingreso
                        $stmtIngreso->execute([
                            ':idIngreso' => $idIngresoAjuste,
                            ':idBodega' => $idBodega,
                            ':idArticulo' => $idArticulo,
                            ':CantidadIngreso' => $diferencia,
                            ':UsuarioCreador' => $UsuarioCreador,
                            ':PrecioUnitario' => $PrecioUnitario,
                            ':SubTotal' => $SubTotal,
                            ':Motivo' => 'Ajuste positivo',
                            ':idTomaInventario' => $idRevision
                        ]);
                    } else {
                        // Generar encabezado salida si aún no se ha creado
                        if (!$idSalidaAjuste) {
                            $sqlSalida = "INSERT INTO inventario_SalidaInventario (
                                            Estado, UsuarioCreador, FechaCreacion, TipoSalida, Comentarios
                                        ) VALUES (
                                            1, :UsuarioCreador, NOW(), 4, :Comentarios
                                        )";
                            $stmtEncSalida = $this->pdo->prepare($sqlSalida);
                            $stmtEncSalida->execute([
                                ':UsuarioCreador' => $UsuarioCreador,
                                ':Comentarios' => "Ajuste por toma inventario #{$idRevision}"
                            ]);
                            $idSalidaAjuste = $this->pdo->lastInsertId();
                        }

                        // Insertar detalle salida
                        $stmtSalida->execute([
                            ':idSalida' => $idSalidaAjuste,
                            ':idBodega' => $idBodega,
                            ':idArticulo' => $idArticulo,
                            ':CantidadSalida' => abs($diferencia),
                            ':UsuarioCreador' => $UsuarioCreador,
                            ':PrecioSalida' => $PrecioUnitario,
                            ':Motivo' => 'Ajuste negativo',
                            ':idTomaInventario' => $idRevision
                        ]);
                    }
                }
            }

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            die("Error general: " . $e->getMessage());
        }
    }


    public function getInventoryCountsHistory()
{
    $sql = "SELECT 
                R.id, 
                R.FechaCorte, 
                U.nombre AS Usuario, 
                R.Comentarios, 
                R.Estado
            FROM inventario_RevisionInventario R
            JOIN usuarios U ON R.UsuarioCreador = U.id
            ORDER BY R.FechaCorte DESC";

    $stmt = $this->pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getInventoryCountsDetail($idRevision)
{
    $sql = "SELECT 
                R.id,
                R.FechaCorte,
                R.Estado,
                U.Nombre AS Usuario,
                A.NombreArticulo AS Articulo,

                D.CantidadSistema,
                D.CantidadFisica,
                R.Comentarios,
                D.CantidadFisica - D.CantidadSistema  AS Diferencia
            FROM inventario_RevisionInventario R
            JOIN inventario_RevisionDetalle D ON R.id = D.idRevision
            JOIN usuarios U ON R.UsuarioCreador = U.id
            JOIN inventario_Articulos A ON D.idArticulo = A.id
    
            WHERE R.Estado = 1 AND R.id = :idRevision
            ORDER BY R.FechaCorte DESC";

    $stmt = $this->pdo->prepare($sql); // Preparar primero
    $stmt->bindParam(':idRevision', $idRevision, PDO::PARAM_INT); // Luego enlazar
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

}
