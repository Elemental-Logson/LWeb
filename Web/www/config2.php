<?php
// Server
// Dirección IP de Keycloak
$ip = '10.11.0.18:8443'; // Dirección IP y puerto del servidor Keycloak
// Configuración de Keycloak
$realm = 'Logson';  // Nombre del realm
$client_id = 'SSO2';  // ID del cliente registrado en Keycloak
$client_secret = '9XULsZpXOmwwqF4me6ctaciaDojg2TZW';  // Secreto del cliente
$redirect_uri = 'https://10.11.0.17/LWeb/Web/php/login/callback2.php';  // URL de callback
$redirect_uriLogOut = 'https://10.11.0.17/LWeb/Web/php/login/loginUnificado.php'; // URL del logout

// URLs de Keycloak
$keycloak_url = 'https://' . $ip . '/realms/' . $realm . '/protocol/openid-connect/auth';
$token_url = 'https://' . $ip . '/realms/' . $realm . '/protocol/openid-connect/token';
$keycloak_logout_url = 'https://' . $ip . '/realms/' . $realm . '/protocol/openid-connect/logout?redirect_uri=' . urlencode($redirect_uriLogOut);
$users_url = 'https://' . $ip . '/admin/realms/' . $realm . '/users';
