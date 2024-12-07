<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/LWeb/Web/www/config.php');
session_start(); // Iniciar la sesión

// Eliminar todos los datos de la sesión
session_unset();
// Destruir la sesión
session_destroy();

// URL de cierre de sesión de Keycloak
// $realm = 'SSO';  // Nombre del realm
// $redirect_uri = 'http://localhost/lweb/Web/php/login/loginUnificado.php';  // Redirigir al login unificado
// $keycloak_logout_url = 'http://192.168.18.31:8080/realms/' . $realm . '/protocol/openid-connect/logout?redirect_uri=' . urlencode($redirect_uri);

// Redirigir al logout de Keycloak
header('Location: ' . $keycloak_logout_url);
exit;
