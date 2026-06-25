<?php
require_once dirname(__DIR__, 2) . '/includes/auth_check.php';
require_once dirname(__DIR__, 2) . '/includes/db_funciones.php';

iniciarSesion();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirigir(BASE_URL . 'views/public/catalogo.php');
}

$propiedadId = (int)($_POST['id'] ?? 0);
$redirect = trim($_POST['redirect'] ?? 'catalogo.php');

if ($propiedadId <= 0 || !estaLogueado()) {
    redirigir($redirect);
}

$usuarioId = $_SESSION['usuario']['id'];

if (esFavorito($usuarioId, $propiedadId)) {
    eliminarFavorito($usuarioId, $propiedadId);
} else {
    agregarFavorito($usuarioId, $propiedadId);
}

redirigir($redirect);
