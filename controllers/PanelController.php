<?php
require_once "models/Estacion.php";

if (isset($_GET['fetch'])) {
    header("Content-Type: application/json");

    $ch = curl_init("http://mattprofe.com.ar:81/alumno/10014/app-estacion/datos.php");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $result = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error) {
        echo json_encode(["error" => "Error al obtener API", "detalle" => $error]);
    } else {
        echo $result;
    }
    exit;
}

require "view/panel.php";
?>
