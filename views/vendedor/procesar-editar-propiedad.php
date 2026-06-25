<?php
require_once dirname(__DIR__, 2) . '/includes/auth_check.php';
require_once dirname(__DIR__, 2) . '/includes/db_funciones.php';

requireRol('Vendedor');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirigir('dashboard.php');
}

$id = (int)($_POST['id'] ?? 0);
$prop = obtenerPropiedad($id);

if (!$prop) {
    $_SESSION['flash_error'] = 'Propiedad no encontrada.';
    redirigir('dashboard.php');
}

if ($_SESSION['usuario']['tipo'] !== 'Administrador' && (int)$prop['vendedor_id'] !== $_SESSION['usuario']['id']) {
    $_SESSION['flash_error'] = 'No tienes permiso para editar esta propiedad.';
    redirigir('dashboard.php');
}

$titulo       = trim($_POST['titulo'] ?? '');
$precio       = $_POST['precio'] ?? '';
$tipo         = $_POST['tipo'] ?? '';
$ubicacion    = trim($_POST['ubicacion'] ?? '');
$estado       = $_POST['estado'] ?? '';
$habitaciones = (int)($_POST['habitaciones'] ?? 0);
$banos        = (int)($_POST['banos'] ?? 0);
$area         = $_POST['area'] ?? '';
$descripcion  = trim($_POST['descripcion'] ?? '');

if (empty($titulo) || empty($precio) || empty($tipo) || empty($ubicacion) || empty($estado) || empty($area) || empty($descripcion)) {
    $_SESSION['flash_error'] = 'Todos los campos obligatorios deben estar llenos.';
    redirigir('editar-propiedad.php?id=' . $id);
}

if (strlen($titulo) < 10) {
    $_SESSION['flash_error'] = 'El título debe tener al menos 10 caracteres.';
    redirigir('editar-propiedad.php?id=' . $id);
}

$datos = [
    'titulo'       => $titulo,
    'precio'       => (float)$precio,
    'tipo'         => $tipo,
    'ubicacion'    => $ubicacion,
    'estado'       => $estado,
    'habitaciones' => $habitaciones,
    'banos'        => $banos,
    'area'         => (float)$area,
    'descripcion'  => $descripcion,
];

if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
    try {
        $datos['imagen'] = subirImagen($_FILES['imagen']);
    } catch (\RuntimeException $e) {
        $_SESSION['flash_error'] = $e->getMessage();
        redirigir('editar-propiedad.php?id=' . $id);
    }
}

try {
    actualizarPropiedad($id, $datos);
    $_SESSION['flash_success'] = 'Propiedad actualizada correctamente.';
} catch (PDOException $e) {
    $_SESSION['flash_error'] = 'Error del servidor. Intenta de nuevo.';
}

redirigir('dashboard.php');
