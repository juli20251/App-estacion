<?php
session_start();
session_destroy(); // Cerramos sesión si estaba abierta
?>
<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Usuario bloqueado</title></head>
<body>
<h2>Tu usuario está bloqueado</h2>
<p>Contactá con soporte para más información.</p>
<a href="/alumno/10014/app-estacion/view/login.php">Volver al login</a>
</body>
</html>
