<?php
// Configuración de la base de datos
$host = "localhost";  // Dirección del servidor (puede ser una IP si no es local)
$usuario = "root";    // Nombre de usuario de MySQL
$contrasena = "";     // Contraseña del usuario de MySQL
$base_datos = "db_logson";  // Nombre de la base de datos

try {
    // Crear conexión usando PDO
    $conn = new PDO("mysql:host=$host;dbname=$base_datos", $usuario, $contrasena);
    
    // Establecer el modo de error de PDO
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Conexión exitosa";
} catch(PDOException $e) {
    echo "Conexión fallida: " . $e->getMessage();
}

// Cerrar la conexión
$conn = null;
?>