<?php
// login.php

// Incluir el archivo de configuración
require_once($_SERVER['DOCUMENT_ROOT'] . '/LWeb/Web/www/config.php');
// Generar una URL de autenticación
$auth_url = $keycloak_url . '?response_type=code&client_id=' . $client_id . '&redirect_uri=' . urlencode($redirect_uri);

// Verificar si no hay un token de acceso en la sesión
if (!isset($_SESSION['access_token'])) {
    header('Location: ' . $auth_url);
    exit;
}

// Si ya existe el token, puedes redirigir al dashboard u otra página
header('Location: ../dashboard.php');
exit;
