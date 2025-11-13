<?php
header("Content-Type: application/json; charset=utf-8");

$chipid = $_GET['chipid'] ?? '';
$cant = $_GET['cant'] ?? '7';

if ($chipid == '') {
    echo json_encode(["error" => "chipid no especificado"]);
    exit;
}

$url = "https://mattprofe.com.ar/proyectos/app-estacion/datos.php?chipid=$chipid&cant=$cant";
$datos = @file_get_contents($url);

if ($datos === false) {
    echo json_encode(["error" => "No se pudieron obtener los datos de la estación"]);
    exit;
}

echo $datos;
