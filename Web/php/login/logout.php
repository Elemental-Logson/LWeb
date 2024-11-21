<?php
echo("test");
    session_start(); // Iniciar la sesión
    // Eliminar todos los datos de la sesión
    session_unset();
    // Destruir la sesión
    session_destroy();
    // Redirigir al login
    header('Location: login.php');
    exit;
?>
