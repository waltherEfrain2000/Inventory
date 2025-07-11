<?php
class Product
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }


    public function getProducts()
    {
        try {
            $sql = "SELECT * FROM inventario_Articulos ORDER BY Id DESC";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener las Articulos: " . $e->getMessage());
        }
    }

    public function getUnitMeasures()
    {
        try {
            $sql = "SELECT * FROM inventario_Unidad_de_medida ";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener las Articulos: " . $e->getMessage());
        }
    }

    public function getProductById($id)
    {
        try {
            $sql = "SELECT 
                    a.*, 
                    s.idCategoria, 
                    s.nombreSubCategoria,
                    c.nombreCategoria
                FROM inventario_Articulos a
                INNER JOIN inventario_SubCategoria s ON a.idSubCategoria = s.id
                INNER JOIN inventario_Categorias c ON s.idCategoria = c.id WHERE a.id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener la categoría: " . $e->getMessage());
        }
    }


    public function getProviders()
    {
        try {
            $sql = "SELECT * FROM inventario_Proveedores";
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error al obtener las Articulos: " . $e->getMessage());
        }
    }

    public function deleteProduct($id)
    {
        try {
            $sql = "UPDATE inventario_Articulos SET Estado = 0 WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            return ["success" => false, "error" => "Error al actualizar el estado de la categoría: " . $e->getMessage()];
        }
    }


    public function saveArticle($idSubCategoria, $UsuarioCreador,
     $NombreArticulo, $DescripcionArticulo, $Estado, 
     $CantidadInicial, $PrecioCompra, $PrecioVenta, $idUnidadMedida)
{
    try {
        // Consulta SQL para insertar el artículo
        $sql = "INSERT INTO inventario_Articulos (
                    idSubCategoria,
                    UsuarioCreador,
                    NombreArticulo,
                    DescripcionArticulo,
                    Estado,
                    CantidadInicial,
                    PrecioCompra,
                    PrecioVenta,
                    FechaCreacion,
                    idUnidadMedida
                ) VALUES (
                    :idSubCategoria,
                    :UsuarioCreador,
                    :NombreArticulo,
                    :DescripcionArticulo,
                    :Estado,
                    :CantidadInicial,
                    :PrecioCompra,
                    :PrecioVenta,
                    NOW(),
                    :idUnidadMedida
                )";

        // Preparar la sentencia SQL
        $stmt = $this->pdo->prepare($sql);

        // Vincular los parámetros a la consulta
        $stmt->bindParam(':idSubCategoria', $idSubCategoria, PDO::PARAM_INT);
        $stmt->bindParam(':UsuarioCreador', $UsuarioCreador, PDO::PARAM_INT); // Asumiendo que el usuario es un número (ID de usuario)
        $stmt->bindParam(':NombreArticulo', $NombreArticulo, PDO::PARAM_STR);
        $stmt->bindParam(':DescripcionArticulo', $DescripcionArticulo, PDO::PARAM_STR);
        $stmt->bindParam(':Estado', $Estado, PDO::PARAM_INT); // 1 para activo, 0 para inactivo
        $stmt->bindParam(':CantidadInicial', $CantidadInicial, PDO::PARAM_INT);
        $stmt->bindParam(':PrecioCompra', $PrecioCompra, PDO::PARAM_STR);
        $stmt->bindParam(':PrecioVenta', $PrecioVenta, PDO::PARAM_STR);
        $stmt->bindParam(':idUnidadMedida', $idUnidadMedida, PDO::PARAM_STR);

        // Ejecutar la consulta
        return $stmt->execute();

    } catch (PDOException $e) {
        // En caso de error, se lanza una excepción con el mensaje
        die("Error al guardar el artículo: " . $e->getMessage());
    }
}


public function updateArticle( $idSubCategoria, $NombreArticulo, 
    $DescripcionArticulo, $Estado, 
    $CantidadInicial, $PrecioCompra, $PrecioVenta, $id,$idUnidadMedida, $usuario_id,)
{
    try {
        // Consulta SQL para actualizar el artículo
        $sql = "UPDATE inventario_Articulos SET 
                    idSubCategoria = :idSubCategoria,
                    NombreArticulo = :NombreArticulo,
                    DescripcionArticulo = :DescripcionArticulo,
                    Estado = :Estado,
                    CantidadInicial = :CantidadInicial,
                    PrecioCompra = :PrecioCompra,
                    PrecioVenta = :PrecioVenta,
                    FechaModificacion = NOW(),
                    idUnidadMedida = :idUnidadMedida,
                    UsuarioModificador = :UsuarioModificador
                WHERE id = :id";

        // Preparar la sentencia SQL
        $stmt = $this->pdo->prepare($sql);

        // Vincular los parámetros a la consulta
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':idSubCategoria', $idSubCategoria, PDO::PARAM_INT);
        $stmt->bindParam(':NombreArticulo', $NombreArticulo, PDO::PARAM_STR);
        $stmt->bindParam(':DescripcionArticulo', $DescripcionArticulo, PDO::PARAM_STR);
        $stmt->bindParam(':Estado', $Estado, PDO::PARAM_INT);
        $stmt->bindParam(':CantidadInicial', $CantidadInicial, PDO::PARAM_INT);
        $stmt->bindParam(':PrecioCompra', $PrecioCompra, PDO::PARAM_STR);
        $stmt->bindParam(':PrecioVenta', $PrecioVenta, PDO::PARAM_STR);
        $stmt->bindParam(':idUnidadMedida', $idUnidadMedida, PDO::PARAM_STR);
        $stmt->bindParam(':UsuarioModificador', $usuario_id, PDO::PARAM_INT); // Asumiendo que el usuario es un número (ID de usuario)

        // Ejecutar la consulta
        return $stmt->execute();

    } catch (PDOException $e) {
        die("Error al actualizar el artículo: " . $e->getMessage());
    }
}



}