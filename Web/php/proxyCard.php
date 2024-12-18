<?php
session_start();
if (!isset($_SESSION['access_token'])) {
    // Redirigir si no hay sesión activa
    header("Location: /LWeb/Web/html/forbidden.html");
    exit();
}

header('Content-Type: application/json');

// Verifica el parámetro
if (!isset($_GET['cardType'])) {
    echo json_encode(['error' => 'Tipo de tarjeta no especificado']);
    http_response_code(400);
    exit;
}

$cardType = strtoupper($_GET['cardType']);
if (!in_array($cardType, ['CREDIT', 'DEBIT'])) {
    echo json_encode(['error' => 'Tipo de tarjeta inválido']);
    http_response_code(400);
    exit;
}

// Construye la URL de la API
$apiUrl = "http://10.11.0.25:4000/enableCard2/user1/$cardType";

$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if ($httpCode !== 200) {
    echo json_encode(['error' => 'Error al comunicarse con la API']);
    http_response_code($httpCode);
    curl_close($ch);
    exit;
}

curl_close($ch);
echo $response;
