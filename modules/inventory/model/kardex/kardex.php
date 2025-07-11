<?php
class Kardex
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }



public function getKardex($idProducto, $idBodega = null)
{
    try {
        $stmt = $this->pdo->prepare("CALL sp_Kardex(:idProducto, :idBodega)");
        $stmt->bindParam(':idProducto', $idProducto, PDO::PARAM_INT);
        $stmt->bindParam(':idBodega', $idBodega, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    } catch (PDOException $e) {
        return ["success" => false, "error" => "Error al obtener el Kardex: " . $e->getMessage()];
    }
}


public function getHistoryKardex()
{
    try {
        $stmt = $this->pdo->prepare("Select * from inventario_vw_transacciones");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    } catch (PDOException $e) {
        return ["success" => false, "error" => "Error al obtener el historial de transacciones: " . $e->getMessage()];
    }
}


public function getGeneralInventory()
{
    try {
        $stmt = $this->pdo->prepare("Select * from inventario_vw_InventarioActual");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    } catch (PDOException $e) {
        return ["success" => false, "error" => "Error al obtener el inventario general: " . $e->getMessage()];
    }
}
}