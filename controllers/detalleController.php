<?php
session_start(); 

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

$section = "Detalles";

$tpl = new Palta("detalle");

$tpl->assign([
    "APP_SECTION" => $section,
]);

$tpl->printToScreen();
?>
