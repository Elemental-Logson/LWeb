<?php
// Configuración de Keycloak
// $realm = 'SSO';  // Nombre del realm
$realm = 'Master';  // Nombre del realm
$client_id = 'SSO';  // ID del cliente registrado en Keycloak
$client_secret = '0OUwDjXfU7eAdBHF9b89I6rRbxV6xrwv';  // Secreto del cliente
$redirect_uri = 'https://localhost/lweb/Web/php/login/callback.php';  // URL de callback
$redirect_uriLogOut = 'https://localhost/lweb/Web/php/login/loginUnificado.php'; // URL del logout

// Dirección IP de Keycloak
$ip = '10.11.0.18:8443'; // Dirección IP y puerto del servidor Keycloak
// $ip = '192.168.18.32:8080'; //Casa
// URLs de Keycloak
$keycloak_url = 'https://' . $ip . '/realms/' . $realm . '/protocol/openid-connect/auth';
$token_url = 'https://' . $ip . '/realms/' . $realm . '/protocol/openid-connect/token';
$keycloak_logout_url = 'https://' . $ip . '/realms/' . $realm . '/protocol/openid-connect/logout?redirect_uri=' . urlencode($redirect_uriLogOut);
$users_url = 'https://' . $ip . '/admin/realms/' . $realm . '/users';
