<?php
class Mantenimiento {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    //Método para obtener los tipos de mantenimeinto
    public function obtenerTiposMantenimiento() {
        try {
            $sql = "SELECT id, nombre FROM flota_tipo_mantenimiento WHERE estado = 1";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener los tipos de mantenimiento " . $e->getMessage());
        }
    }

    //Método para obtener los tipos de servicio
    public function obtenerTiposServicio() {
        try {
            $sql = "SELECT id, nombre FROM flota_tipo_servicio";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener los tipos de servicio " . $e->getMessage());
        }
    }

    //Método para obtener los talleres
    public function obtenerTalleres() {
        try {
            $sql = "SELECT id, nombre FROM flota_taller WHERE estado = 1";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener los talleres " . $e->getMessage());
        }
    }

    //Método para obtener catalogo por tipo
    public function obtenerCatalogoPorTipo($id_tipo_mantenimiento) {
        try {
            $sql = "SELECT id, nombre FROM flota_catalogo WHERE estado = 1 AND id_tipo_mantenimiento=:id_tipo_mantenimiento";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id_tipo_mantenimiento', $id_tipo_mantenimiento, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener el catálogo " . $e->getMessage());
        }
    }

     //Método para obtener las bodegas
     public function obtenerBodegas() {
        try {
            $sql = "SELECT id, NombreBodega as nombre FROM inventario_Bodegas WHERE Estado = 1";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener las bodegas " . $e->getMessage());
        }
    }

