<?php
include('conexion.php');

try {
    $stmt = $conn->query("SELECT 'Conexión exitosa' AS mensaje");
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    echo $resultado['mensaje'];
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
