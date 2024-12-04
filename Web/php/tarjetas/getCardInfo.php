<?php

// Función para realizar la solicitud cURL a la API
function getDataFromApi($url)
{
    // Inicializa cURL
    $ch = curl_init();

    // Establece la URL de la API
    curl_setopt($ch, CURLOPT_URL, $url);

    // Indica que se recibirá la respuesta como una cadena
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Ejecuta la solicitud
    $response = curl_exec($ch);

    // Verifica si hubo algún error
    if (curl_errno($ch)) {
        echo 'Error en cURL: ' . curl_error($ch);
    }

    // Cierra cURL
    curl_close($ch);

    // Devuelve la respuesta
    return $response;
}

// URL de la API que proporcionaste
$apiUrl = 'http://10.11.0.25:4000/infocards2/user1';

// Obtén los datos cifrados desde la API
$ciphertext_b64 = getDataFromApi($apiUrl);

// Clave secreta para el descifrado (32 bytes para AES-256)
$key = "KeyMustBe16ByteOR24ByteOR32ByT2!";

// Decodificar Base64
$ciphertext = base64_decode($ciphertext_b64);

// Descifrar el texto con AES-256 en modo ECB
$iv = '';  // ECB no requiere IV
$plaintext = openssl_decrypt($ciphertext, 'AES-256-ECB', $key, OPENSSL_RAW_DATA, $iv);

// Imprimir el resultado descifrado (en texto plano)
echo $plaintext;
