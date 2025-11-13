<?php
require_once __DIR__ . '/Mailer.php';
class Usuario {
    private $db;
    private $mailer;
    public function __construct($conexion) {
        $this->db = $conexion;
        $this->mailer = new Mailer(); 
        // Opcional pero útil: Configurar MySQLi para reportar errores
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    }
    // Registro de usuario
    public function register($email, $password) {
        // Verificar si ya existe el email
        $sql = "SELECT id FROM usuarios1 WHERE email=?";
        $stmt = $this->db->prepare($sql);
        if(!$stmt) {
            error_log("Error prepare SELECT register: " . $this->db->error);
            return false;
        }
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows > 0) return false;

        // Preparar datos
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $token_action = bin2hex(random_bytes(16));
        $token_usuario = bin2hex(random_bytes(16));
        $activo = 0;
        $bloqueado = 0;
        // Insertar usuario
        $insert = $this->db->prepare("
            INSERT INTO usuarios1(email, `contraseña`, activo, bloqueado, token, token_action)
            VALUES(?,?,?,?,?,?)
        ");
        if(!$insert) {
            error_log("Error prepare INSERT register: " . $this->db->error);
            return false;
        }
        $insert->bind_param("ssssss", $email, $hash, $activo, $bloqueado, $token_usuario, $token_action);

        if($insert->execute()) {
            $link = "http://mattprofe.com.ar:81/alumno/10014/app-estacion/view/activate.php?token_action=$token_action";
            $body = "<p>Activá tu cuenta haciendo click:</p><a href='$link'>Activar cuenta</a>";
            return $this->mailer->send($email, "Activación de cuenta", $body);
        }
        return false;
    }

    // Activar cuenta
    public function activate($token_action) {
        $update = $this->db->prepare("UPDATE usuarios1 SET activo=1, token_action=NULL WHERE token_action=?");
        if(!$update) {
            error_log("Error prepare UPDATE activate: " . $this->db->error);
            return false;
        }
        $update->bind_param("s", $token_action);
        return $update->execute();
    }
    // Login
    public function login($email, $password) {
        $sql = "SELECT `contraseña`, activo, bloqueado FROM usuarios1 WHERE email=?";
        $stmt = $this->db->prepare($sql);
        if(!$stmt) {
            error_log("Error prepare SELECT login: " . $this->db->error);
            return false;
        }
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows == 0) return false;

        $stmt->bind_result($hash, $activo, $bloqueado);
        $stmt->fetch();

        if($activo != 1 || $bloqueado == 1) return false;
        return password_verify($password, $hash);
    }
    // Enviar correo de recuperación
    public function enviarRecuperacion($email) {
        $sql = "SELECT id, bloqueado FROM usuarios1 WHERE email=?";
        $stmt = $this->db->prepare($sql);
        if(!$stmt) {
            error_log("Error prepare SELECT enviarRecuperacion: " . $this->db->error);
            return false;
        }
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows == 0) return false;

        $stmt->bind_result($id, $bloqueado);
        $stmt->fetch();
        if($bloqueado == 1) return false;

        // Generar token de recuperación
        $token_action = bin2hex(random_bytes(16));
        $update = $this->db->prepare("UPDATE usuarios1 SET token_action=?, recupero=1 WHERE id=?");
        if(!$update) {
            error_log("Error prepare UPDATE enviarRecuperacion: " . $this->db->error);
            return false;
        }
        $update->bind_param("si", $token_action, $id);
        $update->execute();

        $link = "http://localhost/app-estacion/view/reset.php?token_action=$token_action";
        $body = "<p>Hacé click para resetear tu contraseña:</p><a href='$link'>Resetear contraseña</a>";
        return $this->mailer->send($email, "Recuperación de contraseña", $body);
    }

    // Resetear contraseña
    public function resetPassword($token_action, $password) {
    // Verifico que la conexión exista
    if (!$this->db) {
        error_log("resetPassword: La conexión a la base de datos no existe.");
        return false;
    }
    try {
        // Intento preparar la consulta SELECT
        $stmt = $this->db->prepare("SELECT id FROM usuarios1 WHERE token_action=? AND recupero=1");
        if (!$stmt) {
            throw new Exception("Error prepare SELECT resetPassword: " . $this->db->error);
        }

        $stmt->bind_param("s", $token_action);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 0) {
            // No hay token válido
            return false;
        }

        $stmt->bind_result($id);
        $stmt->fetch();
        $stmt->close();

        // Preparo UPDATE para cambiar contraseña
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $update = $this->db->prepare("UPDATE usuarios1 SET `contraseña`=?, token_action=NULL, recupero=0 WHERE id=?");
        if (!$update) {
            throw new Exception("Error prepare UPDATE resetPassword: " . $this->db->error);
        }

        $update->bind_param("si", $hash, $id);
        $result = $update->execute();
        $update->close();

        return $result;

    } catch (Exception $e) {
        // Logging del error completo
        error_log("resetPassword Exception: " . $e->getMessage());
        echo "Ocurrió un error al intentar resetear la contraseña. Revisa los logs.";
        return false;
    }
}


}
?>