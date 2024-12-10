<?php
// Configuración de Keycloak
$realm = 'SSO';  // Nombre del realm
$client_id = 'SSO';  // ID del cliente registrado en Keycloak
$client_secret = 'CtisRoENEcw7MYtIBPl2ahaWGSzz10rI';  // Secreto del cliente
$redirect_uri = 'http://localhost/lweb/Web/php/login/callback.php';  // URL de callback
$redirect_uriLogOut = 'http://localhost/lweb/Web/php/login/loginUnificado.php'; // URL del logout

// Dirección IP de Keycloak
$ip = '10.11.0.172:8080'; // Dirección IP y puerto del servidor Keycloak
// $ip = '192.168.18.32:8080'; //Casa
// URLs de Keycloak
$keycloak_url = 'http://' . $ip . '/realms/' . $realm . '/protocol/openid-connect/auth';
$token_url = 'http://' . $ip . '/realms/' . $realm . '/protocol/openid-connect/token';
$keycloak_logout_url = 'http://' . $ip . '/realms/' . $realm . '/protocol/openid-connect/logout?redirect_uri=' . urlencode($redirect_uriLogOut);
$users_url = 'http://' . $ip . '/admin/realms/' . $realm . '/users';
