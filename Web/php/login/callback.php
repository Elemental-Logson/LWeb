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
    if (isset($response_data['access_token'])) {
        $_SESSION['access_token'] = $response_data['access_token'];
        $_SESSION['refresh_token'] = $response_data['refresh_token'];

        // Obtener el token de acceso
        $access_token = $_SESSION['access_token'];

        // Dividir el JWT en sus partes
        list($header, $payload, $signature) = explode('.', $access_token);

        // Decodificar las partes Base64
        $decoded_header = json_decode(base64_decode($header), true);
        $decoded_payload = json_decode(base64_decode($payload), true);

        // Obtener nombre de usuario y grupos
        $username = isset($decoded_payload['preferred_username']) ? $decoded_payload['preferred_username'] : 'No disponible';
        $roles = isset($decoded_payload['realm_access']['roles']) ? $decoded_payload['realm_access']['roles'] : [];

        // Determinar el rol
        $role = 'Empresa'; // Rol por defecto
        if (in_array('Admin', $roles)) {
            $role = 'Admin';
        }

        // Guardamos el nombre de usuario y su rol en la sesión
        $_SESSION["username"] = $username;
        $_SESSION["role"] = $role;

        // Mostrar la información
        // echo "Nombre de usuario: " . $username . "<br>";
        // echo "Roles: " . implode(", ", $roles) . "<br>";
        // echo "Rol asignado: " . $role . "<br>";

        // Redirigir a la página de login
        header('Location: ../dashboard.php');
        exit;
    } else {
        echo 'Error al obtener el token.';
    }
} else {
    echo 'No se recibió el código de autenticación.';
}
