<?php
include __DIR__ . '/../core/db.php';       
include __DIR__ . '/../core/Usuario.php';  

$usuario = new Usuario($conexion);         
$mensaje = '';
$token_action = $_GET['token_action'] ?? '';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';
    $password2 = $_POST['password2'] ?? '';

    if($password !== $password2) {
        $mensaje = "Las contraseñas no coinciden.";
    } elseif($usuario->resetPassword($token_action, $password)) {
        $mensaje = "Contraseña cambiada. Podés hacer login.";
    } else {
        $mensaje = "Token inválido o expirado.";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Resetear contraseña</title></head>
<body>
<h2>Resetear contraseña</h2>
<p><?= $mensaje ?></p>
<form method="post">
    <input type="password" name="password" required placeholder="Nueva contraseña"><br>
    <input type="password" name="password2" required placeholder="Repetir contraseña"><br>
    <button>Cambiar</button>
</form>
<a href="/alumno/10014/app-estacion/view/login.php">Volver al login</a>
</body>
</html>
