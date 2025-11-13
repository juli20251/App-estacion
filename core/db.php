<?php
$host = "mattprofe.com.ar";   
$dbname = "10014";            
$user = "10014";              
$pass = "perro.cipres.jugo";  

$conexion = new mysqli($host, $user, $pass, $dbname);

if ($conexion->connect_error) {
    die(json_encode(["error" => "Error de conexión: " . $conexion->connect_error]));
}

$conexion->set_charset("utf8");
?>
