<?php
session_start(); // Iniciar la sesión

// Verificar si existe un token de acceso y si el rol es admin
if (!isset($_SESSION['access_token']) || empty($_SESSION['access_token']) || ($_SESSION['role'] ?? '') !== 'Admin') {
    // Destruir la sesión y redirigir al login
    session_unset();  // Elimina todas las variables de sesión
    session_destroy(); // Destruye la sesión
    header('Location: /lweb/Web/php/login/loginUnificado.php'); // Redirigir al login
    exit;
}

// Si pasa las validaciones, continuar con la ejecución del script
