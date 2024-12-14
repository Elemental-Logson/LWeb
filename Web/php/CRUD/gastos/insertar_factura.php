<?php
session_start();
// Verificar si la sesión está activa
include('../../../www/conexion.php');

$nombre_usuario = $_SESSION['username'];

// Verificar si es una solicitud POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $descripcion = filter_input(INPUT_POST, 'descripcion', FILTER_SANITIZE_STRING);
    $monto = filter_input(INPUT_POST, 'monto', FILTER_VALIDATE_FLOAT);
    $fecha = filter_input(INPUT_POST, 'fecha', FILTER_SANITIZE_STRING);
    $factura = $_FILES['factura'] ?? null;

    $nuevo_nombre = ""; // Inicia la factura como null

    // Validar archivo de factura (si se envía)
    if ($factura && $factura['error'] === UPLOAD_ERR_OK) {
        // Directorio base para las facturas
        $base_directorio = $_SERVER['DOCUMENT_ROOT'] . '/LWeb/img/';
        // Crear un subdirectorio para el usuario
        $directorio_usuario = $base_directorio . $nombre_usuario . '/';

        // Crear el directorio del usuario si no existe
        if (!file_exists($directorio_usuario)) {
            if (!mkdir($directorio_usuario, 0775, true)) {
                echo json_encode(['success' => false, 'error' => 'Error al crear el directorio para el usuario.']);
                exit;
            }
        }

        $extension = strtolower(pathinfo($factura['name'], PATHINFO_EXTENSION));
        $extensiones_permitidas = ['jpg', 'jpeg', 'png', 'pdf'];

        if (in_array($extension, $extensiones_permitidas)) {
            $nuevo_nombre = uniqid('factura_') . '.' . $extension;
            $archivo_destino = $directorio_usuario . $nuevo_nombre;

            // Mover el archivo subido
            if (!move_uploaded_file($factura['tmp_name'], $archivo_destino)) {
                echo json_encode(['success' => false, 'error' => 'Error al mover el archivo de factura.']);
                exit;
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'Solo se permiten archivos de tipo imagen (jpg, jpeg, png, pdf).']);
            exit;
        }
    }

    // Intentar insertar el registro en la base de datos
    try {
        $sql = "INSERT INTO gastos (descripcion, monto, fecha, factura, nombre_usuario) 
                VALUES (:descripcion, :monto, :fecha, :factura, :nombre_usuario)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':monto', $monto);
        $stmt->bindParam(':fecha', $fecha);
        $stmt->bindParam(':factura', $nuevo_nombre); // Si no hay factura, será NULL
        $stmt->bindParam(':nombre_usuario', $nombre_usuario);
        $stmt->execute();

        echo json_encode(['success' => true, 'message' => 'Gasto añadido exitosamente.']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => 'Error al insertar los datos: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Método no permitido.']);
}

$conn = null;
