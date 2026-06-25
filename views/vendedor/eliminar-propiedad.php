<?php
require_once dirname(__DIR__, 2) . '/includes/auth_check.php';
require_once dirname(__DIR__, 2) . '/includes/db_funciones.php';

requireRol('Vendedor', 'Administrador');

$id = (int)($_GET['id'] ?? 0);
$prop = obtenerPropiedad($id);

if (!$prop) {
    $_SESSION['flash_error'] = 'Propiedad no encontrada.';
    redirigir('dashboard.php');
}

if ($_SESSION['usuario']['tipo'] !== 'Administrador' && (int)$prop['vendedor_id'] !== $_SESSION['usuario']['id']) {
    $_SESSION['flash_error'] = 'No tienes permiso para eliminar esta propiedad.';
    redirigir('dashboard.php');
}

try {
    if ($prop['imagen'] && strpos($prop['imagen'], 'http') !== 0) {
        $ruta = dirname(__DIR__, 2) . '/' . $prop['imagen'];
        if (file_exists($ruta)) {
            unlink($ruta);
        }
    }
    eliminarPropiedad($id);
    $_SESSION['flash_success'] = 'Propiedad eliminada correctamente.';
} catch (PDOException $e) {
    $_SESSION['flash_error'] = 'Error del servidor. Intenta de nuevo.';
}

redirigir('dashboard.php');
