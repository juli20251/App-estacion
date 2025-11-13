<?php
$env_file = __DIR__ . "/../env.php";
if (file_exists($env_file)) {
    require_once $env_file;
} else {
    die("Error: No se encontró env.php");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title><?= defined('APP_NAME') ? APP_NAME : 'Mi App' ?></title>
<style>
body { font-family: sans-serif; text-align: center; margin-top: 50px; background: linear-gradient(135deg, #3c1053, #ad5389); color: white; }
button { background: #ff4081; border: none; color: white; padding: 10px 20px; border-radius: 8px; cursor: pointer; margin-top: 10px; font-size: 16px; transition: 0.3s; }
button:hover { background: #ff609d; }
</style>
</head>
<body>
<h1><?= defined('APP_NAME') ? APP_NAME : 'Mi App' ?></h1>
<p>Esta aplicación muestra las estaciones meteorológicas conectadas al sistema.</p>
<a href="/alumno/10014/app-estacion/view/login.php"><button>Ingresar</button></a>
</body>
</html>
