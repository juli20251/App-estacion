<?php
// index.php simplificado: solo landing
$env_file = __DIR__ . "/env.php";
if (file_exists($env_file)) {
    require_once $env_file;
} else {
    die("Error: No se encontró env.php");
}

// Si quieres, podrías redirigir a landing si no hay url
$url = $_GET['url'] ?? 'landing';
if ($url !== 'landing') {
    header("Location: index.php?url=landing");
    exit;
}

// Incluimos la landing directamente
include __DIR__ . '/view/landing.php';
