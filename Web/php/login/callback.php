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

    // Mostrar la respuesta completa para depuración
    // echo "<h3>Respuesta del Token:</h3>";
    // echo "<pre>" . print_r($response_data, true) . "</pre>";

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

        // Mostrar las partes del token para depuración
        // echo "<h3>Header del Token JWT:</h3>";
        // echo "<pre>" . print_r($decoded_header, true) . "</pre>";

        // echo "<h3>Payload del Token JWT:</h3>";
        // echo "<pre>" . print_r($decoded_payload, true) . "</pre>";

        // Obtener nombre de usuario y grupos
        $username = isset($decoded_payload['preferred_username']) ? $decoded_payload['preferred_username'] : 'No disponible';
        $roles = isset($decoded_payload['realm_access']['roles']) ? $decoded_payload['realm_access']['roles'] : [];
        $givenName = isset($decoded_payload['given_name']) ? $decoded_payload['given_name'] : 'No disponible'; //Nombre
        $familyName = isset($decoded_payload['family_name']) ? $decoded_payload['family_name'] : 'No disponible'; //Apellido
        $email = isset($decoded_payload['email']) ? $decoded_payload['email'] : 'No disponible'; //Apellido
        $auth_time = isset($decoded_payload['auth_time']) ? $decoded_payload['auth_time'] : null;

        if ($auth_time !== null) {
            // Crear un objeto DateTime con la zona horaria de Europa/Madrid
            $fecha = new DateTime('@' . $auth_time);
            $fecha->setTimezone(new DateTimeZone('Europe/Madrid'));

            // Formatear la fecha y hora en un formato legible y almacenarla en una variable
            $inicioSesionHora = $fecha->format('Y-m-d H:i:s'); // Ejemplo de salida: 2024-12-08 16:10:47

            // Mostrar la hora de inicio de sesión
            echo "Hora de inicio de sesión: " . $inicioSesionHora;
        } else {
            echo 'auth_time no disponible';
        }

        // Determinar el rol
        $role = 'Empresa'; // Rol por defecto
        if (in_array('Admin', $roles)) {
            $role = 'Admin';
        }

        // Guardamos el nombre de usuario y su rol en la sesión
        $_SESSION["username"] = $username;
        $_SESSION["role"] = $role;
        $_SESSION["email"] = $email;
        $_SESSION["given_name"] = $givenName;
        $_SESSION["family_name"] = $familyName;
        $_SESSION["auth_time"] = $inicioSesionHora;

        // Mostrar información del usuario
        // echo "<h3>Información del Usuario:</h3>";
        // echo "Nombre de usuario: " . htmlspecialchars($username) . "<br>";
        // echo "Roles: " . implode(", ", $roles) . "<br>";
        // echo "Rol asignado: " . htmlspecialchars($role) . "<br>";

        // Redirigir a la página de login
        header('Location: ../dashboard.php');
        exit;
    } else {
        echo '<h3>Error:</h3>';
        echo 'Error al obtener el token.<br>';
        echo '<pre>' . htmlspecialchars($response) . '</pre>';
    }
} else {
    echo '<h3>Error:</h3>';
    echo 'No se recibió el código de autenticación.';
}
