<?php
session_start();

/* ============================================================
 * Includes y configuración base
 * ============================================================ */
require_once __DIR__ . "/../config.php";
require_once __DIR__ . "/../Modelo/modeloUsuario.php";
require_once __DIR__ . "/../Modelo/conexion.php";
require_once __DIR__ . "/../vendor/autoload.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/* ============================================================
 * Conexión y modelo
 * ============================================================ */
// El constructor del modelo ya se encarga de conectar si lo definimos así anteriormente
$modeloUsuario = new modeloUsuario();

/* ============================================================
 * Determinar acción
 * ============================================================ */
$accion = $_GET['accion'] ?? $_POST['accion'] ?? "";

/* ============================================================
 * 1) Mostrar formulario de reseteo
 * ============================================================ */
if ($accion === 'form_reset') {
    $token = $_GET['token'] ?? '';
    $user  = $modeloUsuario->buscarPorToken($token);

    // MongoDB devuelve objetos UTCDateTime, usamos toDateTime() para comparar
    if (!$user || $user['reset_expires']->toDateTime() < new DateTime()) {
        die("Enlace inválido o expirado");
    }

    $current_token = $token;
    include __DIR__ . "/../Vista/vistaResetPass.php";
    exit();
}

/* ============================================================
 * 2) Solicitar reseteo (envía correo)
 * ============================================================ */
if ($accion === 'solicitar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = trim($_POST['correo'] ?? '');
    $user   = $modeloUsuario->buscarPorCorreo($correo);

    if (!$user) {
        header("Location: " . BASE_URL . "index.php?msg=correo_no_encontrado");
        exit();
    }

    $token  = bin2hex(random_bytes(32));
    // Definimos la expiración (1 hora)
    $expira = date('Y-m-d H:i:s', time() + 3600); 

    // Usamos el _id de MongoDB (convertido a string para el modelo)
    $modeloUsuario->guardarTokenReset((string)$user['_id'], $token, $expira);

    $link = BASE_URL . "Controlador/controladorPassword.php?accion=form_reset&token=" . $token;

    // Envío de correo (Mailtrap u otro servicio según tus env vars)
    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = getenv('SMTP_HOST') ?: 'sandbox.smtp.mailtrap.io';
        $mail->SMTPAuth   = true;
        $mail->Username   = getenv('SMTP_USERNAME') ?: 'tu_usuario';
        $mail->Password   = getenv('SMTP_PASSWORD') ?: 'tu_password';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = (int)(getenv('SMTP_PORT') ?: 587);

        $mail->setFrom(getenv('SMTP_FROM_EMAIL') ?: 'no-reply@inventario.local', 'Sistema de Inventario');
        $mail->addAddress($correo);
        $mail->Subject = "Restablecer contrasena";
        $mail->Body    = "Haz clic en el siguiente enlace para cambiar tu contrasena:\n\n" . $link;

        $mail->send();
    } catch (Exception $e) {
        error_log("Error al enviar correo: " . $mail->ErrorInfo);
        die("Error al enviar correo (verifique logs)");
    }

    header("Location: " . BASE_URL . "index.php?msg=correo_enviado");
    exit();
}

/* ============================================================
 * 3) Resetear contraseña
 * ============================================================ */
if ($accion === 'reset' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'] ?? '';
    $pass1 = $_POST['pass1'] ?? '';
    $pass2 = $_POST['pass2'] ?? '';

    if ($pass1 !== $pass2 || strlen($pass1) < 6) {
        $_SESSION['reset_error'] = 'pass_invalida';
        $current_token = $token;
        include __DIR__ . "/../Vista/vistaResetPass.php";
        exit();
    }

    $user = $modeloUsuario->buscarPorToken($token);

    // Comparación de expiración con objeto BSON UTCDateTime
    if (!$user || $user['reset_expires']->toDateTime() < new DateTime()) {
        die("Enlace inválido o expirado");
    }

    $hash = password_hash($pass1, PASSWORD_DEFAULT);
    // Usamos (string)$user['_id'] para pasar el identificador correcto al modelo
    $modeloUsuario->actualizarPassword((string)$user['_id'], $hash);

    header("Location: " . BASE_URL . "index.php?msg=pass_actualizada");
    exit();
}