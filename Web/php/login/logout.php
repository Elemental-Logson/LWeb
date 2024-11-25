<?php
session_start(); // Iniciar la sesión

// Eliminar todos los datos de la sesión
session_unset();
// Destruir la sesión
session_destroy();

// URL de cierre de sesión de Keycloak
$realm = 'SSO';  // Nombre del realm
$redirect_uri = 'http://localhost/lweb/Web/php/login/loginUnificado.php';  // Redirigir al login unificado

// Asegúrate de que $_SESSION['authToken'] contenga el token de acceso
$id_token_hint = $_SESSION['authToken'];  // Usamos el token de acceso como hint para cerrar la sesión
$keycloak_logout_url = 'http://10.11.0.96:8080/realms/' . $realm . '/protocol/openid-connect/logout?post_logout_redirect_uri=' . urlencode($redirect_uri) . '&id_token_hint=' . urlencode($id_token_hint);

// Redirigir al logout de Keycloak
header('Location: ' . $keycloak_logout_url);
exit;
