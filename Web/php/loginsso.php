<?php
session_start();

$realm = 'SSO';  // Nombre del realm
$client_id = 'SSO';  // ID del cliente registrado en Keycloak
$client_secret = 'RtrWyqSSa3lrCopGjjLh5NHWGKrHtMtY';  // Secreto del cliente
$redirect_uri = 'http://localhost/lweb/Web/php/callback.php';  // URL de callback

// URL de Keycloak para la autenticación
$keycloak_url = 'http://10.11.0.96:8080//realms/' . $realm . '/protocol/openid-connect/auth';

// Generar una URL de autenticación
$auth_url = $keycloak_url . '?response_type=code&client_id=' . $client_id . '&redirect_uri=' . urlencode($redirect_uri);

if (!isset($_SESSION['access_token'])) {
    header('Location: ' . $auth_url);
    exit;
}

echo 'Usuario autenticado correctamente.';
?>
