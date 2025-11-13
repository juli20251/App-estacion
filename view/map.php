<?php
session_start();
if(!isset($_SESSION['user']) || $_SESSION['user'] != 'admin-estacion'){
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../core/db.php';

// Clientes únicos
$clients = $conexion->query("
    SELECT ip, latitud, longitud, COUNT(*) AS accesos
    FROM tracker
    GROUP BY ip, latitud, longitud
")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Mapa de Clientes</title>
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<style>
#map { height: 100vh; width: 100%; }
.top-right { position:absolute; top:10px; right:10px; z-index:1000; }
a { text-decoration:none; color:white; padding:8px 16px; background:#007bff; border-radius:6px; display:inline-block; }
a:hover { background:#0056b3; }
</style>
</head>
<body>
<div class="top-right">
    <a href="/alumno/10014/app-estacion/view/administrador.php">Volver</a>
</div>

<div id="map"></div>
<script>
var map = L.map('map').setView([-34.61, -58.38], 4);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
}).addTo(map);

var clients = <?= json_encode($clients) ?>;
clients.forEach(function(client){
    if(client.latitud && client.longitud){
        var marker = L.marker([client.latitud, client.longitud]).addTo(map);
        marker.bindPopup('IP: ' + client.ip + '<br>Accesos: ' + client.accesos);
    }
});
</script>
</body>
</html>
