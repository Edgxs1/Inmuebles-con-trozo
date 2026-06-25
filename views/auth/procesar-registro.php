<?php
require_once dirname(__DIR__, 2) . '/config/config.php';
require_once dirname(__DIR__, 2) . '/config/db.php';
require_once dirname(__DIR__, 2) . '/includes/auth_check.php';

iniciarSesion();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirigir(BASE_URL . 'views/auth/registro.php');
}

$nombre     = trim($_POST['nombre'] ?? '');
$email      = trim($_POST['email'] ?? '');
$password   = $_POST['password'] ?? '';
$tipoCuenta = $_POST['tipo_cuenta'] ?? '';

if (empty($nombre) || empty($email) || empty($password) || empty($tipoCuenta)) {
    $_SESSION['flash_error'] = 'Todos los campos son obligatorios.';
    redirigir(BASE_URL . 'views/auth/registro.php');
}

if (strlen($nombre) < 3) {
    $_SESSION['flash_error'] = 'El nombre debe tener al menos 3 caracteres.';
    redirigir(BASE_URL . 'views/auth/registro.php');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['flash_error'] = 'El correo electrónico no es válido.';
    redirigir(BASE_URL . 'views/auth/registro.php');
}

if (strlen($password) < 8) {
    $_SESSION['flash_error'] = 'La contraseña debe tener al menos 8 caracteres.';
    redirigir(BASE_URL . 'views/auth/registro.php');
}

if (!in_array($tipoCuenta, ['Administrador', 'Vendedor', 'Comprador'])) {
    $_SESSION['flash_error'] = 'Tipo de cuenta no válido.';
    redirigir(BASE_URL . 'views/auth/registro.php');
}

try {
    $pdo = getDB();

    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = :email LIMIT 1");
    $stmt->execute(['email' => $email]);

    if ($stmt->fetch()) {
        $_SESSION['flash_error'] = 'Este correo electrónico ya está registrado.';
        redirigir(BASE_URL . 'views/auth/registro.php');
    }

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    $fechaActual = date('Y-m-d');

    $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, password, tipo, fecha_registro) VALUES (:nombre, :email, :password, :tipo, :fecha)");
    $stmt->execute([
        'nombre'        => $nombre,
        'email'         => $email,
        'password'      => $passwordHash,
        'tipo'          => $tipoCuenta,
        'fecha'         => $fechaActual
    ]);

    $_SESSION['flash_success'] = 'Cuenta creada correctamente. Ahora puedes iniciar sesión.';
    redirigir(BASE_URL . 'views/auth/login.php');

} catch (PDOException $e) {
    $_SESSION['flash_error'] = 'Error SQL: ' . $e->getMessage();
    redirigir(BASE_URL . 'views/auth/registro.php');
}