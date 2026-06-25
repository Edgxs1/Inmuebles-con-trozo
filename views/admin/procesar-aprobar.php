<?php
require_once dirname(__DIR__, 2) . '/includes/auth_check.php';
require_once dirname(__DIR__, 2) . '/includes/db_funciones.php';

requireRol('Administrador');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirigir('dashboard.php');
}

$id     = (int)($_POST['id'] ?? 0);
$accion = $_POST['accion'] ?? ''; 

$prop = obtenerPropiedad($id);
if (!$prop) {
    $_SESSION['flash_error'] = 'Propiedad no encontrada.';
    redirigir('dashboard.php');
}

try {
    if ($accion === 'aprobar') {
        actualizarPropiedad($id, ['estatus' => 'Disponible']);
        $_SESSION['flash_success'] = 'Propiedad aprobada y publicada.';
        
    } elseif ($accion === 'pausar') {
        actualizarPropiedad($id, ['estatus' => 'Pausado']);
        $_SESSION['flash_success'] = 'Propiedad pausada correctamente.';
        
    } elseif ($accion === 'rechazar') {
        eliminarPropiedad($id);
        $_SESSION['flash_success'] = 'Propiedad rechazada y eliminada.';
        
    } else {
        $_SESSION['flash_error'] = 'Acción no válida.';
    }
} catch (PDOException $e) {
    $_SESSION['flash_error'] = 'Error SQL: ' . $e->getMessage();
}

redirigir('dashboard.php');