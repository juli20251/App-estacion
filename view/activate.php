<?php
// activate.php
include __DIR__ . '/../core/db.php';        
include __DIR__ . '/../core/Usuario.php';   

$usuario = new Usuario($conexion);

$mensaje = '';

if(isset($_GET['token_action'])) {
    $token = $_GET['token_action'];
    if($usuario->activate($token)) {
        $mensaje = "Cuenta activada correctamente. Ya podés iniciar sesión.";
    } else {
        $mensaje = "Token inválido o cuenta ya activada.";
    }
} else {
    $mensaje = "No se recibió token de activación.";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Activación de cuenta</title>
    <style>
        body { font-family: sans-serif; text-align: center; margin-top: 50px; background: #f5f5f5; color: #333; }
        a button { padding: 10px 20px; border-radius: 5px; border: none; background: #007BFF; color: white; cursor: pointer; transition: 0.3s; }
        a button:hover { background: #0056b3; }
    </style>
</head>
<body>
    <h2>Activación de cuenta</h2>
    <p><?= $mensaje ?></p>
    <a href="/alumno/10014/app-estacion/view/login.php"><button>Ir al login</button></a>
</body>
</html>
