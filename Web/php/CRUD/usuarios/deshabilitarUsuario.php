<?php
session_start();
// Incluir el archivo de configuración
require_once($_SERVER['DOCUMENT_ROOT'] . '/LWeb/Web/www/config.php');
if (!isset($_SESSION['access_token']) || (!isset($_SESSION["role"]) != "Admin")) {
    // Si no hay token o el rol no es admin, destruir la sesión y redirigir al login
    session_unset();  // Elimina todas las variables de sesión
    session_destroy(); // Destruye la sesión
    header('Location: /lweb/Web/php/login/loginUnificado.php'); // Redirige al login
    exit;
}
// Verificar que se recibió una solicitud PUT
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Leer el cuerpo de la solicitud
    $input = file_get_contents('php://input');
    // Decodificar el JSON recibido
    $data = json_decode($input, true);

    // Capturar el ID del usuario
    $userId = $data['userId'] ?? null;

    // Validar que el ID del usuario esté presente
    if (!$userId) {
        echo json_encode(['status' => 'error', 'message' => 'El ID del usuario es obligatorio']);
        exit;
    }

    // Obtener el token de administración de Keycloak
    $ch = curl_init($token_url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
        'client_id' => $client_id,
        'client_secret' => $client_secret,
        'grant_type' => 'client_credentials',
    ]));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code !== 200) {
        echo json_encode(['status' => 'error', 'message' => 'Error al obtener el token de administración']);
        exit;
    }

    $tokenData = json_decode($response, true);
    $adminToken = $tokenData['access_token'];

    // Deshabilitar el usuario en Keycloak
    $userUpdateData = [
        'enabled' => false,
    ];

    $ch = curl_init($users_url . '/' . $userId);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: ' . 'Bearer ' . $adminToken,
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($userUpdateData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code === 204) {
        echo json_encode(['status' => 'success', 'message' => 'Usuario deshabilitado con éxito']);
    } else {
        $error = json_decode($response, true);
        echo json_encode(['status' => 'error', 'message' => $error['errorMessage'] ?? 'Error al deshabilitar el usuario']);
    }
}
