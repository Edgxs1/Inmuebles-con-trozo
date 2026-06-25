<?php
require_once dirname(__DIR__, 2) . '/includes/auth_check.php';
require_once dirname(__DIR__, 2) . '/includes/db_funciones.php';

requireRol('Administrador');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirigir('usuarios.php');
}

$id    = (int)($_POST['id'] ?? 0);
$accion = $_POST['accion'] ?? '';

$usuario = obtenerUsuario($id);
if (!$usuario) {
    $_SESSION['flash_error'] = 'Usuario no encontrado.';
    redirigir('usuarios.php');
}

try {
    if ($accion === 'toggle_activo') {
        $nuevo_estado = $usuario['activo'] ? 0 : 1;
        actualizarUsuario($id, ['activo' => $nuevo_estado]);
        $_SESSION['flash_success'] = 'Estado del usuario actualizado.';
    } elseif ($accion === 'cambiar_tipo') {
        $nuevo_tipo = $_POST['tipo'] ?? '';
        if (!in_array($nuevo_tipo, ['Administrador', 'Vendedor', 'Comprador'])) {
            $_SESSION['flash_error'] = 'Tipo de usuario no válido.';
        } else {
            actualizarUsuario($id, ['tipo' => $nuevo_tipo]);
            $_SESSION['flash_success'] = 'Tipo de usuario actualizado.';
        }
    } else {
        $_SESSION['flash_error'] = 'Acción no válida.';
    }
} catch (PDOException $e) {
    $_SESSION['flash_error'] = 'Error del servidor. Intenta de nuevo.';
}

redirigir('usuarios.php');
