<?php
session_start();
// Incluir el archivo de configuración
require_once($_SERVER['DOCUMENT_ROOT'] . '/LWeb/Web/www/config.php');

// Verificar que se envió una solicitud POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Leer el cuerpo de la solicitud
    $input = file_get_contents('php://input');
    // Decodificar el JSON recibido
    $data = json_decode($input, true);

    // Verificar si la decodificación fue exitosa
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(['status' => 'error', 'message' => 'Datos JSON inválidos']);
        exit;
    }

    // Capturar datos del formulario
    $username = $data['username'] ?? null;
    $email = $data['email'] ?? null;
    $password = $data['password'] ?? null;

    // Validar que todos los campos estén presentes
    if (!$username || !$email || !$password) {
        echo json_encode(['status' => 'error', 'message' => 'Todos los campos son obligatorios']);
        exit;
    }

    // Obtener token de administración de Keycloak
    $ch = curl_init($token_url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
        'client_id' => $client_id,
        'client_secret' => $client_secret,
        'grant_type' => 'client_credentials',
    ]));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);

    // Verificar si ocurrió un error en cURL
    if (curl_errno($ch)) {
        echo json_encode(['status' => 'error', 'message' => 'Error en la solicitud cURL: ' . curl_error($ch)]);
        curl_close($ch);
        exit;
    }

    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code !== 200) {
        echo json_encode(['status' => 'error', 'message' => 'Error al obtener el token de administración. Código HTTP: ' . $http_code]);
        exit;
    }

    $tokenData = json_decode($response, true);
    if (isset($tokenData['access_token'])) {
        $adminToken = $tokenData['access_token'];
        $_SESSION['access_token'] = $adminToken; // Almacenar el token en la sesión
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Token de acceso no encontrado en la respuesta']);
        exit;
    }

    // Crear usuario en Keycloak
    $userData = [
        'username' => $username,
        'enabled' => true,
        'email' => $email,
        'credentials' => [
            [
                'type' => 'password',
                'value' => $password,
                'temporary' => false,
            ],
        ],
    ];

    $ch = curl_init($users_url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $adminToken,
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($userData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);

    // Verificar si ocurrió un error en cURL
    if (curl_errno($ch)) {
        echo json_encode(['status' => 'error', 'message' => 'Error en la solicitud cURL: ' . curl_error($ch)]);
        curl_close($ch);
        exit;
    }

    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code === 201) {
        echo json_encode(['status' => 'success', 'message' => 'Usuario creado con éxito']);
    } else {
        $error = json_decode($response, true);
        $errorMessage = $error['errorMessage'] ?? 'Error al crear el usuario';
        echo json_encode(['status' => 'error', 'message' => $errorMessage, 'http_code' => $http_code]);
    }
}
