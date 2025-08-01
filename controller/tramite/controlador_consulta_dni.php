<?php

require_once __DIR__ . '/../vendor/autoload.php'; // Ajusta según dónde está este archivo

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../'); // Ajusta según ubicación de tu .env
$dotenv->load();

header("Content-Type: application/json");

// API Key y Validación del DNI
$token = $_ENV['API_TOKEN']; // Token desde .env
$dni = isset($_GET['dni']) ? $_GET['dni'] : null;

if (!$dni || strlen($dni) != 8 || !is_numeric($dni)) {
    echo json_encode(["success" => false, "message" => "DNI inválido"]);
    exit;
}

// Hacer la solicitud a la API
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://api.apis.net.pe/v2/reniec/dni?numero=' . $dni,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_SSL_VERIFYPEER => 0,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => array(
        'Authorization: Bearer ' . $token
    ),
));

$response = curl_exec($curl);
curl_close($curl);

$persona = json_decode($response, true);

// Devolver JSON al frontend
if (isset($persona["numeroDocumento"])) {
    echo json_encode(["success" => true, "data" => $persona]);
} else {
    echo json_encode(["success" => false, "message" => "DNI no encontrado"]);
}
?>
