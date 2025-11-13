<?php
session_start();
include __DIR__ . '/../core/db.php';
include __DIR__ . '/../core/Usuario.php';
$usuario = new Usuario($conexion);

$mensaje = '';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Login admin
    if($email === 'admin-estacion' && $password === 'admin1234'){
        $_SESSION['user'] = 'admin-estacion';
        // ✅ Ruta absoluta
        header("Location: /alumno/10014/app-estacion/view/administrador.php");
        exit;
    }

    // Login usuario normal
    if($usuario->login($email, $password)) {
        $_SESSION['usuario'] = $email;
        // ✅ Ruta absoluta
        header("Location: /alumno/10014/app-estacion/view/panel.php");
        exit;
    }

    $mensaje = "Usuario o contraseña incorrecta, o usuario no activado/bloqueado.";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Login</title>
</head>
<body>
<h2>Login</h2>
<p style="color:red;"><?= $mensaje ?></p>
<form method="post">
    <input type="text" name="email" placeholder="Usuario o Email" required><br>
    <input type="password" name="password" placeholder="Contraseña" required><br>
    <button type="submit">Ingresar</button>
</form>
<p>
<a href="/alumno/10014/app-estacion/view/register.php">Registrarse</a> | 
<a href="/alumno/10014/app-estacion/view/recovery.php">Recuperar contraseña</a>
</p>
</body>
</html>