    //Método para obtener los articulos
    public function obtenerArticulos() {
        try {
            $sql = "SELECT a.id, a.nombreArticulo AS nombre, IFNULL(u.nombre, '--') AS unidad FROM inventario_Articulos a
                    LEFT JOIN inventario_Unidad_de_medida u ON a.idUnidadMedida=u.id
                    WHERE a.Estado = 1";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener los insumos " . $e->getMessage());
        }
    }

    //Método para obtener los articulos y existencias por bodega
    public function obtenerArticulosPorBodega($id_bodega) {
        try {
            $sql = "SELECT idArticulo as id, NombreArticulo as nombre, IFNULL(UnidadMedida, '--') AS unidad, Existencia as existencia 
                        FROM mddesarr_fptrax.inventario_vw_InventarioActual 
                    WHERE idBodega=:id_bodega;";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id_bodega', $id_bodega, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener los insumos " . $e->getMessage());
        }
    }

    //Método para guardar un mantenimiento
    public function guardarMantenimiento($mantenimiento) {
        try {
            $creado_por=1;

            // Iniciar la transacción
            $this->pdo->beginTransaction();

            // Insertar encabezado
            $sql = "INSERT INTO flota_mantenimiento (id_vehiculo, id_tipo_mantenimiento, id_tipo_servicio, kilometraje_vehiculo, fecha_inicio, comentarios, id_taller, id_bodega, creado_por) 
                        VALUES (:id_vehiculo, :id_tipo_mantenimiento, :id_tipo_servicio, :kilometraje_vehiculo, :fecha_inicio, :comentarios, :id_taller, :id_bodega, :creado_por)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id_vehiculo', $mantenimiento->id_vehiculo, PDO::PARAM_INT);
            $stmt->bindParam(':id_tipo_mantenimiento', $mantenimiento->id_tipo_mantenimiento, PDO::PARAM_INT);
            $stmt->bindParam(':id_tipo_servicio', $mantenimiento->id_tipo_servicio, PDO::PARAM_INT);
            $stmt->bindParam(':kilometraje_vehiculo', $mantenimiento->kilometraje_vehiculo, PDO::PARAM_INT);
            $stmt->bindParam(':fecha_inicio', $mantenimiento->fecha_inicio, PDO::PARAM_STR);
            $stmt->bindParam(':comentarios', $mantenimiento->comentarios, PDO::PARAM_STR);
            $stmt->bindParam(':id_taller', $mantenimiento->id_taller, PDO::PARAM_INT);
            $stmt->bindParam(':id_bodega', $mantenimiento->id_bodega, PDO::PARAM_INT);
            $stmt->bindParam(':creado_por', $creado_por, PDO::PARAM_INT);
            $stmt->execute();

             // Recuperar el id del mantenimiento
            $id_mantenimiento = $this->pdo->lastInsertId();

            // Insertar detalles
            $sqlDetalle = "INSERT INTO flota_mantenimiento_detalle (id_mantenimiento, id_catalogo, costo, creado_por) 
                            VALUES (:id_mantenimiento, :id_catalogo, :costo, :creado_por)";
            $stmtDetalle = $this->pdo->prepare($sqlDetalle);

            $detalleMantenimiento = $mantenimiento->trabajos;

            foreach ($detalleMantenimiento as $detalle) {
                $stmtDetalle->bindValue(':id_mantenimiento', $id_mantenimiento, PDO::PARAM_INT);
                $stmtDetalle->bindValue(':id_catalogo', $detalle['id'], PDO::PARAM_INT);
                $stmtDetalle->bindValue(':costo', $detalle['costo'], PDO::PARAM_STR);
                $stmtDetalle->bindValue(':creado_por', $creado_por, PDO::PARAM_INT);
                $stmtDetalle->execute();
            }

            $insumosMantenimiento = $mantenimiento->insumos;
            if (!empty($insumosMantenimiento)) {
                // Insertar insumos Mantenimiento
                $sqlInsumos = "INSERT INTO flota_mantenimiento_insumos_OT (id_mantenimiento, id_articulo, cantidad, creado_por) 
                            VALUES (:id_mantenimiento, :id_articulo, :cantidad, :creado_por)";
                $stmtInsumos = $this->pdo->prepare($sqlInsumos);

                foreach ($insumosMantenimiento as $insumo) {
                    $stmtInsumos->bindValue(':id_mantenimiento', $id_mantenimiento, PDO::PARAM_INT);
                    $stmtInsumos->bindValue(':id_articulo', $insumo['id'], PDO::PARAM_INT);
                    $stmtInsumos->bindValue(':cantidad', $insumo['cantidad'], PDO::PARAM_STR);
                    $stmtInsumos->bindValue(':creado_por', $creado_por, PDO::PARAM_INT);
                    $stmtInsumos->execute();
                }
            }

            // Confirmar la transacción
            $this->pdo->commit();
            return true;


        } catch (PDOException $e) {
            // Rollback por si ocurre un error
            $this->pdo->rollBack();
            throw new PDOException("Error al guardar el mantenimiento: " . $e->getMessage());
        }
    }

    public function editarMantenimiento($mantenimiento) {
        try {
            $modificado_por = 1;
            $this->pdo->beginTransaction();
    
            // Actualizar encabezado
            $sql = "UPDATE flota_mantenimiento 
                    SET id_vehiculo = :id_vehiculo, 
                        id_tipo_mantenimiento = :id_tipo_mantenimiento,
                        id_tipo_servicio = :id_tipo_servicio,
                        kilometraje_vehiculo = :kilometraje_vehiculo,
                        fecha_inicio = :fecha_inicio,
                        comentarios = :comentarios,
                        id_taller = :id_taller,
                        id_bodega = :id_bodega,
                        modificado_por = :modificado_por,
                        fecha_modificado = CURRENT_TIMESTAMP
                    WHERE id = :id_mantenimiento";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id_vehiculo', $mantenimiento->id_vehiculo, PDO::PARAM_INT);
            $stmt->bindParam(':id_tipo_mantenimiento', $mantenimiento->id_tipo_mantenimiento, PDO::PARAM_INT);
            $stmt->bindParam(':id_tipo_servicio', $mantenimiento->id_tipo_servicio, PDO::PARAM_INT);
            $stmt->bindParam(':kilometraje_vehiculo', $mantenimiento->kilometraje_vehiculo, PDO::PARAM_INT);
            $stmt->bindParam(':fecha_inicio', $mantenimiento->fecha_inicio, PDO::PARAM_STR);
            $stmt->bindParam(':comentarios', $mantenimiento->comentarios, PDO::PARAM_STR);
            $stmt->bindParam(':id_taller', $mantenimiento->id_taller, PDO::PARAM_INT);
            $stmt->bindParam(':id_bodega', $mantenimiento->id_bodega, PDO::PARAM_INT);
            $stmt->bindParam(':modificado_por', $modificado_por, PDO::PARAM_INT);
            $stmt->bindParam(':id_mantenimiento', $mantenimiento->id_mantenimiento, PDO::PARAM_INT);
            $stmt->execute();
    
            // Insertar trabajos nuevos
            if (!empty($mantenimiento->trabajosNuevos)) {
                $sql = "INSERT INTO flota_mantenimiento_detalle (id_mantenimiento, id_catalogo, costo, creado_por) 
                        VALUES (:id_mantenimiento, :id_catalogo, :costo, :creado_por)";
                $stmt = $this->pdo->prepare($sql);
                foreach ($mantenimiento->trabajosNuevos as $trabajo) {
                    $stmt->bindValue(':id_mantenimiento', $mantenimiento->id_mantenimiento, PDO::PARAM_INT);
                    $stmt->bindValue(':id_catalogo', $trabajo['id_catalogo'], PDO::PARAM_INT);
                    $stmt->bindValue(':costo', $trabajo['costo'], PDO::PARAM_STR);
                    $stmt->bindValue(':creado_por', $modificado_por, PDO::PARAM_INT);
                    $stmt->execute();
                }
            }
    
            // Eliminar trabajos
            if (!empty($mantenimiento->trabajosEliminados)) {
                $sqlUpdateTrabajo = "UPDATE flota_mantenimiento_detalle 
                                     SET estado = 2, modificado_por = :modificado_por, fecha_modificado = CURRENT_TIMESTAMP 
                                     WHERE id = :id";
                $stmtUpdateTrabajo = $this->pdo->prepare($sqlUpdateTrabajo);
                foreach ($mantenimiento->trabajosEliminados as $trabajo) {
                    $stmtUpdateTrabajo->bindValue(':id', $trabajo['id'], PDO::PARAM_INT);
                    $stmtUpdateTrabajo->bindValue(':modificado_por', $modificado_por, PDO::PARAM_INT);
                    $stmtUpdateTrabajo->execute();
                }
            }
    
            // Insertar insumos nuevos
            if (!empty($mantenimiento->insumosNuevos)) {
                $sql = "INSERT INTO flota_mantenimiento_insumos_OT (id_mantenimiento, id_articulo, cantidad, creado_por)
                        VALUES (:id_mantenimiento, :id_articulo, :cantidad, :creado_por)";
                $stmt = $this->pdo->prepare($sql);
                foreach ($mantenimiento->insumosNuevos as $insumo) {
                    $stmt->bindValue(':id_mantenimiento', $mantenimiento->id_mantenimiento, PDO::PARAM_INT);
                    $stmt->bindValue(':id_articulo', $insumo['id_articulo'], PDO::PARAM_INT);
                    $stmt->bindValue(':cantidad', $insumo['cantidad'], PDO::PARAM_STR);
                    $stmt->bindValue(':creado_por', $modificado_por, PDO::PARAM_INT);
                    $stmt->execute();
                }
            }
    
            // Eliminar insumos
            if (!empty($mantenimiento->insumosEliminados)) {
                $sqlUpdateInsumo = "UPDATE flota_mantenimiento_insumos_OT 
                                    SET estado = 2, modificado_por = :modificado_por, fecha_modificado = CURRENT_TIMESTAMP
                                    WHERE id = :id";
                $stmtUpdateInsumo = $this->pdo->prepare($sqlUpdateInsumo);
                foreach ($mantenimiento->insumosEliminados as $insumo) {
                    $stmtUpdateInsumo->bindValue(':id', $insumo['id'], PDO::PARAM_INT);
                    $stmtUpdateInsumo->bindValue(':modificado_por', $modificado_por, PDO::PARAM_INT);
                    $stmtUpdateInsumo->execute();
                }
            }
    
            $this->pdo->commit();
            return true;
    
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            throw new PDOException("Error al editar mantenimiento: " . $e->getMessage());
        }
    }


    //Método para obtener todos los mantenimeintos
    public function obtenerMantenimientosTodos() {
        try {
            $sql = "SELECT * FROM flota_vw_mantenimientos_todos";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener los tipos de mantenimiento " . $e->getMessage());
        }
    }

    //Método para aprobar mantenimiento
    public function aprobarMantenimiento($id, $id_vehiculo, $kilometrajeMantenimiento, $kilometrajeVehiculo) {
        try {
            $creado_por=1;
            $sql = "CALL flota_sp_aprobar_mantenimiento(:idMantenimiento, :usuario)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':idMantenimiento', $id, PDO::PARAM_INT);
            $stmt->bindParam(':usuario', $creado_por, PDO::PARAM_INT);

            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            $stmt->closeCursor();

            if($result['tipo'] == 'SUCCESS') {
                try {
                    // Iniciar la transacción
                    $this->pdo->beginTransaction();
        
                    // Actualizar la ficha del vehiculo con el nuevo kilometraje del mantenimiento
                    $sqlVehiculo = "UPDATE flota_vehiculos SET kilometraje_actual = :kilometraje_mantenimiento WHERE id = :id_vehiculo";
                    $stmtVehiculo = $this->pdo->prepare($sqlVehiculo);
                    $stmtVehiculo->bindParam(':id_vehiculo', $id_vehiculo, PDO::PARAM_INT);
                    $stmtVehiculo->bindParam(':kilometraje_mantenimiento', $kilometrajeMantenimiento, PDO::PARAM_INT);
                    $stmtVehiculo->execute();

                    // Insertar el registro en la tabla de kilometraje vehiculo
                    $sqlKilometraje = "INSERT INTO flota_kilometraje_vehiculo (id_vehiculo, kilometraje, fecha_registro, id_tipo_registro, fecha_creado, creado_por) 
                                        VALUES (:id_vehiculo, :kilometrajeMantenimiento, CURRENT_TIMESTAMP, 2, CURRENT_TIMESTAMP, :creado_por)";
                    $stmtKilometraje = $this->pdo->prepare($sqlKilometraje);
                    $stmtKilometraje->bindParam(':id_vehiculo', $id_vehiculo, PDO::PARAM_INT);
                    $stmtKilometraje->bindParam(':kilometrajeMantenimiento', $kilometrajeMantenimiento, PDO::PARAM_INT);
                    $stmtKilometraje->bindParam(':creado_por', $creado_por, PDO::PARAM_INT);
                    $stmtKilometraje->execute();

                   // Confirmar la transacción
                   $this->pdo->commit();
        
                } catch (PDOException $er) {
                    // Rollback por si ocurre un error
                    $this->pdo->rollBack();
                    throw new PDOException("Error al aprobar el mantenimiento: " . $er->getMessage());
                }
            }

            return [
                'status' => $result['tipo'],
                'message' => $result['mensaje'],
                'id_salida' => $result['id_salida'] ?? null
            ];
        } catch (PDOException $e) {
            return [
                'status' => 'ERROR',
                'message' => 'Error al ejecutar el procedimiento: ' . $e->getMessage(),
                'id_salida' => null
            ];
        }
    }

    //Método para ejecutar mantenimiento
    public function ejecutarMantenimiento($id, $id_vehiculo) {
        try {
            $modificado_por=1;

            // Iniciar la transacción
            $this->pdo->beginTransaction();

            // Actualizar estado mantenimiento
            $sql = "UPDATE flota_mantenimiento SET estado = 3, modificado_por=:modificado_por, fecha_modificado=CURRENT_TIMESTAMP WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':modificado_por', $modificado_por, PDO::PARAM_INT);
            $stmt->execute();

            // Actualizar estado vehículo
            $sqlVehiculo = "UPDATE flota_vehiculos SET estado = 3 WHERE id = :id_vehiculo";
            $stmtVehiculo = $this->pdo->prepare($sqlVehiculo);
            $stmtVehiculo->bindParam(':id_vehiculo', $id_vehiculo, PDO::PARAM_INT);
            $stmtVehiculo->execute();

           // Confirmar la transacción
           $this->pdo->commit();
           return true;

        } catch (PDOException $e) {
            // Rollback por si ocurre un error
            $this->pdo->rollBack();
            throw new PDOException("Error al iniciar el mantenimiento: " . $e->getMessage());
        }
    }

    //Método para finalizaar mantenimiento
    public function finalizarMantenimiento($id, $id_vehiculo) {
        try {
            $modificado_por=1;

            // Iniciar la transacción
            $this->pdo->beginTransaction();

            // Actualizar estado mantenimiento
            $sql = "UPDATE flota_mantenimiento SET estado = 4, modificado_por=:modificado_por, fecha_modificado=CURRENT_TIMESTAMP WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':modificado_por', $modificado_por, PDO::PARAM_INT);
            $stmt->execute();

            // Actualizar estado vehículo
            $sqlVehiculo = "UPDATE flota_vehiculos SET estado = 1 WHERE id = :id_vehiculo";
            $stmtVehiculo = $this->pdo->prepare($sqlVehiculo);
            $stmtVehiculo->bindParam(':id_vehiculo', $id_vehiculo, PDO::PARAM_INT);
            $stmtVehiculo->execute();

           // Confirmar la transacción
           $this->pdo->commit();
           return true;

        } catch (PDOException $e) {
            // Rollback por si ocurre un error
            $this->pdo->rollBack();
            throw new PDOException("Error al completar el mantenimiento: " . $e->getMessage());
        }
    }

    //Método para obtener la cantidad de mantenimientos por estado
    public function cantidadMantenimientosPorEstados() {
        try {
            $sql = "SELECT e.id AS estado, e.descripcion as nombre_estado, COALESCE(COUNT(m.estado), 0) AS total
                    FROM flota_estado_mantenimiento e
                    LEFT JOIN flota_vw_mantenimientos_todos m ON m.estado = e.id
                    GROUP BY e.id;";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener los estados: " . $e->getMessage());
        }
    }

    //Método para rechazar mantenimiento
    public function rechazarMantenimiento($id, $motivo_rechazo) {
        try {
            $rechazado_por=1;

            // Iniciar la transacción
            $this->pdo->beginTransaction();

            // Actualizar estado mantenimiento
            $sql = "UPDATE flota_mantenimiento SET estado = 5, rechazado_por=:rechazado_por, fecha_rechazado=CURRENT_TIMESTAMP, motivo_rechazo=:motivo_rechazo WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':rechazado_por', $rechazado_por, PDO::PARAM_INT);
            $stmt->bindParam(':motivo_rechazo', $motivo_rechazo, PDO::PARAM_STR);
            $stmt->execute();

           // Confirmar la transacción
           $this->pdo->commit();
           return true;

        } catch (PDOException $e) {
            // Rollback por si ocurre un error
            $this->pdo->rollBack();
            throw new PDOException("Error al rechazar el mantenimiento: " . $e->getMessage());
        }
    }

    //Método para obtener mantenimeinto por id
    public function obtenerMantenimientoPorId($id) {
        try {
            $sql = "SELECT * FROM flota_vw_mantenimientos_todos WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $encabezado = $stmt->fetch(PDO::FETCH_ASSOC);

            $sqlDetalle = "SELECT md.id, md.id_mantenimiento, md.id_catalogo, md.costo, c.nombre as nombre_catalogo FROM mddesarr_fptrax.flota_mantenimiento_detalle AS md
                            INNER JOIN flota_catalogo AS c ON md.id_catalogo=c.id 
                            WHERE md.id_mantenimiento = :id AND md.estado=1";
            $stmtDetalle = $this->pdo->prepare($sqlDetalle);
            $stmtDetalle->bindParam(':id', $id, PDO::PARAM_INT);
            $stmtDetalle->execute();
            $detalle = $stmtDetalle->fetchAll(PDO::FETCH_ASSOC);

            if($encabezado['id_tipo_servicio'] == 1){
                $sqlInsumos = "SELECT mi.id, mi.id_mantenimiento, mi.id_articulo, mi.cantidad, a.NombreArticulo AS articulo, IFNULL(u.nombre, '--') AS unidad FROM flota_mantenimiento_insumos_OT mi
                                INNER JOIN inventario_Articulos a ON mi.id_articulo=a.id
                                LEFT JOIN inventario_Unidad_de_medida u ON a.idUnidadMedida = u.id
                                WHERE mi.id_mantenimiento = :id AND mi.estado=1";
                $stmtInsumos = $this->pdo->prepare($sqlInsumos);
                $stmtInsumos->bindParam(':id', $id, PDO::PARAM_INT);
                $stmtInsumos->execute();
                $insumos = $stmtInsumos->fetchAll(PDO::FETCH_ASSOC);
            }
            
            $response=[
                'encabezado' => $encabezado,
                'detalle' => $detalle,
                'insumos' => $insumos ?? [],
            ];
            return $response;
        } catch (PDOException $e) {
            die("Error al obtener el mantenimiento " . $e->getMessage());
        }
    }
    
    public function cantidadOTPorEstados() {
        try {
            $sql = "SELECT e.id AS estado, e.descripcion as nombre_estado, COALESCE(COUNT(m.estado), 0) AS total
                    FROM flota_estado_mantenimiento e
                    LEFT JOIN flota_vw_mantenimientos_todos m ON m.estado = e.id AND m.id_tipo_servicio = 1
                    GROUP BY e.id;";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener los estados: " . $e->getMessage());
        }
    }

    //Método para obtener todas las OT
    public function obtenerOTTodas() {
        try {
            $sql = "SELECT * FROM flota_vw_mantenimientos_todos WHERE id_tipo_servicio = 1";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener los tipos de mantenimiento " . $e->getMessage());
        }
    }
}
?>
