<?php
// Configuración de Keycloak
$realm = 'SSO'; // Nombre del realm
$client_id = 'SSO'; // ID del cliente registrado en Keycloak
$client_secret = 'CtisRoENEcw7MYtIBPl2ahaWGSzz10rI'; // Secreto del cliente
$redirect_uri = 'http://localhost/LWeb/Web/php/login/callback.php'; // URL de callback
$redirect_uriLogOut = 'http://localhost/LWeb/Web/php/login/loginUnificado.php'; // URL del logout

// Dirección IP de Keycloak
$ip = '192.168.18.36:8080'; //Casa
// URLs de Keycloak
$keycloak_url = 'http://' . $ip . '/realms/' . $realm . '/protocol/openid-connect/auth';
$token_url = 'http://' . $ip . '/realms/' . $realm . '/protocol/openid-connect/token';
$keycloak_logout_url = 'http://' . $ip . '/realms/' . $realm . '/protocol/openid-connect/logout?redirect_uri=' . urlencode($redirect_uriLogOut);
$users_url = 'http://' . $ip . '/admin/realms/' . $realm . '/users';



// Server
// Dirección IP de Keycloak
// $ip = '10.11.0.18:8443'; // Dirección IP y puerto del servidor Keycloak
// // Configuración de Keycloak
// $realm = 'Logson';  // Nombre del realm
// $client_id = 'SSO';  // ID del cliente registrado en Keycloak
// $client_secret = '85ABiJWQxptYYg0wJCP7NCL7LrFawGRk';  // Secreto del cliente
// $redirect_uri = 'https://10.11.0.17/LWeb/Web/php/login/callback.php';  // URL de callback
// $redirect_uriLogOut = 'https://10.11.0.17/LWeb/Web/php/login/loginUnificado.php'; // URL del logout

// // URLs de Keycloak
// $keycloak_url = 'https://' . $ip . '/realms/' . $realm . '/protocol/openid-connect/auth';
// $token_url = 'https://' . $ip . '/realms/' . $realm . '/protocol/openid-connect/token';
// $keycloak_logout_url = 'https://' . $ip . '/realms/' . $realm . '/protocol/openid-connect/logout?redirect_uri=' . urlencode($redirect_uriLogOut);
// $users_url = 'https://' . $ip . '/admin/realms/' . $realm . '/users';
