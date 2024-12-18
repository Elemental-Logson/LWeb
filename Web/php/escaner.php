<?php
session_start();
if (!isset($_SESSION['access_token']) || $_SESSION['role'] !== "Admin") {
    // Redirigir si no hay sesión activa
    header("Location: /LWeb/Web/html/forbidden.html");
    exit();
}
header('Content-Type: application/json');

$request_uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];
$api_base_url = "http://10.11.0.147:8001";

if ($method === 'POST') {
    // Obtener datos del cuerpo de la solicitud
    $input = json_decode(file_get_contents('php://input'), true);

    if (!$input || !isset($input['endpoint']) || !isset($input['data'])) {
        echo json_encode(['error' => 'Solicitud inválida']);
        http_response_code(400);
        exit;
    }

    $endpoint = $input['endpoint'];
    $data = $input['data'];

    // Construir la URL de la API
    $api_url = $api_base_url . $endpoint;

    // Configurar cURL
    $ch = curl_init($api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

    // Ejecutar la solicitud
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // Verificar errores en cURL
    if (curl_errno($ch)) {
        $error = curl_error($ch);
        echo json_encode(['error' => $error]);
        http_response_code(500);
        curl_close($ch);
        exit;
    }

    // Pasar la respuesta al cliente
    http_response_code($httpCode);
    curl_close($ch);
    echo $response;
} else {
    echo json_encode(['error' => 'Método no permitido']);
    http_response_code(405);
}
