<?php
// Configuración de Keycloak
$realm = 'SSO';  // Nombre del realm
$client_id = 'SSO';  // ID del cliente registrado en Keycloak
$client_secret = 'nEwFia9TvqTH0fgxEgeIJwEHy2MyJVm2';  // Secreto del cliente
$redirect_uri = 'http://localhost/lweb/Web/php/login/callback.php';  // URL de callback
$redirect_uriLogOut = 'http://localhost/lweb/Web/php/login/loginUnificado.php'; //URL del logout
// URLs de Keycloak
$keycloak_url = 'http://192.168.18.31:8080//realms/' . $realm . '/protocol/openid-connect/auth';
$token_url = 'http://192.168.18.31:8080//realms/' . $realm . '/protocol/openid-connect/token';
$keycloak_logout_url = 'http://192.168.18.31:8080/realms/' . $realm . '/protocol/openid-connect/logout?redirect_uri=' . urlencode($redirect_uriLogOut);
