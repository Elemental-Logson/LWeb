<?php
header('Content-Type: application/json');

// Verifica que el parámetro "enablecard2" esté presente
if (!isset($_GET['enablecard2'])) {
    echo json_encode(['error' => 'Parámetro "enablecard2" no proporcionado']);
    http_response_code(400); // Bad Request
    exit;
}

// Obtiene el parámetro enablecard2 (CREDIT o DEBIT)
$cardType = strtoupper($_GET['enablecard2']);

// Valida que el parámetro sea válido
if (!in_array($cardType, ['CREDIT', 'DEBIT'])) {
    echo json_encode(['error' => 'Tipo de tarjeta inválido. Use CREDIT o DEBIT.']);
    http_response_code(400); // Bad Request
    exit;
}

// Construye la URL de la API con el parámetro adecuado
$apiUrl = "http://10.11.0.25:4000/enableCard2/user1/$cardType";

// Configura la solicitud cURL
$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPGET, true); // Configura como GET

// Desactiva la verificación de SSL (NO RECOMENDADO PARA PRODUCCIÓN)
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

// Ejecuta la solicitud y captura la respuesta
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if ($httpCode !== 200) {
    echo json_encode(['error' => 'Error al comunicarse con la API', 'details' => $response]);
    http_response_code($httpCode);
    curl_close($ch);
    exit;
}

// Cierra la conexión cURL
curl_close($ch);

// Devuelve la respuesta de la API al cliente
echo $response;
