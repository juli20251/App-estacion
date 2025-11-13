<?php
session_start();
if(!isset($_SESSION['user']) || $_SESSION['user'] != 'admin-estacion') {
    header("Location: login.php"); 
    exit;
}

require_once __DIR__ . '/../core/db.php';

$res_users = $conexion->query("SELECT COUNT(*) AS total FROM usuarios1")->fetch_assoc();
$res_clients = $conexion->query("SELECT COUNT(*) AS total FROM tracker")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Panel de Administrador</title>
<style>
body { font-family: sans-serif; text-align:center; margin-top:50px; }
a { text-decoration:none; margin:5px; color:white; padding:10px 20px; background:#007bff; border-radius:8px; display:inline-block; }
a:hover { background:#0056b3; }
.top-right { position:absolute; top:10px; right:10px; }
</style>
</head>
<body>
<div class="top-right">
    <a href="/alumno/10014/app-estacion/logout.php">Cerrar sesión</a>
</div>

<h1>Panel de Administrador</h1>

<a href="/alumno/10014/app-estacion/view/map.php">Mapa de clientes</a>

<p>Usuarios registrados: <?= $res_users['total'] ?></p>
<p>Clientes totales: <?= $res_clients['total'] ?></p>
</body>
</html>
