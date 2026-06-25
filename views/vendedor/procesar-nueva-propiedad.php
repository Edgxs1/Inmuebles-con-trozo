<?php
require_once dirname(__DIR__, 2) . '/includes/auth_check.php';
require_once dirname(__DIR__, 2) . '/includes/db_funciones.php';

requireRol('Vendedor');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirigir('nueva-propiedad.php');
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
    redirigir('nueva-propiedad.php');
}

if (strlen($titulo) < 10) {
    $_SESSION['flash_error'] = 'El título debe tener al menos 10 caracteres.';
    redirigir('nueva-propiedad.php');
}

if ((float)$precio < 10000) {
    $_SESSION['flash_error'] = 'El precio debe ser al menos $10,000.';
    redirigir('nueva-propiedad.php');
}

$imagen = '';
if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
    try {
        $imagen = subirImagen($_FILES['imagen']);
    } catch (\RuntimeException $e) {
        $_SESSION['flash_error'] = $e->getMessage();
        redirigir('nueva-propiedad.php');
    }
}

try {
    crearPropiedad([
        'titulo'            => $titulo,
        'precio'            => (float)$precio,
        'ubicacion'         => $ubicacion,
        'estado'            => $estado,
        'habitaciones'      => $habitaciones,
        'banos'             => $banos,
        'area'              => (float)$area,
        'tipo'              => $tipo,
        'descripcion'       => $descripcion,
        'imagen'            => $imagen,
        'vendedor_id'       => $_SESSION['usuario']['id'],
        'fecha_publicacion' => date('Y-m-d'),
        'estatus'           => 'En Revisión',
    ]);

    $_SESSION['flash_success'] = 'Propiedad enviada a revisión correctamente.';
} catch (PDOException $e) {
    $_SESSION['flash_error'] = 'Error del servidor: ' . $e->getMessage();
}

redirigir('dashboard.php');