<?php
session_start();
include __DIR__ . '/../conexion.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';
require '../PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mensaje = '';

if (!isset($_GET['token_action'])) {
    $mensaje = "Token no proporcionado.";
} else {
    $token_action = $_GET['token_action'];

    // Buscar usuario con ese token_action y activo = 0
    $sql = "SELECT id, email FROM usuarios WHERE token_action = ? AND activo = 0";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $token_action);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $email);

    if ($stmt->num_rows > 0) {
        $stmt->fetch();
        $stmt->close();

        // Activar usuario y limpiar token_action
        $sql_update = "UPDATE usuarios SET activo = 1, token_action = NULL, active_date = NOW() WHERE id = ?";
        $stmt_up = $conexion->prepare($sql_update);
        $stmt_up->bind_param("i", $id);
        $stmt_up->execute();
        $stmt_up->close();

        // Enviar correo de confirmación
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'tpbaez16@gmail.com';
            $mail->Password = 'buzgordpjvfybxqy';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            $mail->CharSet = 'UTF-8';

            $mail->setFrom('tpbaez16@gmail.com', 'App Estacion');
            $mail->addAddress($email); 

            $mail->isHTML(true);
            $mail->Subject = 'Cuenta activada';
            $mail->Body = "
                <h3>Tu cuenta ha sido activada</h3>
                <p>Ahora podés iniciar sesión en la App Estacion.</p>
                <a href='http://localhost/app-estacion/login.php' style='display:inline-block;padding:10px 20px;background:#28a745;color:#fff;text-decoration:none;'>Iniciar sesión</a>
            ";

            $mail->send();
            $mensaje = "Cuenta activada correctamente. Revisa tu correo para más información.";
        } catch (Exception $e) {
            $mensaje = "Cuenta activada, pero no se pudo enviar el correo: " . $mail->ErrorInfo;
        }

    } else {
        $mensaje = "El token no corresponde a un usuario o ya fue utilizado.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Validación de usuario</title>
</head>
<body>
    <h2>Validación de usuario</h2>
    <p><?php echo $mensaje; ?></p>
    <a href="/alumno/10014/app-estacion/view/login.php">Volver al login</a>
</body>
</html>
