<?php
require_once dirname(__DIR__, 2) . '/includes/auth_check.php';
require_once dirname(__DIR__, 2) . '/includes/db_funciones.php';

iniciarSesion();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirigir(BASE_URL . 'views/public/catalogo.php');
}

$propiedadId = (int)($_POST['propiedad_id'] ?? 0);
$calificacion = (int)($_POST['calificacion'] ?? 0);
$comentario = trim($_POST['comentario'] ?? '');

if ($propiedadId <= 0 || !estaLogueado()) {
    redirigir(BASE_URL . 'views/public/catalogo.php');
}

if ($calificacion < 1 || $calificacion > 5) {
    $_SESSION['flash_error'] = 'La calificación debe ser entre 1 y 5 estrellas.';
    redirigir(BASE_URL . 'views/public/propiedad.php?id=' . $propiedadId);
}

if (empty($comentario)) {
    $_SESSION['flash_error'] = 'El comentario es obligatorio.';
    redirigir(BASE_URL . 'views/public/propiedad.php?id=' . $propiedadId);
}

if (strlen($comentario) < 10) {
    $_SESSION['flash_error'] = 'El comentario debe tener al menos 10 caracteres.';
    redirigir(BASE_URL . 'views/public/propiedad.php?id=' . $propiedadId);
}

try {
    agregarResena($propiedadId, $_SESSION['usuario']['id'], $calificacion, $comentario);
    $_SESSION['flash_success'] = 'Reseña publicada correctamente.';
} catch (PDOException $e) {
    $_SESSION['flash_error'] = 'Ya has reseñado esta propiedad.';
}

redirigir(BASE_URL . 'views/public/propiedad.php?id=' . $propiedadId);
