<?php
session_start(); // Inicia la sesión

// Verificar si la sesión está activa
if (!isset($_SESSION['username'])) {
    die(json_encode(["error" => "Acceso denegado: No has iniciado sesión."]));
}

include('../../../www/conexion.php'); // Conexión a la base de datos

if (!isset($_GET['scanned_ip_id'])) {
    die(json_encode(["error" => "Falta el parámetro scanned_ip_id."]));
}

$scanned_ip_id = $_GET['scanned_ip_id'];

try {
    // Obtener los detalles de las vulnerabilidades para la IP escaneada especificada
    $sql = "SELECT id, port, protocol, service, version, product, script_output 
            FROM scan_details 
            WHERE scanned_ip_id = :scanned_ip_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':scanned_ip_id', $scanned_ip_id, PDO::PARAM_INT);
    $stmt->execute();

    $vulnerabilities = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Establecer el encabezado para indicar JSON
    header('Content-Type: application/json');

    // Enviar la respuesta como JSON
    echo json_encode($vulnerabilities);
} catch (PDOException $e) {
    // Enviar error como JSON
    header('Content-Type: application/json');
    echo json_encode(["error" => "Error al obtener los datos: " . $e->getMessage()]);
}

$conn = null;