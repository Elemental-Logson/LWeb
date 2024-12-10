<?php
// Verificar si la sesión está activa
include('../../../www/conexion.php'); // Conexión a la base de datos
try {
    // Obtener los escaneos del usuario
    $sql = "SELECT id, name, scan_date FROM scan";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $scan = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Establecer el encabezado para indicar JSON
    header('Content-Type: application/json');

    // Enviar la respuesta como JSON
    echo json_encode($scan);
} catch (PDOException $e) {
    // Enviar error como JSON
    header('Content-Type: application/json');
    echo json_encode(["error" => "Error al obtener los datos: " . $e->getMessage()]);
}

$conn = null;
