<?php

// Verificar si la sesión está activa
include('../../../www/conexion.php'); // Conexión a la base de datos
// Comprobar si hay un token de autenticación en la sesión
try {
    // Obtener las transacciones del usuario
    $sql = "SELECT descripcion, monto, fecha, factura FROM gastos";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $transacciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Establecer el encabezado para indicar JSON
    header('Content-Type: application/json');

    // Enviar la respuesta como JSON
    echo json_encode($transacciones);
} catch (PDOException $e) {
    // Enviar error como JSON
    header('Content-Type: application/json');
    echo json_encode(["error" => "Error al obtener los datos: " . $e->getMessage()]);
}

$conn = null;
