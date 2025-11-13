<?php
require_once __DIR__ . '/core/db.php'; 
$res = $conexion->query("
    SELECT ip, latitud, longitud, COUNT(*) AS accesos 
    FROM tracker 
    GROUP BY ip, latitud, longitud
");

$clients = [];
while($row = $res->fetch_assoc()){
    $clients[] = $row;
}

header('Content-Type: application/json');
echo json_encode($clients);
exit;
?>
