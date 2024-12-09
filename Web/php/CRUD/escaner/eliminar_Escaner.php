<?php
session_start(); // Inicia la sesión

// Verificar si la sesión está activa
include('../../../www/conexion.php'); // Conexión a la base de datos
// Comprobar si hay un token de autenticación en la sesión
if (!isset($_SESSION['access_token']) || (!isset($_SESSION["role"]) != "Admin")) {
    // Si no hay token o el rol no es admin, destruir la sesión y redirigir al login
    session_unset();  // Elimina todas las variables de sesión
    session_destroy(); // Destruye la sesión
    header('Location: /lweb/Web/php/login/loginUnificado.php'); // Redirige al login
    exit;
}
// Verificar si el método es DELETE
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    // Obtener el cuerpo de la solicitud y decodificarlo como JSON
    $input = json_decode(file_get_contents("php://input"), true);
    $scan_id = $input['scan_id'] ?? null;

    if (!$scan_id) {
        // Responder con un error si no se proporciona un scan_id
        header('Content-Type: application/json');
        echo json_encode(["error" => "ID de escaneo no proporcionado."]);
        exit;
    }

    try {
        // Preparar y ejecutar la eliminación del escaneo por ID
        $sql = "DELETE FROM scan WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $scan_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            // Respuesta exitosa si el escaneo fue eliminado correctamente
            header('Content-Type: application/json');
            echo json_encode(["message" => "Escaneo eliminado con éxito."]);
        } else {
            // Responder con un error si la eliminación falla
            header('Content-Type: application/json');
            echo json_encode(["error" => "Error al eliminar el escaneo."]);
        }
    } catch (PDOException $e) {
        // Manejar errores de la base de datos y responder en formato JSON
        header('Content-Type: application/json');
        echo json_encode(["error" => "Error de base de datos: " . $e->getMessage()]);
    }
} else {
    // Responder con un error si el método no es DELETE
    header('Content-Type: application/json');
    echo json_encode(["error" => "Método no permitido."]);
}

$conn = null;
