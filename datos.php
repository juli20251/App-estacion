<?php
header('Content-Type: application/json');

// Configuración del contexto para evitar errores de SSL
$context = stream_context_create([
    "ssl" => [
        "verify_peer" => false,
        "verify_peer_name" => false,
    ]
]);

// URL remota que devuelve los datos de las estaciones
$url = "https://mattprofe.com.ar/proyectos/app-estacion/datos.php?mode=list-stations";

// Intentar obtener los datos
$datos = @file_get_contents($url, false, $context);

// Si falla, devolver un JSON vacío con error
if ($datos === false) {
    echo json_encode([
        "error" => "No se pudo obtener la lista de estaciones"
    ]);
    exit;
}

// Devolver los datos tal cual vienen
echo $datos;
?>
