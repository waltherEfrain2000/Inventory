<?php
class Venta
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    //Método para guardar una Venta
    public function guardarVenta($datos)
    {
        try {
            $this->pdo->beginTransaction();

            // Verificar si nRemision ya existe con estado diferente de 5
            $sqlCheck = "SELECT COUNT(*) FROM ventas WHERE nRemision = :nRemision AND estado != 5";
            $stmtCheck = $this->pdo->prepare($sqlCheck);
            $stmtCheck->bindParam(':nRemision', $datos['nremision'], PDO::PARAM_STR);
            $stmtCheck->execute();
            $exists = $stmtCheck->fetchColumn();

            if ($exists > 0) {
                $this->pdo->rollBack();
                error_log("Error: nRemision ya fue registrado");
                echo json_encode(["success" => false, "error" => "El número de remisión ya fue registrado."]);
                exit;
            }



            if ($datos['hacer'] == 1) {
                $estadoventa = 1;
            } else {
                $estadoventa = 2;
            }

            // Inserción en la tabla `ventas`
            $sql = "INSERT INTO `ventas`(`idCliente`, `fechaVenta`, `nRemision`, `idRemitente`, `puntoPartida`, `destino`, `valordeventa`, `idMotivoTraslado`, `fechaTraslado`, `fechaFinTraslado`, `emisiones`, `km`, `raqui`, `estado`, `creadoPor`, `fechaCreado`)
            VALUES (:idCliente, :fechaVenta, :nRemision, :idRemitente, :puntoPartida, :destino, :valorventa, :idMotivoTraslado, :fechaTraslado, :fechaFinTraslado, :emisiones, :km, 0, $estadoventa, 1, NOW())";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':idCliente', $datos['destinatario'], PDO::PARAM_STR);
            $stmt->bindParam(':fechaVenta', $datos['fecha'], PDO::PARAM_STR);
            $stmt->bindParam(':nRemision', $datos['nremision'], PDO::PARAM_STR);
            $stmt->bindParam(':idRemitente', $datos['remitente'], PDO::PARAM_STR);
            $stmt->bindParam(':puntoPartida', $datos['partida'], PDO::PARAM_STR);
            $stmt->bindParam(':destino', $datos['destino'], PDO::PARAM_STR);
            $stmt->bindParam(':valorventa', $datos['valorventa'], PDO::PARAM_STR);
            $stmt->bindParam(':idMotivoTraslado', $datos['traslado'], PDO::PARAM_STR);
            $stmt->bindParam(':fechaTraslado', $datos['fechainicio'], PDO::PARAM_STR);
            $stmt->bindParam(':fechaFinTraslado', $datos['fechatermino'], PDO::PARAM_STR);
            //$stmt->bindParam(':certificaciones', $datos['certificaciones'], PDO::PARAM_STR);
            $stmt->bindParam(':emisiones', $datos['emisiones'], PDO::PARAM_STR);
            $stmt->bindParam(':km', $datos['km'], PDO::PARAM_STR);

            if ($stmt->execute()) {
                $idVenta = $this->pdo->lastInsertId();
                $sucursal = 1;
                // insertar la certificacion
                $sqlCertificacion = "INSERT INTO `ventas_certificaciones`(`idTipocertificacion`, `codCert`, `descripcion`, `estado`)
                                VALUES (0, :codCert, :descripcion, 1)";
                $stmtCertificacion = $this->pdo->prepare($sqlCertificacion);
                $stmtCertificacion->bindParam(':codCert', $idVenta, PDO::PARAM_STR); // Cambiado a PDO::PARAM_STR
                $stmtCertificacion->bindParam(':descripcion', $datos['ncertificacion'], PDO::PARAM_STR); // Cambiado a PDO::PARAM_STR
                $stmtCertificacion->execute();
                // insertar la tipo certificacion
                $sqltCertificacion = "INSERT INTO `ventas_tipos_certificacion`(`codCert`, `descripcion`, `estado`)
                                VALUES (:codCert, :descripcion, 1)";
                $sqltCertificacion = $this->pdo->prepare($sqltCertificacion);
                $sqltCertificacion->bindParam(':codCert', $idVenta, PDO::PARAM_STR); // Cambiado a PDO::PARAM_STR
                $sqltCertificacion->bindParam(':descripcion', $datos['tipocertificacion'], PDO::PARAM_STR); // Cambiado a PDO::PARAM_STR
                $sqltCertificacion->execute();


                if (!empty($datos['detalles'])) {
                    /* $this->pdo->rollBack();
                    return ["success" => false, "error" => "Debe incluir al menos un detalle en la venta."]; */

                    // Inserción en la tabla `ventas_detalle`
                    $sqlDetalle = "INSERT INTO `ventas_detalle`(`idVenta`, `idProducto`, `idProductor`, `cantidad`, `lote`, `creadoPor`, `fechaCreado`)
                VALUES (:idVenta, :idProducto, :idProductor, :cantidad, :lote, 1, NOW())";
                    $stmtDetalle = $this->pdo->prepare($sqlDetalle);

                    foreach ($datos['detalles'] as $detalle) {
                        $stmtDetalle->bindParam(':idVenta', $idVenta, PDO::PARAM_INT);
                        $stmtDetalle->bindParam(':idProducto', $detalle['idProducto'], PDO::PARAM_INT);
                        $stmtDetalle->bindParam(':idProductor', $detalle['idProductor'], PDO::PARAM_INT);
                        $stmtDetalle->bindParam(':cantidad', $detalle['cantidad'], PDO::PARAM_INT);
                        $stmtDetalle->bindParam(':lote', $detalle['lote'], PDO::PARAM_INT);
                        $stmtDetalle->execute();
                    }
                }

                // Inserción en la tabla `ventas_transporte`
                $sqlTransporte = "INSERT INTO `ventas_transporte`(`idVenta`, `idTransportista`, `idConductor`, `placa`, `licencia`, `usuarioCreado`, `fechaCreado`)
                VALUES (:idVenta, :idTransportista, :idConductor, :placa, :licencia, 1, NOW())";
                $stmtTransporte = $this->pdo->prepare($sqlTransporte);
                $stmtTransporte->bindParam(':idVenta', $idVenta, PDO::PARAM_INT);
                $stmtTransporte->bindParam(':idTransportista', $datos['transportista'], PDO::PARAM_INT);
                $stmtTransporte->bindParam(':idConductor', $datos['conductor'], PDO::PARAM_INT);
                $stmtTransporte->bindParam(':placa', $datos['placa'], PDO::PARAM_STR);
                $stmtTransporte->bindParam(':licencia', $datos['licencia'], PDO::PARAM_STR);
                $stmtTransporte->execute();

                //modificar el estado de la tabla de trasporte
                $sqlTransporte = "UPDATE `flota_vehiculos` SET `estado` = 2 WHERE `placa` = :placa";
                $stmtTransporte = $this->pdo->prepare($sqlTransporte);
                $stmtTransporte->bindParam(':placa', $datos['placa'], PDO::PARAM_STR);
                $stmtTransporte->execute();




                // Inserción en la tabla `ventas_otros_traslados` si aplica
                if ($datos['traslado'] == 12) {
                    $sql = "INSERT INTO `ventas_otros_traslados`(`descripcion`, `autorizacion`, `numeracion`, `fecha`, `idventa`, `estado`)
                    VALUES (:descripcion, :autorizacion, :numeracion, :fecha, :idventa, 1)";
                    $stmt = $this->pdo->prepare($sql);
                    $stmt->bindParam(':descripcion', $datos['otrotraslado'], PDO::PARAM_STR);
                    $stmt->bindParam(':autorizacion', $datos['nAutorizacion'], PDO::PARAM_STR);
                    $stmt->bindParam(':numeracion', $datos['numeracion'], PDO::PARAM_STR);
                    $stmt->bindParam(':fecha', $datos['fechaDocotrotraslado'], PDO::PARAM_STR);
                    $stmt->bindParam(':idventa', $idVenta, PDO::PARAM_INT);
                    $stmt->execute();
                }

                $this->pdo->commit();
                return ["success" => true];
            } else {
                $this->pdo->rollBack();
                return ["success" => false, "error" => "Error al guardar la venta."];
            }
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            error_log("Error al guardar la Venta: " . $e->getMessage());
            return ["success" => false, "error" => "Error al guardar la venta: " . $e->getMessage()];
        }
    }

    //Método para editar una Venta
    public function editarVenta($datos)
    {
        try {
            $this->pdo->beginTransaction();

            // Verificar si nRemision ya existe con estado diferente de 5
            $sqlCheck = "SELECT COUNT(*) FROM ventas WHERE nRemision = :nRemision AND estado = :idVenta";
            $stmtCheck = $this->pdo->prepare($sqlCheck);
            $stmtCheck->bindParam(':nRemision', $datos['nremision'], PDO::PARAM_STR);
            $stmtCheck->bindParam(':idVenta', $datos['idVenta'], PDO::PARAM_INT);
            $stmtCheck->execute();
            $exists = $stmtCheck->fetchColumn();

            if ($exists > 0) {
                $this->pdo->rollBack();
                error_log("Error: nRemision ya fue registrado");
                echo json_encode(["success" => false, "error" => "El número de remisión ya fue registrado."]);
                exit;
            }



            if ($datos['hacer'] == 1) {
                $estadoventa = 1;
            } else {
                $estadoventa = 2;
            }

            // Inserción en la tabla `ventas`
            $sql = "UPDATE `ventas` 
                    SET `idCliente` = :idCliente, 
                        `fechaVenta` = :fechaVenta, 
                        `nRemision` = :nRemision, 
                        `idRemitente` = :idRemitente, 
                        `puntoPartida` = :puntoPartida, 
                        `destino` = :destino, 
                        `valordeventa` = :valorventa, 
                        `idMotivoTraslado` = :idMotivoTraslado, 
                        `fechaTraslado` = :fechaTraslado, 
                        `fechaFinTraslado` = :fechaFinTraslado, 
                        `emisiones` = :emisiones, 
                        `km` = :km, 
                        `estado` = :estadoventa, 
                        `modificadoPor` = 1, 
                        `fechaModificado` = NOW() 
                    WHERE idVenta = :idVenta";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindParam(':idVenta', $datos['idVenta'], PDO::PARAM_STR);
            $stmt->bindParam(':idCliente', $datos['destinatario'], PDO::PARAM_STR);
            $stmt->bindParam(':fechaVenta', $datos['fecha'], PDO::PARAM_STR);
            $stmt->bindParam(':nRemision', $datos['nremision'], PDO::PARAM_STR);
            $stmt->bindParam(':idRemitente', $datos['remitente'], PDO::PARAM_STR);
            $stmt->bindParam(':puntoPartida', $datos['partida'], PDO::PARAM_STR);
            $stmt->bindParam(':destino', $datos['destino'], PDO::PARAM_STR);
            $stmt->bindParam(':valorventa', $datos['valorventa'], PDO::PARAM_STR);
            $stmt->bindParam(':idMotivoTraslado', $datos['traslado'], PDO::PARAM_STR);
            $stmt->bindParam(':fechaTraslado', $datos['fechainicio'], PDO::PARAM_STR);
            $stmt->bindParam(':fechaFinTraslado', $datos['fechatermino'], PDO::PARAM_STR);
            $stmt->bindParam(':emisiones', $datos['emisiones'], PDO::PARAM_STR);
            $stmt->bindParam(':km', $datos['km'], PDO::PARAM_STR);
            $stmt->bindParam(':estadoventa', $estadoventa, PDO::PARAM_INT); // Aquí vinculamos el parámetro correctamente


            if ($stmt->execute()) {
                $idVenta = $datos['idVenta'];

                // Verificar si ya existe un registro en `ventas_certificaciones`
                $sqlCheckCertificacion = "SELECT COUNT(*) FROM `ventas_certificaciones` WHERE `codCert` = :codCert";
                $stmtCheckCertificacion = $this->pdo->prepare($sqlCheckCertificacion);
                $stmtCheckCertificacion->bindParam(':codCert', $idVenta, PDO::PARAM_STR);
                $stmtCheckCertificacion->execute();
                $existsCertificacion = $stmtCheckCertificacion->fetchColumn();

                if ($existsCertificacion > 0) {
                    // Si existe, actualizar la descripción
                    $sqlUpdateCertificacion = "UPDATE `ventas_certificaciones` SET `descripcion` = :descripcion WHERE `codCert` = :codCert";
                    $stmtUpdateCertificacion = $this->pdo->prepare($sqlUpdateCertificacion);
                    $stmtUpdateCertificacion->bindParam(':codCert', $idVenta, PDO::PARAM_STR);
                    $stmtUpdateCertificacion->bindParam(':descripcion', $datos['ncertificacion'], PDO::PARAM_STR);
                    $stmtUpdateCertificacion->execute();
                } else {
                    // Si no existe, insertar un nuevo registro
                    $sqlCertificacion = "INSERT INTO `ventas_certificaciones`(`idTipocertificacion`, `codCert`, `descripcion`, `estado`)
                          VALUES (0, :codCert, :descripcion, 1)";
                    $stmtCertificacion = $this->pdo->prepare($sqlCertificacion);
                    $stmtCertificacion->bindParam(':codCert', $idVenta, PDO::PARAM_STR);
                    $stmtCertificacion->bindParam(':descripcion', $datos['ncertificacion'], PDO::PARAM_STR);
                    $stmtCertificacion->execute();
                }

                // Verificar si ya existe un registro en `ventas_tipos_certificacion`
                $sqlCheckTipoCertificacion = "SELECT COUNT(*) FROM `ventas_tipos_certificacion` WHERE `codCert` = :codCert";
                $stmtCheckTipoCertificacion = $this->pdo->prepare($sqlCheckTipoCertificacion);
                $stmtCheckTipoCertificacion->bindParam(':codCert', $idVenta, PDO::PARAM_STR);
                $stmtCheckTipoCertificacion->execute();
                $existsTipoCertificacion = $stmtCheckTipoCertificacion->fetchColumn();

                if ($existsTipoCertificacion > 0) {
                    // Si existe, actualizar la descripción
                    $sqlUpdateTipoCertificacion = "UPDATE `ventas_tipos_certificacion` SET `descripcion` = :descripcion WHERE `codCert` = :codCert";
                    $stmtUpdateTipoCertificacion = $this->pdo->prepare($sqlUpdateTipoCertificacion);
                    $stmtUpdateTipoCertificacion->bindParam(':codCert', $idVenta, PDO::PARAM_STR);
                    $stmtUpdateTipoCertificacion->bindParam(':descripcion', $datos['tipocertificacion'], PDO::PARAM_STR);
                    $stmtUpdateTipoCertificacion->execute();
                } else {
                    // Si no existe, insertar un nuevo registro
                    $sqltCertificacion = "INSERT INTO `ventas_tipos_certificacion`(`codCert`, `descripcion`, `estado`)
                           VALUES (:codCert, :descripcion, 1)";
                    $stmttCertificacion = $this->pdo->prepare($sqltCertificacion);
                    $stmttCertificacion->bindParam(':codCert', $idVenta, PDO::PARAM_STR);
                    $stmttCertificacion->bindParam(':descripcion', $datos['tipocertificacion'], PDO::PARAM_STR);
                    $stmttCertificacion->execute();
                }

                // cambiar estado de la tabla ventas_detalle a 0
                $sqlDetalle = "UPDATE ventas_detalle SET estado = 0 WHERE idVenta = :idVenta";
                $stmtDetalle = $this->pdo->prepare($sqlDetalle);
                $stmtDetalle->bindParam(':idVenta', $datos['idVenta'], PDO::PARAM_INT);
                $stmtDetalle->execute();

                // Inserción en la tabla `ventas_detalle`
                $sqlDetalle = "INSERT INTO `ventas_detalle`(`idVenta`, `idProducto`, `idProductor`, `cantidad`, `lote`, `creadoPor`, `fechaCreado`)
                VALUES (:idVenta, :idProducto, :idProductor, :cantidad, :lote, 1, NOW())";
                $stmtDetalle = $this->pdo->prepare($sqlDetalle);

                foreach ($datos['detalles'] as $detalle) {
                    $stmtDetalle->bindParam(':idVenta', $idVenta, PDO::PARAM_INT);
                    $stmtDetalle->bindParam(':idProducto', $datos['idProducto'], PDO::PARAM_INT);
                    $stmtDetalle->bindParam(':idProductor', $detalle['idProductor'], PDO::PARAM_INT);
                    $stmtDetalle->bindParam(':cantidad', $detalle['cantidad'], PDO::PARAM_INT);
                    $stmtDetalle->bindParam(':lote', $detalle['lote'], PDO::PARAM_INT);
                    $stmtDetalle->execute();
                }

                // editar en la tabla `ventas_trasporte`
                $sqlTransporte = "UPDATE `ventas_transporte` SET `idTransportista` = :idTransportista, `idConductor` = :idConductor, `placa` = :placa, `licencia` = :licencia, `modificadoPor` = 1, `fechaModificado` = NOW() WHERE idVenta = :idVenta";
                $stmtTransporte = $this->pdo->prepare($sqlTransporte);
                $stmtTransporte->bindParam(':idVenta', $idVenta, PDO::PARAM_INT);
                $stmtTransporte->bindParam(':idTransportista', $datos['transportista'], PDO::PARAM_INT);
                $stmtTransporte->bindParam(':idConductor', $datos['conductor'], PDO::PARAM_INT);
                $stmtTransporte->bindParam(':placa', $datos['placa'], PDO::PARAM_STR);
                $stmtTransporte->bindParam(':licencia', $datos['licencia'], PDO::PARAM_STR);
                $stmtTransporte->execute();

                //modificar el estado de la tabla de trasporte
                $sqlTransporte = "UPDATE `flota_vehiculos` SET `estado` = 2 WHERE `placa` = :placa";
                $stmtTransporte = $this->pdo->prepare($sqlTransporte);
                $stmtTransporte->bindParam(':placa', $datos['placa'], PDO::PARAM_STR);
                $stmtTransporte->execute();

                // Inserción en la tabla `ventas_otros_traslados` si aplica
                if ($datos['traslado'] == 12) {
                    // Verificar si ya existe un registro con el mismo idventa
                    $sqlCheck = "SELECT COUNT(*) FROM ventas_otros_traslados WHERE idVenta = :idventa";
                    $stmtCheck = $this->pdo->prepare($sqlCheck);
                    $stmtCheck->bindParam(':idventa', $idVenta, PDO::PARAM_INT);
                    $stmtCheck->execute();
                    $exists = $stmtCheck->fetchColumn();

                    if ($exists > 0) {
                        // Si existe, actualizar el registro
                        $sqlUpdate = "UPDATE `ventas_otros_traslados`
                                      SET `descripcion` = :descripcion, 
                                          `autorizacion` = :autorizacion, 
                                          `numeracion` = :numeracion, 
                                          `fecha` = :fecha, 
                                          `estado` = 1
                                      WHERE `idVenta` = :idventa";
                        $stmtUpdate = $this->pdo->prepare($sqlUpdate);
                        $stmtUpdate->bindParam(':descripcion', $datos['otrotraslado'], PDO::PARAM_STR);
                        $stmtUpdate->bindParam(':autorizacion', $datos['nAutorizacion'], PDO::PARAM_STR);
                        $stmtUpdate->bindParam(':numeracion', $datos['numeracion'], PDO::PARAM_STR);
                        $stmtUpdate->bindParam(':fecha', $datos['fechaDocotrotraslado'], PDO::PARAM_STR);
                        $stmtUpdate->bindParam(':idventa', $idVenta, PDO::PARAM_INT);
                        $stmtUpdate->execute();
                    } else {
                        // Si no existe, insertar un nuevo registro
                        $sqlInsert = "INSERT INTO `ventas_otros_traslados`(`descripcion`, `autorizacion`, `numeracion`, `fecha`, `idVenta`, `estado`)
                                      VALUES (:descripcion, :autorizacion, :numeracion, :fecha, :idventa, 1)";
                        $stmtInsert = $this->pdo->prepare($sqlInsert);
                        $stmtInsert->bindParam(':descripcion', $datos['otrotraslado'], PDO::PARAM_STR);
                        $stmtInsert->bindParam(':autorizacion', $datos['nAutorizacion'], PDO::PARAM_STR);
                        $stmtInsert->bindParam(':numeracion', $datos['numeracion'], PDO::PARAM_STR);
                        $stmtInsert->bindParam(':fecha', $datos['fechaDocotrotraslado'], PDO::PARAM_STR);
                        $stmtInsert->bindParam(':idventa', $idVenta, PDO::PARAM_INT);
                        $stmtInsert->execute();
                    }
                } else {

                    $sqlUpdate = "UPDATE ventas_otros_traslados SET estado = 0 WHERE idVenta = :idventa";
                    $stmtUpdate = $this->pdo->prepare($sqlUpdate);
                    $stmtUpdate->bindParam(':idventa', $idVenta, PDO::PARAM_INT);
                    $stmtUpdate->execute();
                }

                $this->pdo->commit();
                return ["success" => true];
            } else {
                $this->pdo->rollBack();
                return ["success" => false, "error" => "Error al guardar la venta."];
            }
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            error_log("Error al guardar la Venta: " . $e->getMessage());
            return ["success" => false, "error" => "Error al guardar la venta: " . $e->getMessage()];
        }
    }




    //Método para obtener todas las Ventas
    public function obtenerVentas()
    {
        try {
            $sql = "SELECT v.*, 
                        c.nombre, 
                        SUM(vd.cantidad) AS total_cantidad, 
                        SUM(vd.cantidad) * c.precioVenta AS venta_total,
                        ev.descripcion AS descripcionestado,
                        vt.idTransportista, vt.idConductor, vt.placa,
                        vc.nombre AS nombreconductor
                    FROM ventas v 
                    LEFT JOIN clientes c ON v.idCliente = c.idCliente
                    LEFT JOIN ventas_detalle vd ON v.idVenta = vd.idVenta
                    LEFT JOIN ventas_estado ev ON v.estado = ev.estado
                    LEFT JOIN ventas_transporte vt ON v.idVenta = vt.idVenta
                    LEFT JOIN ventas_configuracion_conductores vc ON vt.idConductor = vc.idConductor
                    where v.estado != 5
                    GROUP BY v.idVenta, c.nombre, c.precioVenta
                    ORDER BY v.idVenta DESC";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener los Ventas: " . $e->getMessage());
            return [];
        }
    }

    public function listarEditar($data)
    {
        /* try { */
        // Consulta principal sin unir detalles para evitar duplicación de ventas
        $sql = "SELECT v.*, 
                    c.nombre, 
                    ev.descripcion AS descripcionestado,
                    ot.descripcion AS otrotraslado,
                    ot.autorizacion AS nAutorizacion,
                    ot.numeracion AS numeracion,
                    ot.fecha AS fechaDocotrotraslado,
                    vt.idTransportista, vt.idConductor, vt.placa, vt.licencia,
                    m.id AS marca_id,
                    m.nombre AS marca_nombre,
                    tc.descripcion AS tipocertificacion,
                    vc.descripcion AS ncertificacion
                FROM ventas v 
                LEFT JOIN clientes c ON v.idCliente = c.idCliente
                LEFT JOIN ventas_estado ev ON v.estado = ev.estado
                LEFT JOIN ventas_otros_traslados ot ON v.idVenta = ot.idventa AND ot.estado = 1
                LEFT JOIN ventas_transporte vt ON v.idVenta = vt.idVenta
                LEFT JOIN flota_vehiculos vhl ON vt.placa = vhl.placa
                LEFT JOIN flota_marcas m ON vhl.id_marca = m.id
                LEFT JOIN ventas_tipos_certificacion tc ON v.idVenta = tc.codCert
                LEFT JOIN ventas_certificaciones vc ON v.idVenta = vc.codCert
                WHERE v.idVenta = :id
                ORDER BY v.idVenta DESC
                LIMIT 1;";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':id', $data, PDO::PARAM_INT);
        $stmt->execute();
        $venta = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$venta) {
            return [];
        }

        // Obtener detalles de la venta
        $sqlDetalles = "SELECT vd.idProducto, vd.idProductor, vd.cantidad, vd.lote, p.nombre
                            FROM ventas_detalle as vd
                            LEFT JOIN compras_productores as p ON vd.idProductor = p.idProductor
                            /* LEFT JOIN producto as pr ON vd.idProducto = pr.idProducto */
                            WHERE vd.idVenta = :id AND vd.estado = 1";

        $stmtDetalles = $this->pdo->prepare($sqlDetalles);
        $stmtDetalles->bindParam(':id', $data, PDO::PARAM_INT);
        $stmtDetalles->execute();
        $detalles = $stmtDetalles->fetchAll(PDO::FETCH_ASSOC);

        // Agregar los detalles a la venta como un array dentro de la respuesta
        $venta['detalles'] = $detalles;

        return $venta;
        /* } catch (PDOException $e) {
            error_log("Error al obtener la venta: " . $e->getMessage());
            return [];
        } */
    }



    //Método para guardar ventas directas
    public function guardarventadirecta($data)
    {
        try {
            $this->pdo->beginTransaction();

            //definir estado
            if ($data['hacer'] == 1) {
                $estadoventa = 4;
            } else {
                $estadoventa = 3;
            }

            // Verificar si nrecepcion o nrecepcionraqui ya existen con estado diferente de 5
            $sqlCheck = "SELECT COUNT(*) FROM ventas_directas 
                         WHERE boleta = :boleta
                         AND estado != 5";
            $stmtCheck = $this->pdo->prepare($sqlCheck);
            $stmtCheck->bindParam(':boleta', $data['boleta'], PDO::PARAM_INT);
            $stmtCheck->execute();
            $exists = $stmtCheck->fetchColumn();

            if ($exists > 0) {
                // Si ya existe, no permitir guardar
                return ["success" => false, "error" => "El número de boleta ya están registrados en una venta activa."];
            }

            // Inserción en la tabla `ventas_directas`
            $sql = "INSERT INTO `ventas_directas`(`idcliente`, `pventa`, `idproveedor`, `pcompra`, `boleta`, `peso`, `monto`, `ganancia`, `observaciones`, `creadoPor`, `estado`, `fechaCreado`)
                    VALUES (:idcliente, :pventa, :idproveedor, :pcompra, :boleta, :peso, :monto, :ganancia, :observaciones, 1, $estadoventa, NOW())";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':idcliente', $data['cliente'], PDO::PARAM_INT);
            $stmt->bindParam(':pventa', $data['pventa'], PDO::PARAM_STR);
            $stmt->bindParam(':idproveedor', $data['proveedor'], PDO::PARAM_INT);
            $stmt->bindParam(':pcompra', $data['pcompra'], PDO::PARAM_STR);
            $stmt->bindParam(':boleta', $data['boleta'], PDO::PARAM_STR);
            $stmt->bindParam(':peso', $data['peso'], PDO::PARAM_STR);
            $stmt->bindParam(':monto', $data['totalp'], PDO::PARAM_STR);
            $stmt->bindParam(':ganancia', $data['ganancia'], PDO::PARAM_STR);
            $stmt->bindParam(':observaciones', $data['observacion'], PDO::PARAM_STR);
            $stmt->execute();
            // Obtener el ID de la venta recién insertada
            $idVenta = $this->pdo->lastInsertId();
            // agregar venta_transporte

            $sqlTransporte = "INSERT INTO `ventas_transporte`(`idVenta`, `idConductor`, `placa`, `flete`, `usuarioCreado`, `fechaCreado`)
            VALUES (:idVenta, :idConductor, :placa, :flete, 1, NOW())";
            $stmtTransporte = $this->pdo->prepare($sqlTransporte);
            $stmtTransporte->bindParam(':idVenta', $idVenta, PDO::PARAM_INT);
            $stmtTransporte->bindParam(':idConductor', $data['idConductor'], PDO::PARAM_INT);
            $stmtTransporte->bindParam(':placa', $data['placa'], PDO::PARAM_STR);
            $stmtTransporte->bindParam(':flete', $data['pflete'], PDO::PARAM_STR);
            $stmtTransporte->execute();

            //modificar el estado de la tabla de trasporte
            $sqlTransporte = "UPDATE `flota_vehiculos` SET `estado` = 2 WHERE `placa` = :placa";
            $stmtTransporte = $this->pdo->prepare($sqlTransporte);
            $stmtTransporte->bindParam(':placa', $data['placa'], PDO::PARAM_STR);
            $stmtTransporte->execute();


            $this->pdo->commit();
            return ["success" => true];
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            error_log("Error al guardar la venta: " . $e->getMessage());
            return ["success" => false, "error" => "Error al guardar la venta: " . $e->getMessage()];
        }
    }

    //Método para editar ventas de terceros
    public function editarventadirecta($data)
    {
        try {
            $this->pdo->beginTransaction();

            //definir estado
            if ($data['hacer'] == 1) {
                $estadoventa = 4;
            } else {
                $estadoventa = 3;
            }

            // Verificar si nrecepcion o nrecepcionraqui ya existen con estado diferente de 5
            $sqlCheck = "SELECT COUNT(*) FROM ventas_directas 
                         WHERE boleta = :boleta
                         AND id != :id
                         AND estado != 5";
            $stmtCheck = $this->pdo->prepare($sqlCheck);
            $stmtCheck->bindParam(':boleta', $data['boleta'], PDO::PARAM_INT);
            $stmtCheck->bindParam(':id', $data['id'], PDO::PARAM_INT);
            $stmtCheck->execute();
            $exists = $stmtCheck->fetchColumn();

            if ($exists > 0) {
                // Si ya existe, no permitir guardar
                return ["success" => false, "error" => "El número de boleta ya están registrados en una venta activa."];
            }

            // Inserción en la tabla `ventas_directas`
            $sql = "UPDATE `ventas_directas` 
                    SET `idcliente` = :idcliente, 
                        `pventa` = :pventa, 
                        `idproveedor` = :idproveedor, 
                        `pcompra` = :pcompra, 
                        `boleta` = :boleta, 
                        `peso` = :peso, 
                        `monto` = :monto, 
                        `ganancia` = :ganancia, 
                        `observaciones` = :observaciones, 
                        `estado` = $estadoventa,
                        `modificadoPor` = 1, 
                        `fechaModificado` = NOW() 
                    WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);
            $stmt->bindParam(':idcliente', $data['cliente'], PDO::PARAM_INT);
            $stmt->bindParam(':pventa', $data['pventa'], PDO::PARAM_STR);
            $stmt->bindParam(':idproveedor', $data['proveedor'], PDO::PARAM_INT);
            $stmt->bindParam(':pcompra', $data['pcompra'], PDO::PARAM_STR);
            $stmt->bindParam(':boleta', $data['boleta'], PDO::PARAM_STR);
            $stmt->bindParam(':peso', $data['peso'], PDO::PARAM_STR);
            $stmt->bindParam(':monto', $data['totalp'], PDO::PARAM_STR);
            $stmt->bindParam(':ganancia', $data['ganancia'], PDO::PARAM_STR);
            $stmt->bindParam(':observaciones', $data['observacion'], PDO::PARAM_STR);
            $stmt->execute();
            // agregar venta_transporte
            $sqlTransporte = "UPDATE `ventas_transporte` 
                              SET `idConductor` = :idConductor, 
                                  `placa` = :placa, 
                                  `flete` = :flete, 
                                  `modificadoPor` = 1, 
                                  `fechaModificado` = NOW() 
                              WHERE idVenta = :idVenta";
            $stmtTransporte = $this->pdo->prepare($sqlTransporte);
            $stmtTransporte->bindParam(':idVenta', $data['id'], PDO::PARAM_INT);
            $stmtTransporte->bindParam(':idConductor', $data['idConductor'], PDO::PARAM_INT);
            $stmtTransporte->bindParam(':placa', $data['placa'], PDO::PARAM_STR);
            $stmtTransporte->bindParam(':flete', $data['pflete'], PDO::PARAM_STR);
            $stmtTransporte->execute();
            $this->pdo->commit();
            return ["success" => true];
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            error_log("Error al guardar la venta: " . $e->getMessage());
            return ["success" => false, "error" => "Error al guardar la venta: " . $e->getMessage()];
        }
    }


    //Método para obtener ventas de terceros
    public function cargarVentasTerceros()
    {
        try {
            $sql = "SELECT vd.*, c.nombre, ev.estado AS idestado, vt.flete, vhl.placa
                    FROM ventas_directas vd
                    LEFT JOIN clientes c ON vd.idCliente = c.idCliente
                    LEFT JOIN ventas_estado ev ON vd.estado = ev.estado
                    LEFT JOIN ventas_transporte vt on vd.id = vt.idVenta
                    LEFT JOIN flota_vehiculos vhl ON vt.placa = vhl.placa
                    WHERE vd.estado != 5
                    ORDER BY vd.id DESC";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener los Ventas: " . $e->getMessage());
            return [];
        }
    }

    //Método para recibir pago de ventas de terceros
    public function recibirPagoTerceros($data)
    {
        try {
            $sql = "UPDATE ventas_directas SET estado = 2 WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al recibir el pago: " . $e->getMessage());
            return false;
        }
    }

    //Método para saldar Pago Terceros
    public function saldarPagoTerceros($data)
    {
        try {
            $sql = "UPDATE ventas_directas SET estado = 3 WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al recibir el pago: " . $e->getMessage());
            return false;
        }
    }

    //Método para cancelarVentaTerceros
    public function cancelarVentaTerceros($data)
    {
        try {
            $sql = "UPDATE ventas_directas SET estado = 5 WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $data['id'], PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al cancelar la venta: " . $e->getMessage());
            return false;
        }
    }

    //====================================================================================================
    //====================================== Data para Selects ===========================================
    //====================================================================================================

    //Método para obtener clientes
    public function listarclientes()
    {
        try {
            $sql = "SELECT c.idCliente, c.nombre, c.rtn, c.direccion, cp.precioVenta
            FROM clientes c
            INNER JOIN clientes_historial_preciosventa cp ON c.idCliente = cp.idCliente AND cp.estado = 1
            where c.estado = 1";

            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener los Clientes: " . $e->getMessage());
            return [];
        }
    }

    public function listarproductores()
    {
        try {
            // Primera consulta: obtener la cantidad vendida por lote
            $sqlCantidadVendida = "SELECT ct.id AS lote, 
                                          SUM(IFNULL(vd.cantidad, 0)) AS cantidad_vendida
                                   FROM compras_transporte ct
                                   LEFT JOIN ventas_detalle vd ON ct.id = vd.lote
                                   where vd.estado = 1
                                   GROUP BY ct.id";
            $stmtCantidadVendida = $this->pdo->query($sqlCantidadVendida);
            $cantidadesVendidas = $stmtCantidadVendida->fetchAll(PDO::FETCH_ASSOC);

            // Convertir los resultados en un array asociativo para fácil acceso
            $cantidadVendidaPorLote = [];
            foreach ($cantidadesVendidas as $fila) {
                $cantidadVendidaPorLote[$fila['lote']] = $fila['cantidad_vendida'];
            }

            // Segunda consulta: obtener los productores y calcular el peso bruto menos la cantidad vendida
            $sqlProductores = "SELECT cp.idProductor,
                                      cp.nombre,
                                      ct.id AS lote,
                                      ct.identificador,
                                      cpe.id_transporte,
                                      SUM(cpe.peso_bruto) AS peso_bruto
                               FROM compras_productores cp
                               INNER JOIN compras_transporte ct ON cp.idProductor = ct.id_productor
                               INNER JOIN compras_pesajes cpe ON ct.id = cpe.id_transporte
                               WHERE cp.estado = 1
                               GROUP BY ct.id";
            $stmtProductores = $this->pdo->query($sqlProductores);
            $productores = $stmtProductores->fetchAll(PDO::FETCH_ASSOC);

            // Calcular la cantidad disponible para cada lote
            foreach ($productores as &$productor) {
                $lote = $productor['lote'];
                $cantidadVendida = isset($cantidadVendidaPorLote[$lote]) ? $cantidadVendidaPorLote[$lote] : 0;
                $productor['cantidad_disponible'] = $productor['peso_bruto'] - $cantidadVendida;
            }

            return $productores;
        } catch (PDOException $e) {
            error_log("Error al obtener los Productores: " . $e->getMessage());
            return [$e->getMessage()];
        }
    }

    //Método para obtener motivos de traslado
    public function listarmotivostraslado()
    {
        try {
            $sql = "SELECT * FROM ventas_motivos_traslado";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener los motivos: " . $e->getMessage());
            return [];
        }
    }

    //Método para obtener tipos de certificación
    public function listartipoCertificacion()
    {
        try {
            $sql = "SELECT * FROM ventas_tipos_certificacion";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener los tipos de certificacion: " . $e->getMessage());
            return [];
        }
    }

    //Método para obtener certificacion
    public function listarcertificacion()
    {
        try {
            $sql = "SELECT * FROM ventas_certificaciones";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener las certificaciones: " . $e->getMessage());
            return [];
        }
    }

    //Método para obtener transportistas
    public function listartransportistas()
    {
        try {
            $sql = "SELECT * FROM ventas_configuracion_transportistas";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener transportistas: " . $e->getMessage());
            return [];
        }
    }

    //Método para obtener conductores
    public function listarconductores()
    {
        try {
            $sql = "SELECT * FROM ventas_configuracion_conductores"; // where idTransportista = :idTransportista
            $stmt = $this->pdo->prepare($sql);
            /* $stmt->bindParam(':idTransportista', $data['idTransportista'], PDO::PARAM_INT); */
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener conductores: " . $e->getMessage());
            return [];
        }
    }

    //Método para obtener vehiculos
    public function listarvehiculos()
    {
        try {
            $sql = "SELECT
                v.id AS vehiculo_id,
                v.placa,
                m.id AS marca_id,
                m.nombre AS marca_nombre
            FROM
                flota_vehiculos v
            JOIN
                flota_marcas m ON v.id_marca = m.id
            WHERE v.estado = 1;
            ";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener vehiculos: " . $e->getMessage());
            return [];
        }
    }

    //Método para obtener terceros
    public function listarProveedores()
    {
        try {
            $sql = "SELECT c.id, c.nombre, c.rtn, c.direccion, cp.preciocompra
            FROM contabilidad_proveedores c
            INNER JOIN contabilidad_historial_preciocompra cp ON c.id = cp.idProveedor AND cp.estado = 1
            where c.estado = 1";

            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener los contabilidad_proveedores: " . $e->getMessage());
            return [];
        }
    }











    //Método finalizar la venta
    public function finalizarventa($data)
    {
        try {
            // Verificar si nrecepcion o nrecepcionraqui ya existen con estado diferente de 5
            $sqlCheck = "SELECT COUNT(*) FROM ventas_directas 
                         WHERE (numrecepcion = :nrecepcion OR (nrecepcionraqui = :nrecepcionraqui AND nrecepcionraqui != '')) 
                         AND estado != 5";
            $stmtCheck = $this->pdo->prepare($sqlCheck);
            $stmtCheck->bindParam(':nrecepcion', $data['nrecepcion'], PDO::PARAM_INT);
            $stmtCheck->bindParam(':nrecepcionraqui', $data['nrecepcionraqui'], PDO::PARAM_INT);
            $stmtCheck->execute();
            $exists = $stmtCheck->fetchColumn();

            if ($exists > 0) {
                // Si ya existe, no permitir guardar
                return ["success" => false, "error" => "El número de recepción o el código RAQUI ya están registrados en una venta activa."];
            }

            // Si no existe, proceder con la actualización
            if (!empty($data['nrecepcionraqui'])) {
                $sql = "UPDATE ventas_directas 
                        SET estado = 2, numrecepcion = :nrecepcion, nrecepcionraqui = :nrecepcionraqui 
                        WHERE id = :id";
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':nrecepcionraqui', $data['nrecepcionraqui'], PDO::PARAM_INT);
            } else {
                $sql = "UPDATE ventas_directas 
                        SET estado = 2, numrecepcion = :nrecepcion 
                        WHERE id = :id";
                $stmt = $this->pdo->prepare($sql);
            }

            $stmt->bindParam(':id', $data['idVenta'], PDO::PARAM_INT);
            $stmt->bindParam(':nrecepcion', $data['nrecepcion'], PDO::PARAM_INT);
            $stmt->execute();

            
            //Insertar ventas_raqui: idVenta, identificador, cantidad, pcompra, estado, creadoPor, fechaCreado, modificadoPor, fechaModificado, sucursal

            $sqlRaqui = "INSERT INTO ventas_raqui (idVenta, identificador, flete, cantidad, pcompra, pventa, estado, creadoPor, fechaCreado, sucursal) 
                        VALUES (:idVenta, :identificador, :flete, :cantidad, :pcompra, :pventa, 1, 1, NOW(), 1)";
            $stmtRaqui = $this->pdo->prepare($sqlRaqui);
            $stmtRaqui->bindParam(':idVenta', $data['idVenta'], PDO::PARAM_INT);
            $stmtRaqui->bindParam(':identificador', $data['nrecepcionraqui'], PDO::PARAM_STR);
            $stmtRaqui->bindParam(':flete', $data['flete'], PDO::PARAM_STR);
            $stmtRaqui->bindParam(':cantidad', $data['cantidad'], PDO::PARAM_STR);
            $stmtRaqui->bindParam(':pcompra', $data['precioCompra'], PDO::PARAM_STR);
            $stmtRaqui->bindParam(':pventa', $data['precioVenta'], PDO::PARAM_STR);
            $stmtRaqui->execute();

            //modificar el estado de la tabla de trasporte
            $sqlTransporte = "UPDATE `flota_vehiculos` SET `estado` = 1 WHERE `placa` = :placa";
            $stmtTransporte = $this->pdo->prepare($sqlTransporte);
            $stmtTransporte->bindParam(':placa', $data['placa'], PDO::PARAM_STR);
            $stmtTransporte->execute();

            return ["success" => true];
        } catch (PDOException $e) {
            error_log("Error al actualizar la venta: " . $e->getMessage());
            return ["success" => false, "error" => "Error al actualizar la venta: " . $e->getMessage()];
        }
    }


    // Método para obtener los abonos de una venta
    public function listarSaldo($datos)
    {
        try {
            $sql = "SELECT IFNULL(SUM(abono), 0) as total FROM ventas_abonos WHERE idVenta = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $datos['idVenta'], PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener los abonos: " . $e->getMessage());
            return [];
        }
    }

    //Método para recibir pago
    public function recibirpago($data)
    {
        try {
            // Obtener el valor total de la venta desde la tabla ventas
            $sql = "SELECT monto as total FROM ventas_directas WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $data['idVenta'], PDO::PARAM_INT);
            $stmt->execute();
            $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
            // Obtener el total de los abonos realizados
            $sql = "SELECT IFNULL(SUM(abono), 0) as total FROM ventas_abonos WHERE idVenta = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $data['idVenta'], PDO::PARAM_INT);
            $stmt->execute();
            $abonos = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
            // Verificar si el monto es mayor que el total de la venta
            if (($data['monto'] + $abonos) > $total) {
                // Retornar error
                $excedente = ($data['monto'] + $abonos) - $total;
                return ["success" => false, "error" => "El monto se encuentra por encima del total de la venta por: $excedente"];
            } else {
                // Insertar el pago en la tabla ventas_abonos
                $sql = "INSERT INTO ventas_abonos (idVenta, metodo, abono, estado, fechaCreado) 
                        VALUES (:id, :metodo, :monto, :estado, NOW())";
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':id', $data['idVenta'], PDO::PARAM_INT);
                $stmt->bindParam(':metodo', $data['metodo'], PDO::PARAM_INT);
                $stmt->bindParam(':monto', $data['monto'], PDO::PARAM_STR);
                $stmt->bindParam(':estado', $data['pago'], PDO::PARAM_STR);
                $stmt->execute();
    
                // Sumar el nuevo abono al total acumulado
                $abonos += $data['monto'];
    
                // Si el total de abonos es igual al valor de la venta, actualizar el estado de la venta a 1 (pagada)
                if ($data['pago'] == 2 || $abonos == $total) {
                    $sql = "UPDATE ventas_directas SET estado = 1 WHERE id = :id";
                    $stmt = $this->pdo->prepare($sql);
                    $stmt->bindParam(':id', $data['idVenta'], PDO::PARAM_INT);
                    $stmt->execute();
                }
    
                // Retornar éxito
                return ["success" => true];
            }
        } catch (PDOException $e) {
            error_log("Error al recibir el pago: " . $e->getMessage());
            return ["success" => false, "error" => "Error al recibir el pago: " . $e->getMessage()];
        }
    }


    //Método finalizar la venta
    public function cancelarventa($data)
    {
        try {
            $sql = "UPDATE ventas SET estado = 5 WHERE idVenta = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $data['idVenta'], PDO::PARAM_INT);

            // Modificar el estado de la tabla de transporte
            $sqlTransporte = "UPDATE `flota_vehiculos` SET `estado` = 1 WHERE `placa` = :placa";
            $stmtTransporte = $this->pdo->prepare($sqlTransporte);
            $stmtTransporte->bindParam(':placa', $data['placa'], PDO::PARAM_STR);
            $stmtTransporte->execute();

            // Modificar el estado de la tabla venta_detalle
            $sqlDetalle = "UPDATE ventas_detalle SET estado = 0 WHERE idVenta = :idVenta";
            $stmtDetalle = $this->pdo->prepare($sqlDetalle);
            $stmtDetalle->bindParam(':idVenta', $data['idVenta'], PDO::PARAM_INT);
            $stmtDetalle->execute();

            return $stmt->execute();
        } catch (PDOException $e) {
            die("Error al actualizar la venta: " . $e->getMessage());
        }
    }




    //Método para obtener datos de ventas directas para editar

    public function listarventadirecta($id)
    {
        try {
            $sql = "SELECT 
            vd.*,
            vt.*,
            c.nombre AS nombreCliente, c.idCliente,
            p.nombre AS nombreProductor, p.id AS idProductor,
            vc.nombre AS nombreConductor, vc.idConductor
            FROM ventas_directas vd
            LEFT JOIN ventas_transporte vt ON vd.id = vt.idVenta
            LEFT JOIN clientes c ON vd.idCliente = c.idCliente
            LEFT JOIN contabilidad_proveedores p ON vd.idproveedor = p.id
            LEFT JOIN ventas_configuracion_conductores vc ON vt.idConductor = vc.idConductor
            WHERE vd.id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener la venta directa: " . $e->getMessage());
            return [];
        }
    }

    //Método para obtener datos de ventas directas para editar

    public function listarpagosventa($id)
    {
        try {
            $sql = "SELECT
                va.abono, va.fechaCreado,
                cm.descripcion
                FROM ventas_abonos va
                INNER JOIN contabilidad_metodosPago cm ON va.metodo = cm.id
                WHERE va.idVenta = :id
                ORDER BY va.fechaCreado DESC
            ";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC); // ← CAMBIADO
        } catch (PDOException $e) {
            error_log("Error al obtener la venta directa: " . $e->getMessage());
            return [];
        }
    }
    
}
