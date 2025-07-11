<?php
class Vehiculo {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    //Método para guardar un nuevo vehículo
    public function guardarVehiculo($id_marca, $id_modelo, $placa, $anio, $color, $id_tipo_vehiculo, $id_pertenencia, $kilometraje_actual, $intervalo_mantenimiento) {
        try {
            $creado_por=1;
            $sql = "INSERT INTO flota_vehiculos (id_marca, id_modelo, placa, anio, color, id_tipo_vehiculo, id_pertenencia, kilometraje_actual, intervalo_mantenimiento, creado_por) 
                        VALUES (:id_marca, :id_modelo, :placa, :anio, :color, :id_tipo_vehiculo, :id_pertenencia, :kilometraje_actual, :intervalo_mantenimiento, :creado_por)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id_marca', $id_marca, PDO::PARAM_INT);
            $stmt->bindParam(':id_modelo', $id_modelo, PDO::PARAM_INT);
            $stmt->bindParam(':placa', $placa, PDO::PARAM_STR);
            $stmt->bindParam(':anio', $anio, PDO::PARAM_INT);
            $stmt->bindParam(':color', $color, PDO::PARAM_STR);
            $stmt->bindParam(':id_tipo_vehiculo', $id_tipo_vehiculo, PDO::PARAM_INT);
            $stmt->bindParam(':id_pertenencia', $id_pertenencia, PDO::PARAM_INT);
            $stmt->bindParam(':kilometraje_actual', $kilometraje_actual, PDO::PARAM_INT);
            $stmt->bindParam(':intervalo_mantenimiento', $intervalo_mantenimiento, PDO::PARAM_INT);
            $stmt->bindParam(':creado_por', $creado_por, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            if ($e->errorInfo[1] == 1062) { // Código de error de duplicado UNIQUE
                throw new PDOException(1062);
            }else {
                throw new PDOException("Error al guardar el vehículo: " . $e->getMessage());
            }
        }
    }

    // Método para editar un vehículo
    public function editarVehiculo($id, $id_marca, $id_modelo, $placa, $anio, $color, $id_tipo_vehiculo, $id_pertenencia, $intervalo_mantenimiento) {
        try {
            $modificado_por=1;
            $sql = " UPDATE flota_vehiculos SET id_marca = :id_marca, id_modelo = :id_modelo, placa = :placa, anio = :anio, color = :color, id_tipo_vehiculo = :id_tipo_vehiculo, id_pertenencia = :id_pertenencia, intervalo_mantenimiento = :intervalo_mantenimiento, modificado_por=:modificado_por, fecha_modificado = CURRENT_TIMESTAMP WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id_marca', $id_marca, PDO::PARAM_INT);
            $stmt->bindParam(':id_modelo', $id_modelo, PDO::PARAM_INT);
            $stmt->bindParam(':placa', $placa, PDO::PARAM_STR);
            $stmt->bindParam(':anio', $anio, PDO::PARAM_INT);
            $stmt->bindParam(':color', $color, PDO::PARAM_STR);
            $stmt->bindParam(':id_tipo_vehiculo', $id_tipo_vehiculo, PDO::PARAM_INT);
            $stmt->bindParam(':id_pertenencia', $id_pertenencia, PDO::PARAM_INT);
            $stmt->bindParam(':intervalo_mantenimiento', $intervalo_mantenimiento, PDO::PARAM_INT);
            $stmt->bindParam(':modificado_por', $modificado_por, PDO::PARAM_INT);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            if ($e->errorInfo[1] == 1062) { // Código de error de duplicado UNIQUE
                throw new PDOException(1062);
            }else {
                throw new PDOException("Error al guardar el vehículo: " . $e->getMessage());
            }
        }
    }

    //Método para eliminar un vehículo por ID
    public function cambiarEstadoVehiculo($id, $estado = 4) {
        try {
            $modificado_por=1;
            $sql = "UPDATE flota_vehiculos SET estado=:estado, modificado_por=:modificado_por, fecha_modificado=CURRENT_TIMESTAMP WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':modificado_por', $modificado_por, PDO::PARAM_INT);
            $stmt->bindParam(':estado', $estado, PDO::PARAM_INT);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            die("Error al eliminar el registro: " . $e->getMessage());
        }
    }

    //Método para obtener todos los vehículos
    public function obtenerVehiculos() {
        try {
            $sql = "SELECT id, placa, marca, modelo, anio, tipo_vehiculo, estado, nombre_estado, pertenencia, kilometraje_actual, CASE WHEN estado=2 THEN (SELECT nRemision FROM ventas v INNER JOIN ventas_transporte t ON t.idVenta=v.idVenta WHERE t.placa = veh.placa AND v.estado IN (1,2) LIMIT 1) ELSE NULL END AS infoVenta
                    FROM flota_vw_vehiculos_todos veh;";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener los vehículos: " . $e->getMessage());
        }
    }

