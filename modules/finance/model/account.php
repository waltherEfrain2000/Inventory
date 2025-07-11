<?php
class CuentaContable {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Método para guardar una nueva cuenta contable
    public function guardarCuenta($codigo, $nombre, $tipo_id, $nivel, $padre_id = null) {
        try {
            $sql = "INSERT INTO contabilidad_cuentas_contables (codigo, nombre, tipo_id, nivel, padre_id) 
                    VALUES (:codigo, :nombre, :tipo_id, :nivel, :padre_id)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':codigo', $codigo, PDO::PARAM_STR);
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':tipo_id', $tipo_id, PDO::PARAM_INT);
            $stmt->bindParam(':nivel', $nivel, PDO::PARAM_INT);
            $stmt->bindParam(':padre_id', $padre_id, PDO::PARAM_INT | PDO::PARAM_NULL);
            return $stmt->execute();
        } catch (PDOException $e) {
            die("Error al guardar la cuenta contable: " . $e->getMessage());
        }
    }

    // Método para eliminar una cuenta contable por ID
    public function eliminarCuenta($id) {
        try {
            $sql = "UPDATE contabilidad_cuentas_contables SET estado = 0 WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            return ["success" => false, "error" => "Error al actualizar el estado de la cuenta: " . $e->getMessage()];
        }
    }
    

    // Método para obtener todas las cuentas contables
    public function obtenerCuentas() {
        try {
            $sql = "SELECT * FROM contabilidad_cuentas_contables ORDER BY codigo";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener las cuentas contables: " . $e->getMessage());
        }
    }

    // Método para obtener una cuenta contable por ID
    public function obtenerCuentaPorId($id) {
        try {
            $sql = "SELECT * FROM contabilidad_cuentas_contables WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener la cuenta contable: " . $e->getMessage());
        }
    }

    // Método para actualizar una cuenta contable
    public function actualizarCuenta($id, $codigo, $nombre, $tipo_id, $nivel, $padre_id = null) {
        try {
            // Verifica si el código ya existe en otra cuenta
            $sqlCheck = "SELECT id FROM contabilidad_cuentas_contables WHERE codigo = :codigo AND id != :id";
            $stmtCheck = $this->pdo->prepare($sqlCheck);
            $stmtCheck->bindParam(':codigo', $codigo, PDO::PARAM_STR);
            $stmtCheck->bindParam(':id', $id, PDO::PARAM_INT);
            $stmtCheck->execute();
    
            if ($stmtCheck->rowCount() > 0) {
                return ["success" => false, "error" => "El código ya existe en otra cuenta."];
            }
    
            // Si no hay duplicados, procede con la actualización
            $sql = "UPDATE contabilidad_cuentas_contables 
                    SET codigo = :codigo, nombre = :nombre, tipo_id = :tipo_id, nivel = :nivel, padre_id = :padre_id
                    WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':codigo', $codigo, PDO::PARAM_STR);
            $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt->bindParam(':tipo_id', $tipo_id, PDO::PARAM_INT);
            $stmt->bindParam(':nivel', $nivel, PDO::PARAM_INT);
            $stmt->bindParam(':padre_id', $padre_id, PDO::PARAM_INT | PDO::PARAM_NULL);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    
            if ($stmt->execute()) {
                return ["success" => true];
            } else {
                return ["success" => false, "error" => "Error al actualizar la cuenta."];
            }
        } catch (PDOException $e) {
            return ["success" => false, "error" => "Error en la base de datos: " . $e->getMessage()];
        }
    }
    

    // Método para obtener cuentas que pueden ser "padre" (No deben ser de nivel más alto que el actual)
    public function obtenerCuentasPadre($nivel) {
        try {
            $sql = "SELECT id, codigo, nombre FROM contabilidad_cuentas_contables WHERE nivel < :nivel ORDER BY codigo";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':nivel', $nivel, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener cuentas padre: " . $e->getMessage());
        }
    }
    
}
?>
