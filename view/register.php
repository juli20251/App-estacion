<?php
include __DIR__ . '/../core/db.php';  // o config.php según el nombre
include __DIR__ . '/../core/Usuario.php';

$usuario = new Usuario($conexion);
$mensaje = '';


$usuario = new Usuario($conexion);
$mensaje = '';

if($_SERVER['REQUEST_METHOD']==='POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    if($usuario->register($email, $password)) {
        $mensaje = "Registrado correctamente. Revisá tu email para activar la cuenta.";
    } else {
        $mensaje = "Error: usuario existente o no se pudo enviar el correo.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Registro</title></head>
<body>
<h2>Registro</h2>
<p><?= $mensaje ?></p>
<form method="post">
    <input type="email" name="email" required placeholder="Email"><br>
    <input type="password" name="password" required placeholder="Contraseña"><br>
    <button>Registrar</button>
</form>
<a href="/alumno/10014/app-estacion/view/login.php">Volver al login</a>
</body>
</html>
