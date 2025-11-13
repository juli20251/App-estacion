<?php
session_start();
include __DIR__ . '/../core/db.php';
include __DIR__ . '/../core/Usuario.php';


$usuario = new Usuario($conexion);
$mensaje = '';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    if ($usuario->enviarRecuperacion($email)) {
        $mensaje = "Se envió un correo de recuperación.";
    } else {
        $mensaje = "Usuario no encontrado o bloqueado.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Recuperar contraseña</title></head>
<body>
<h2>Recuperar contraseña</h2>
<p><?= $mensaje ?></p>
<form method="post">
    <input type="email" name="email" required placeholder="Tu email"><br>
    <button>Enviar correo</button>
</form>
<a href="/alumno/10014/app-estacion/view/login.php">Volver al login</a>
</body>
</html>
