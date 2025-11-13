<?php
header('Content-Type: application/json; charset=utf-8');

if (!isset($_GET['chipid'])) {
    echo json_encode(["error" => "Falta chipid"]);
    exit;
}

$chipid = $_GET['chipid'];
$url = "https://mattprofe.com.ar/proyectos/app-estacion/estacion/" . $chipid;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 200 && $response) {
    echo $response;
} else {
    echo json_encode(["error" => "No se pudo obtener la estación"]);
}
?>
