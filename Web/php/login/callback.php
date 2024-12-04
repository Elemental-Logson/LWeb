<?php
session_start();

// Incluir el archivo de configuración
require_once($_SERVER['DOCUMENT_ROOT'] . '/LWeb/Web/www/config.php');

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
    if (isset($response_data['access_token']) && isset($response_data['id_token'])) {
        $_SESSION['access_token'] = $response_data['access_token'];
        $_SESSION['refresh_token'] = $response_data['refresh_token'];
        $_SESSION['id_token'] = $response_data['id_token'];  // Guardar el ID Token en la sesión

        // Decodificar el ID Token
        $id_token = $response_data['id_token'];
        list($header, $payload, $signature) = explode('.', $id_token);
        $decoded_payload = json_decode(base64_decode($payload), true);

        // Obtener nombre de usuario y roles desde el payload del ID Token
        $username = isset($decoded_payload['preferred_username']) ? $decoded_payload['preferred_username'] : 'No disponible';
        $roles = isset($decoded_payload['realm_access']['roles']) ? $decoded_payload['realm_access']['roles'] : [];

        // Guardamos los datos en la sesión
        $_SESSION['username'] = $username;  // Guardar el nombre de usuario
        $_SESSION['userRole'] = $roles;     // Guardar los roles del usuario

        // Redirigir a la página de login
        header('Location: ../dashboard.php');
        exit;
    } else {
        echo 'Error al obtener el token.';
    }
} else {
    echo 'No se recibió el código de autenticación.';
}
