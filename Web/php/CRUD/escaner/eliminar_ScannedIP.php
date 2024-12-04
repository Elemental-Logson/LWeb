<?php
session_start(); // Inicia la sesión

// Verificar si la sesión está activa
if (!isset($_SESSION['username'])) {
    die(json_encode(["error" => "Acceso denegado: No has iniciado sesión."]));
}

include('../../../www/conexion.php'); // Conexión a la base de datos

// Verificar si el método es DELETE
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    // Obtener el cuerpo de la solicitud y decodificarlo como JSON
    $input = json_decode(file_get_contents("php://input"), true);
    $ip_id = $input['ip_id'] ?? null;

    if (!$ip_id) {
        // Responder con un error si no se proporciona un ip_id
        header('Content-Type: application/json');
        echo json_encode(["error" => "ID de IP escaneada no proporcionado."]);
        exit;
    }

    try {
        // Preparar y ejecutar la eliminación del registro por ID
        $sql = "DELETE FROM scanned_ips WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $ip_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            // Respuesta exitosa si el registro fue eliminado correctamente
            header('Content-Type: application/json');
            echo json_encode(["message" => "Registro de IP escaneada eliminado con éxito."]);
        } else {
            // Responder con un error si la eliminación falla
            header('Content-Type: application/json');
            echo json_encode(["error" => "Error al eliminar el registro de IP escaneada."]);
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
