<?php
session_start();

$realm = 'SSO';  // Nombre del realm
$client_id = 'SSO';  // ID del cliente registrado en Keycloak
$client_secret = 'RtrWyqSSa3lrCopGjjLh5NHWGKrHtMtY';  // Secreto del cliente
$redirect_uri = 'http://localhost/lweb/Web/php/callback.php';  // URL de callback
$token_url = 'http://10.11.0.96:8080//realms/' . $realm . '/protocol/openid-connect/token';  // URL para obtener el token

// Verificar si el código de autenticación está presente en la URL
if (isset($_GET['code'])) {
    $code = $_GET['code'];

    // Preparar los datos para el POST
    $data = [
        'grant_type' => 'authorization_code',
        'code' => $code,
        'redirect_uri' => $redirect_uri,
        'client_id' => $client_id,
        'client_secret' => $client_secret
    ];

    // Iniciar la solicitud cURL para obtener el token
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $token_url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    // Convertir la respuesta a JSON
    $response_data = json_decode($response, true);

    // Verificar si se obtuvo un token
    if (isset($response_data['access_token'])) {
        $_SESSION['access_token'] = $response_data['access_token'];
        $_SESSION['refresh_token'] = $response_data['refresh_token'];
        header('Location: loginsso.php');
        exit;
    } else {
        echo 'Error al obtener el token.';
    }
} else {
    echo 'No se recibió el código de autenticación.';
}
?>
