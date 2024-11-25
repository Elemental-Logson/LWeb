<?php
session_start(); // Inicia la sesión

// Verificar si la sesión está activa
if (!isset($_SESSION['username'])) {
    die(json_encode(["error" => "Acceso denegado: No has iniciado sesión."]));
}

include('../../../www/conexion.php'); // Conexión a la base de datos

$nombre_usuario = $_SESSION['username'];

try {
    // Obtener las transacciones del usuario
    $sql = "SELECT descripcion, monto, fecha, factura FROM gastos WHERE nombre_usuario = :nombre_usuario";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':nombre_usuario', $nombre_usuario);
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
