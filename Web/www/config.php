<?php
session_start();

// Configuración de Keycloak
$realm = 'SSO';  // Nombre del realm
$client_id = 'SSO';  // ID del cliente registrado en Keycloak
$client_secret = 'xoeBGlQNYuvcHJA0awfLfOf5iKkZuMJi';  // Secreto del cliente
$redirect_uri = 'http://localhost/lweb/Web/php/login/callback.php';  // URL de callback

// URLs de Keycloak
$keycloak_url = 'http://10.11.0.119:8080/realms/' . $realm . '/protocol/openid-connect/auth';
$token_url = 'http://10.11.0.119:8080/realms/' . $realm . '/protocol/openid-connect/token';
