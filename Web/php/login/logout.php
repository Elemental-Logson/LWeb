<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/LWeb/Web/www/config.php');
session_start(); // Iniciar la sesión
// Eliminar todos los datos de la sesión
session_unset();
// Destruir la sesión
session_destroy();
// Redirigir al logout de Keycloak
header('Location: ' . $keycloak_logout_url);
exit;
