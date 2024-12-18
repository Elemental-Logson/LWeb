<?php
session_start();
if (!isset($_SESSION['access_token'])) {
    // Redirigir si no hay sesión activa
    header("Location: /LWeb/Web/html/forbidden.html");
    exit();
}

include('../../../www/conexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    $id = filter_var($input['id'] ?? 0, FILTER_VALIDATE_INT);
    $facturaRuta = filter_var($input['facturaRuta'] ?? '', FILTER_SANITIZE_STRING);

    if (!$id || (!$facturaRuta && $facturaRuta !== '/lweb/null')) {
        echo json_encode(['success' => false, 'error' => 'ID o ruta de la factura no válida.']);
        exit();
    }

    try {
        $conn->beginTransaction();

        // Si la ruta no es '/lweb/null', proceder con la eliminación del archivo
        if ($facturaRuta !== '/lweb/null') {
            // Convertir la ruta relativa en absoluta
            $ruta_completa = realpath($_SERVER['DOCUMENT_ROOT'] . '/' . ltrim(str_replace('../', '', $facturaRuta), '/'));

            if (!$ruta_completa || !file_exists($ruta_completa)) {
                error_log("El archivo no existe o la ruta es inválida: " . $facturaRuta);
                echo json_encode(['success' => false, 'error' => 'Archivo no encontrado o ruta inválida.']);
                $conn->rollBack();
                exit();
            }

            // Intentar eliminar el archivo
            if (!unlink($ruta_completa)) {
                error_log("Error al eliminar archivo: " . $ruta_completa);
                echo json_encode(['success' => false, 'error' => 'No se pudo eliminar el archivo asociado.']);
                $conn->rollBack();
                exit();
            }
        }

        // Eliminar el registro de la base de datos
        $sql = "DELETE FROM gastos WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $conn->commit();

        echo json_encode(['success' => true, 'message' => 'Factura y archivo eliminados correctamente.']);
    } catch (PDOException $e) {
        $conn->rollBack();
        echo json_encode(['success' => false, 'error' => 'Error al eliminar el gasto: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Método no permitido.']);
}

$conn = null;
