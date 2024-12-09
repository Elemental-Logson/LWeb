<?php
session_start(); // Inicia la sesión

// Verificar si la sesión está activa
include('../../../www/conexion.php'); // Conexión a la base de datos
if (!isset($_SESSION['access_token']) || (!isset($_SESSION["role"]) != "Admin")) {
    // Si no hay token o el rol no es admin, destruir la sesión y redirigir al login
    session_unset();  // Elimina todas las variables de sesión
    session_destroy(); // Destruye la sesión
    header('Location: /lweb/Web/php/login/loginUnificado.php'); // Redirige al login
    exit;
}
$nombre_usuario = $_SESSION['username'];

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
