<?php
// conexion.php

// Configuración de la base de datos
// $host = "10.11.0.17";  // Dirección del servidor (puede ser una IP si no es local)
// $usuario = "logsonweb";    // Nombre de usuario de MySQL
// $contrasena = "cibergela2024*";     // Contraseña del usuario de MySQL
// $base_datos = "db_logson";  // Nombre de la base de datos

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

    // Si la conexión es exitosa, no se ejecutará esta línea, pero si hay error se lanza una excepción.
} catch (PDOException $e) {
    echo "Conexión fallida: " . $e->getMessage();
    die();  // Termina la ejecución si no hay conexión
}
