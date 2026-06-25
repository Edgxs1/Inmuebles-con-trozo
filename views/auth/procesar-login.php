<?php
require_once dirname(__DIR__, 2) . '/config/config.php';
require_once dirname(__DIR__, 2) . '/config/db.php';
require_once dirname(__DIR__, 2) . '/includes/auth_check.php';

iniciarSesion();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirigir(BASE_URL . 'views/auth/login.php');
}

$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    $_SESSION['flash_error'] = 'Todos los campos son obligatorios.';
    redirigir(BASE_URL . 'views/auth/login.php');
}

try {
    $pdo = getDB();
    $stmt = $pdo->prepare("SELECT id, nombre, email, password, tipo, activo FROM usuarios WHERE email = :email LIMIT 1");
    $stmt->execute(['email' => $email]);
    $usuario = $stmt->fetch();

    if (!$usuario || !password_verify($password, $usuario['password'])) {
        $_SESSION['flash_error'] = 'Credenciales incorrectas.';
        redirigir(BASE_URL . 'views/auth/login.php');
    }

    if (!$usuario['activo']) {
        $_SESSION['flash_error'] = 'Tu cuenta está suspendida. Contacta al administrador.';
        redirigir(BASE_URL . 'views/auth/login.php');
    }

    $_SESSION['usuario'] = [
        'id'     => (int)$usuario['id'],
        'nombre' => $usuario['nombre'],
        'email'  => $usuario['email'],
        'tipo'   => $usuario['tipo'],
    ];

    redirigirPorRol($usuario['tipo']);

} catch (PDOException $e) {
    $_SESSION['flash_error'] = 'Error del servidor. Intenta de nuevo.';
    redirigir(BASE_URL . 'views/auth/login.php');
}
