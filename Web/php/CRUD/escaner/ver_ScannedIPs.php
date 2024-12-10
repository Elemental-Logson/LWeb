<?php

// Verificar si la sesión está activa
include('../../../www/conexion.php'); // Conexión a la base de datos
if (!isset($_GET['scan_id'])) {
    die(json_encode(["error" => "Falta el parámetro scan_id."]));
}

$scan_id = $_GET['scan_id'];

try {
    // Obtener las IPs escaneadas que coincidan con el scan_id proporcionado
    $sql = "SELECT id, host, hostname, state FROM scanned_ips WHERE scan_id = :scan_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':scan_id', $scan_id, PDO::PARAM_INT);
    $stmt->execute();

    $scannedIps = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Establecer el encabezado para indicar JSON
    header('Content-Type: application/json');

    // Enviar la respuesta como JSON
    echo json_encode($scannedIps);
} catch (PDOException $e) {
    // Enviar error como JSON
    header('Content-Type: application/json');
    echo json_encode(["error" => "Error al obtener los datos: " . $e->getMessage()]);
}

$conn = null;
