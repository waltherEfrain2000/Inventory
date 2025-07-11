<?php
class mdlcomprasAcopio
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }
    // Método para guardar un productor
    public function guardarProductor($losDatos)
    {
        $estado = 1; //Tabla configuracion_estados 1 es activo y 0 inactivo
        $id_usuario = 1;
        try {
            $sql = "INSERT INTO compras_productores
                    (nombre,identificacion,direccion,estado,creadoPor,fechaCreado)
                    VALUES (:nombre,:identidad,:direccion,:estado,:creadoPor,NOW());";
            $stmt = $this->pdo->prepare($sql);

            $stmt->bindParam(':nombre', $losDatos->nombre, PDO::PARAM_STR);
            $stmt->bindParam(':identidad', $losDatos->identidad, PDO::PARAM_STR);
            $stmt->bindParam(':direccion', $losDatos->direccion, PDO::PARAM_STR);
            $stmt->bindParam(':estado', $estado, PDO::PARAM_INT);
            $stmt->bindParam(':creadoPor', $id_usuario, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            die("Error al guardar datos del productor: " . $e->getMessage());
        }
    }
    // Método para guardar un peso bruto
    public function guardarDescarga($losDatos)
    {
        $estado = 2;
        $id_usuario = 1;
        try {
            $sql = "INSERT INTO compras_pesajes (id_transporte, peso_bruto,estado,creadoPor)
                    VALUES 						(:id_transporte,:peso_bruto,:estado,:creadoPor)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id_transporte', $losDatos->id_transporte, PDO::PARAM_INT);
            $stmt->bindParam(':peso_bruto', $losDatos->peso_bruto, PDO::PARAM_STR);
            $stmt->bindParam(':estado', $estado, PDO::PARAM_INT);
            $stmt->bindParam(':creadoPor', $id_usuario, PDO::PARAM_INT);

            return $stmt->execute();
        } catch (PDOException $e) {
            die("Error al guardar el peso bruto: " . $e->getMessage());
        }
    }
    // Método para guardar pesaje y pago
    public function guardarPesajeYPago($losDatos)
    {
        $estado = 1;
        $creadoPor = 1;
        $estado_pago = 1;
        $fechaActual = date('Y-m-d H:i:s'); // Formato DATETIME (YYYY-MM-DD HH:MM:SS)
        // Formato ISO 8601 compatible con DATE
        try {
            // Definir la consulta para llamar al procedimiento almacenado
            $sql = "CALL compras_sp_guardar_pesaje_y_pago(
            :id_pesaje, 
            :peso_tara, 
            :monto, 
            :estado, 
            :creadoPor)";

            // Preparar la consulta
            $stmt = $this->pdo->prepare($sql);

            // Asignar valores a los parámetros

            $stmt->bindParam(':id_pesaje', $losDatos->id_pesaje, PDO::PARAM_INT);
            $stmt->bindParam(':peso_tara', $losDatos->peso_tara, PDO::PARAM_STR);
            $stmt->bindParam(':monto', $losDatos->monto, PDO::PARAM_STR);
            $stmt->bindParam(':estado', $estado, PDO::PARAM_INT);
            $stmt->bindParam(':creadoPor', $creadoPor, PDO::PARAM_INT);

            // Ejecutar la consulta
            return $stmt->execute();
        } catch (PDOException $e) {
            die("Error al guardar el pesaje y pago: " . $e->getMessage());
        }
    }

    public function guardarPesajeYPagoYAbono($id_pesaje, $peso_tara, $monto, $estado, $creadoPor, $estado_pago, $montoAbono)
    {
        try {
            // Definir la consulta para llamar al procedimiento almacenado
            $sql = "CALL compras_guardar_pesaje_y_pago_y_abono(:id_pesaje, :peso_tara, :monto, :estado, :creadoPor, :fechaActual, :estado_pago,:montoAbono)";

            // Preparar la consulta
            $stmt = $this->pdo->prepare($sql);

            // Asignar valores a los parámetros
            $fechaActual = date('Ymd'); // Formato YYYYMMDD como INT

            $stmt->bindParam(':id_pesaje', $id_pesaje, PDO::PARAM_INT);
            $stmt->bindParam(':peso_tara', $peso_tara, PDO::PARAM_STR);
            $stmt->bindParam(':monto', $monto, PDO::PARAM_STR);
            $stmt->bindParam(':estado', $estado, PDO::PARAM_INT);
            $stmt->bindParam(':creadoPor', $creadoPor, PDO::PARAM_INT);
            $stmt->bindParam(':fechaActual', $fechaActual, PDO::PARAM_INT);
            $stmt->bindParam(':estado_pago', $estado_pago, PDO::PARAM_INT);
            $stmt->bindParam(':montoAbono', $montoAbono, PDO::PARAM_STR);

            // Ejecutar la consulta
            return $stmt->execute();
        } catch (PDOException $e) {
            die("Error al guardar el pesaje y pago: " . $e->getMessage());
        }
    }


    // Método para eliminar una cuenta contable por ID
    public function eliminarPesaje($id)
    {
        try {
            $sql = "UPDATE compras_pesajes SET estado = 3 WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            return ["success" => false, "error" => "Error al actualizar el estado del pesaje: " . $e->getMessage()];
        }
    }
    // Método para eliminar una cuenta contable por ID
    public function eliminarProductor($id)
    {
        try {
            $sql = "UPDATE compras_productores SET estado = 2 WHERE idProductor = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            return ["success" => false, "error" => "Error al actualizar el estado del productor: " . $e->getMessage()];
        }
    }


    // Método para obtener todas los productores
    public function obtenerProductor()
    {
        try {
            $sql = "SELECT * FROM compras_productores AS pr
                    INNER JOIN compras_estado AS es ON pr.estado = es.id
                    ORDER BY estado ASC";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener los productores:" . $e->getMessage());
        }
    }
    // Método para obtener todos los transportes de un productor
    public function obtenerTiposTransportes($id)
    {
        try {
            $sql = "SELECT t.id, tt.descripcion, t.identificador 
                FROM compras_transporte AS t
                INNER JOIN compras_tipos_transportes AS tt 
                ON t.id_tipo_transporte = tt.id
                WHERE id_Productor = :id";

            // Usamos prepare() para consultas con parámetros
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            // Ejecutamos la consulta
            $stmt->execute();

            // Retornamos los resultados
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener tipos de transportes: " . $e->getMessage());
        }
    }


    public function listarDescargas($id = null)
    {
        try {
            $sql = "SELECT 
                        cp.idProductor,
                        p.id,
                        cp.nombre,
                        t.identificador,
                        p.fecha_pesaje,
                        p.peso_bruto,
                        IFNULL(p.peso_tara, '0.00') AS peso_tara,
                        IFNULL(p.peso_neto, '0.00') AS peso_neto,
                        IFNULL(p.estado, '') AS estado,
                        e.descripcion 
                    FROM compras_pesajes AS p
                    INNER JOIN compras_transporte AS t ON p.id_transporte = t.id
                    INNER JOIN compras_productores AS cp ON t.id_productor = cp.idProductor
                    INNER JOIN compras_estado AS e ON p.estado = e.id";

            // Si se proporciona un ID, agregar la condición WHERE
            if ($id !== null) {
                $sql .= " WHERE p.id = :id";
            }
            $sql .= " ORDER BY p.id DESC"; // Ordenar siemprE
            $stmt = $this->pdo->prepare($sql);

            // Si hay un ID, vincularlo a la consulta
            if ($id !== null) {
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            }

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener las descargas: " . $e->getMessage());
        }
    }
    public function edicionProductor($id)
    {
        try {
            $sql = "SELECT * FROM compras_productores AS pr
                    INNER JOIN compras_estado AS es ON pr.estado = es.id
                    WHERE idProductor = :id;";
            $stmt = $this->pdo->prepare($sql); // Cambiado a prepare()
            $stmt->bindParam(':id', $id, PDO::PARAM_INT); // Enlazar el parámetro
            $stmt->execute(); // Ejecutar la consulta preparada
            return $stmt->fetchAll(PDO::FETCH_ASSOC); // Obtener los resultados
        } catch (PDOException $e) {
            die("Error al obtener los productores: " . $e->getMessage());
        }
    }
    public function actualizarProductor($losDatos)
    {
        $idUsuario = 1;

        try {
            // Construir la consulta SQL
            $sql = "UPDATE compras_productores
                    SET nombre = :nombre, 
                        identificacion = :identificacion, 
                        direccion = :direccion, 
                        fechaModificado = NOW(), 
                        modificadoPor = :modificadoPor
                    WHERE idProductor = :id;";

            // Preparar la consulta
            $stmt = $this->pdo->prepare($sql);

            // Enlazar los parámetros
            $stmt->bindParam(':nombre', $losDatos->nombre, PDO::PARAM_STR);
            $stmt->bindParam(':identificacion', $losDatos->identidad, PDO::PARAM_STR);
            $stmt->bindParam(':direccion', $losDatos->direccion, PDO::PARAM_STR);
            $stmt->bindParam(':modificadoPor', $idUsuario, PDO::PARAM_INT);
            $stmt->bindParam(':id', $losDatos->idProductor, PDO::PARAM_INT);

            // Ejecutar la consulta
            $stmt->execute();

            // Verificar si se actualizó alguna fila
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            die("Error al actualizar el productor: " . $e->getMessage());
        }
    }
}
