<?php
header('Content-Type: application/json; charset=utf-8');

// Incluimos la conexión
require_once __DIR__ . '/../core/db.php';

// Verificamos la conexión
if (!$conexion) {
    echo json_encode(["error" => "Error de conexión"]);
    exit;
}

// Consultamos las estaciones
$sql = "SELECT chipid, apodo, ubicacion, visitas FROM estaciones"; // Cambiá "estaciones" por tu tabla real
$result = $conexion->query($sql);

if (!$result) {
    echo json_encode(["error" => "Error en la consulta: " . $conexion->error]);
    exit;
}

$estaciones = [];
while ($row = $result->fetch_assoc()) {
    $estaciones[] = [
        "chipid" => $row['chipid'],
        "apodo" => $row['apodo'],
        "ubicacion" => $row['ubicacion'],
        "visitas" => $row['visitas']
    ];
}

echo json_encode($estaciones, JSON_UNESCAPED_UNICODE);
?>
