<?php
session_start();
if (!isset($_SESSION['access_token'])) {
    header("Location: /LWeb/Web/html/forbidden.html");
    exit();
}
// Verificar si la sesión está activa
include('../../../www/conexion.php'); // Conexión a la base de datos

$nombre_usuario = $_SESSION['username'];

try {
    // Obtener las transacciones del usuario
    if ($_SESSION["role"] === "Admin") {
        $sql = "SELECT id, descripcion, monto, fecha, factura, nombre_usuario FROM gastos";
    } else {
        $sql = "SELECT id, descripcion, monto, fecha, factura, nombre_usuario FROM gastos WHERE nombre_usuario = :nombre_usuario";
    }
    $stmt = $conn->prepare($sql);
    if ($_SESSION["role"] != "Admin") {
        $stmt->bindParam(':nombre_usuario', $nombre_usuario);
    }
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
