<?php
require_once 'config/config.php';

try {
    $pdo->query('SELECT 1');
    echo 'Conexión exitosa';
} catch (PDOException $e) {
    echo 'Error de conexión: ' . $e->getMessage();
}
?>
