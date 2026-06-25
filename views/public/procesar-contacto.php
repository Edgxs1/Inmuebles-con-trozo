<?php
require_once dirname(__DIR__, 2) . '/includes/auth_check.php';
require_once dirname(__DIR__, 2) . '/includes/db_funciones.php';

iniciarSesion();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirigir('contacto.php');
}

$nombre   = trim($_POST['nombre'] ?? '');
$email    = trim($_POST['email'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');
$asunto   = $_POST['asunto'] ?? '';
$mensaje  = trim($_POST['mensaje'] ?? '');

if (empty($nombre) || empty($email) || empty($asunto) || empty($mensaje)) {
    $_SESSION['flash_error'] = 'Todos los campos obligatorios deben estar llenos.';
    redirigir('contacto.php');
}

if (strlen($nombre) < 3) {
    $_SESSION['flash_error'] = 'El nombre debe tener al menos 3 caracteres.';
    redirigir('contacto.php');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['flash_error'] = 'El correo electrónico no es válido.';
    redirigir('contacto.php');
}

if (strlen($mensaje) < 10) {
    $_SESSION['flash_error'] = 'El mensaje debe tener al menos 10 caracteres.';
    redirigir('contacto.php');
}

try {
    guardarContacto([
        'nombre'   => $nombre,
        'email'    => $email,
        'telefono' => $telefono ?: null,
        'asunto'   => $asunto,
        'mensaje'  => $mensaje,
    ]);

    $_SESSION['flash_success'] = '¡Mensaje enviado correctamente! Nos pondremos en contacto contigo pronto.';
} catch (PDOException $e) {
    $_SESSION['flash_error'] = 'Error del servidor. Intenta de nuevo más tarde.';
}

redirigir('contacto.php');
