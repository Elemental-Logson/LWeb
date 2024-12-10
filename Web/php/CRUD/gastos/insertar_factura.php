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

    // Validar archivo de factura
    if ($factura && $factura['error'] === UPLOAD_ERR_OK) {
        $directorio_destino = $_SERVER['DOCUMENT_ROOT'] . '/LWeb/img/';
        $extension = strtolower(pathinfo($factura['name'], PATHINFO_EXTENSION));
        $extensiones_permitidas = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($extension, $extensiones_permitidas)) {
            $nuevo_nombre = uniqid('factura_') . '.' . $extension;
            $archivo_destino = $directorio_destino . $nuevo_nombre;

            // Crear el directorio si no existe
            if (!file_exists($directorio_destino)) {
                mkdir($directorio_destino, 0775, true);
            }

            // Mover el archivo subido
            if (move_uploaded_file($factura['tmp_name'], $archivo_destino)) {
                try {
                    $sql = "INSERT INTO gastos (descripcion, monto, fecha, factura, nombre_usuario) 
                            VALUES (:descripcion, :monto, :fecha, :factura, :nombre_usuario)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':descripcion', $descripcion);
                    $stmt->bindParam(':monto', $monto);
                    $stmt->bindParam(':fecha', $fecha);
                    $stmt->bindParam(':factura', $nuevo_nombre); // Guardar solo el nombre del archivo
                    $stmt->bindParam(':nombre_usuario', $nombre_usuario);
                    $stmt->execute();

                    echo "Gasto añadido exitosamente.";
                } catch (PDOException $e) {
                    echo "Error al insertar los datos: " . $e->getMessage();
                }
            } else {
                echo "Error al mover el archivo de factura.";
            }
        } else {
            echo "Solo se permiten archivos de tipo imagen (jpg, jpeg, png, gif).";
        }
    } else {
        echo "Error al subir la factura.";
    }
} else {
    echo "Método no permitido.";
}

$conn = null;
