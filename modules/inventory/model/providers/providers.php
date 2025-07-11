<?php
class Provider  
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }


    public function getProviders()
    {
        try {
            $sql = "SELECT * FROM contabilidad_proveedores where estado = 1";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener las Articulos: " . $e->getMessage());
        }
    }
    public function deleteProvider($id)
    {
        try {
            $sql = "UPDATE inventario_Proveedores SET Estado = 0 WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            return ["success" => false, "error" => "Error al actualizar el estado el proveedor " . $e->getMessage()];
        }
    }
    public function getProviderById($id)
    {
        try {
            $sql = "SELECT * FROM inventario_Proveedores WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener el proveedor: " . $e->getMessage());
        }
    }
    public function saveProvider($id = null, $Nombre, $Descripcion, $Estado, $UsuarioCreador, $NumeroCelular, $Direccion, $NombreContacto)
    {
        try {
            // Si el ID no es nulo, se trata de una actualización
            if ($id !== null) {
                $sql = "UPDATE inventario_Proveedores SET
                            Nombre = :Nombre,
                            Descripcion = :Descripcion,
                            Estado = :Estado,
                            UsuarioCreador = :UsuarioCreador,
                            NumeroCelular = :NumeroCelular,
                            Direccion = :Direccion,
                            NombreContacto = :NombreContacto
                        WHERE id = :id";
    
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT); // Enlazar el ID
            } else {
                // Si no hay ID, es una inserción
                $sql = "INSERT INTO inventario_Proveedores (
                            Nombre,
                            Descripcion,
                            Estado,
                            UsuarioCreador,
                            NumeroCelular,
                            Direccion,
                            NombreContacto,
                            FechaCreacion
                        ) VALUES (
                            :Nombre,
                            :Descripcion,
                            :Estado,
                            :UsuarioCreador,
                            :NumeroCelular,
                            :Direccion,
                            :NombreContacto,
                            NOW()
                        )";
    
                $stmt = $this->pdo->prepare($sql);
            }
    
            // Enlazar los parámetros comunes para ambos casos (INSERT y UPDATE)
            $stmt->bindParam(':Nombre', $Nombre, PDO::PARAM_STR);
            $stmt->bindParam(':Descripcion', $Descripcion, PDO::PARAM_STR);
            $stmt->bindParam(':Estado', $Estado, PDO::PARAM_INT); // Usar el valor de $Estado
            $stmt->bindParam(':UsuarioCreador', $UsuarioCreador, PDO::PARAM_STR);
            $stmt->bindParam(':NumeroCelular', $NumeroCelular, PDO::PARAM_STR);
            $stmt->bindParam(':Direccion', $Direccion, PDO::PARAM_STR);
            $stmt->bindParam(':NombreContacto', $NombreContacto, PDO::PARAM_STR);
    
            // Ejecutar la consulta
            return $stmt->execute();
        } catch (PDOException $e) {
            die("Error al guardar o actualizar el proveedor: " . $e->getMessage());
        }
    }
    
}