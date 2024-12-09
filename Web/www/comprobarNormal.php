<?php
session_start(); // Iniciar la sesión

// Verificar si existe un token de acceso
if (!isset($_SESSION['access_token']) || empty($_SESSION['access_token'])) {
    // Destruir la sesión si no hay token
    session_unset();  // Elimina todas las variables de sesión
    session_destroy(); // Destruye la sesión
    header('Location: /lweb/Web/php/login/loginUnificado.php'); // Redirigir al login
    exit;
}