    //Método para obtener la cantidad de vehículos por estado
    public function cantidadVehiculosPorEstados() {
        try {
            $sql = "SELECT e.id AS estado, e.descripcion as nombre_estado, COALESCE(COUNT(v.estado), 0) AS total
                    FROM flota_estado_vehiculo e
                    LEFT JOIN flota_vw_vehiculos_todos v ON v.estado = e.id
                    GROUP BY e.id;";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener los vehículos: " . $e->getMessage());
        }
    }

    //Método para obtener los modelos en base a la marca
    public function obtenerModelosPorMarca($id_marca) {
        try {
            $sql = "SELECT id, nombre FROM flota_modelos WHERE id_marca=:id_marca AND estado = 1";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id_marca', $id_marca, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener los modelos " . $e->getMessage());
        }
    }

    //Método para obtener vehículo por id
    public function obtenerVehiculoId($id) {
        try {
            $sql = "SELECT id, placa, id_marca, id_modelo, anio, color, id_tipo_vehiculo, estado, nombre_estado, id_pertenencia, kilometraje_actual, intervalo_mantenimiento
                    FROM flota_vw_vehiculos_todos WHERE id=:id;";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener el vehículo: " . $e->getMessage());
        }
    }

    public function obtenerInspecciones() {
        try {
            $sql = "SELECT i.id, i.id_vehiculo, v.placa, v.infoVehiculo, i.fecha, i.kilometraje, i.observaciones FROM flota_inspecciones i
                    INNER JOIN flota_vw_vehiculos_todos v ON i.id_vehiculo=v.id;";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener las inspecciones: " . $e->getMessage());
        }
    }

    //Método para guardar una inspección
    public function guardarInspeccion($id_vehiculo, $kilometraje, $observaciones){
        try {
            // Iniciar la transacción
            $this->pdo->beginTransaction();

            $creado_por=1;
            $sql = "INSERT INTO flota_inspecciones (id_vehiculo, kilometraje, observaciones, fecha, creado_por) 
                        VALUES (:id_vehiculo, :kilometraje, :observaciones, now(), :creado_por)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id_vehiculo', $id_vehiculo, PDO::PARAM_INT);
            $stmt->bindParam(':kilometraje', $kilometraje, PDO::PARAM_INT);
            $stmt->bindParam(':observaciones', $observaciones, PDO::PARAM_STR);
            $stmt->bindParam(':creado_por', $creado_por, PDO::PARAM_INT);
            $stmt->execute();

            // actualizar el kilometraje del vehículo
            $sqlVehiculo = "UPDATE flota_vehiculos SET kilometraje_actual = :kilometraje WHERE id = :id_vehiculo";
            $stmtVehiculo = $this->pdo->prepare($sqlVehiculo);
            $stmtVehiculo->bindParam(':kilometraje', $kilometraje, PDO::PARAM_INT);
            $stmtVehiculo->bindParam(':id_vehiculo', $id_vehiculo, PDO::PARAM_INT);
            $stmtVehiculo->execute();

            // insertar el registro en la tabla de kilometrajes
            $sqlKilometraje = "INSERT INTO flota_kilometraje_vehiculo (id_vehiculo, kilometraje, fecha_registro, id_tipo_registro, fecha_creado, creado_por) 
                                        VALUES (:id_vehiculo, :kilometraje, CURRENT_TIMESTAMP, 3, CURRENT_TIMESTAMP, :creado_por)";
            $stmtKilometraje = $this->pdo->prepare($sqlKilometraje);
            $stmtKilometraje->bindParam(':id_vehiculo', $id_vehiculo, PDO::PARAM_INT);
            $stmtKilometraje->bindParam(':kilometraje', $kilometraje, PDO::PARAM_INT);
            $stmtKilometraje->bindParam(':creado_por', $creado_por, PDO::PARAM_INT);
            $stmtKilometraje->execute();

            // Confirmar la transacción
            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
           // Rollback por si ocurre un error
            $this->pdo->rollBack();
            throw new PDOException("Error al guardar la inspección: " . $e->getMessage());
        }
    }

    public function obtenerOdometros() {
        try {
            $sql = "SELECT kv.id, kv.id_vehiculo, v.placa, v.infoVehiculo, kv.fecha_registro, kv.kilometraje, tk.nombre as tipo_registro FROM flota_kilometraje_vehiculo kv
                    INNER JOIN flota_vw_vehiculos_todos v ON kv.id_vehiculo=v.id
                    INNER JOIN flota_tipo_kilometraje tk ON kv.id_tipo_registro=tk.id;";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener los odómetros: " . $e->getMessage());
        }
    }

    public function obtenerAlertasMantenimientos() {
        try {
            $sql = "SELECT * FROM flota_vw_control_alerta_mantenimiento WHERE alerta=1;";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener las alertas: " . $e->getMessage());
        }
    }
}
?>
