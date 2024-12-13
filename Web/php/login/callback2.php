<?php
session_start();

// Incluir el archivo de configuración
require_once($_SERVER['DOCUMENT_ROOT'] . '/LWeb/Web/www/config2.php');

// Verificar si el código de autenticación está presente en la URL
if (isset($_GET['code'])) {
    $code = $_GET['code'];

    // Preparar los datos para el POST
    $data = [
        'grant_type' => 'authorization_code',
        'code' => $code,
        'redirect_uri' => $redirect_uri,   // Debe coincidir con la URL registrada en Keycloak
        'client_id' => $client_id,
        'client_secret' => $client_secret
    ];

    // Iniciar la solicitud cURL para obtener el token
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $token_url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // TEMPORALMENTE (no usar en producción si el certificado no es válido)
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    $response = curl_exec($ch);

    if ($response === false) {
        echo 'Error cURL: ' . curl_error($ch);
        curl_close($ch);
        exit;
    }

    curl_close($ch);

    // Convertir la respuesta a JSON
    $response_data = json_decode($response, true);

    // Verificar si se obtuvo un token
    if (isset($response_data['access_token'])) {
        // Obtener el token de acceso
        $access_token = $response_data['access_token'];

        // Dividir el JWT en sus partes
        $token_parts = explode('.', $access_token);
        if (count($token_parts) === 3) {
            list($header, $payload, $signature) = $token_parts;

            // Decodificar las partes Base64
            $decoded_header = json_decode(base64_decode($header), true);
            $decoded_payload = json_decode(base64_decode($payload), true);

            // Obtener campos del payload
            $username = isset($decoded_payload['preferred_username']) ? $decoded_payload['preferred_username'] : 'No disponible';
            $roles = isset($decoded_payload['realm_access']['roles']) ? $decoded_payload['realm_access']['roles'] : [];
            $givenName = isset($decoded_payload['given_name']) ? $decoded_payload['given_name'] : 'No disponible';
            $familyName = isset($decoded_payload['family_name']) ? $decoded_payload['family_name'] : 'No disponible';
            $email = isset($decoded_payload['email']) ? $decoded_payload['email'] : 'No disponible';
            $auth_time = isset($decoded_payload['auth_time']) ? $decoded_payload['auth_time'] : null;

            $inicioSesionHora = 'No disponible';
            if ($auth_time !== null) {
                $fecha = new DateTime('@' . $auth_time);
                $fecha->setTimezone(new DateTimeZone('Europe/Madrid'));
                $inicioSesionHora = $fecha->format('Y-m-d H:i:s');
            }

            // Determinar el rol
            $role = 'Empresa';
            if (in_array('Admin', $roles)) {
                $role = 'Admin';
            }

            // Imprimir datos del usuario
            echo $username . "<br>";
            echo $role . "<br>";
            echo $email . "<br>";
            echo $givenName . "<br>";
            echo $familyName . "<br>";
            echo $inicioSesionHora . "<br>";
            
            // header('Location: ../dashboard.php');
            exit;
        } else {
            echo '<h3>Error:</h3>';
            echo 'El token JWT no tiene el formato esperado.';
        }
    } else {
        echo '<h3>Error al obtener el token:</h3>';
        echo '<pre>' . htmlspecialchars($response) . '</pre>';
    }
} else {
    echo '<h3>Error:</h3>';
    echo 'No se recibió el código de autenticación.';
}
