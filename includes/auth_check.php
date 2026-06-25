<?php
require_once dirname(__DIR__) . '/config/config.php';

function iniciarSesion(): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function estaLogueado(): bool {
    iniciarSesion();
    return isset($_SESSION['usuario']);
}

function requireAuth(): void {
    iniciarSesion();
    if (!estaLogueado()) {
        $_SESSION['flash_error'] = 'Debes iniciar sesión para acceder a esta página.';
        redirigir(BASE_URL . 'views/auth/login.php');
    }
}

function redirigir(string $url): never {
    session_write_close();
    header('Location: ' . $url);
    exit;
}

function requireRol(string ...$roles): void {
    requireAuth();
    if (!in_array($_SESSION['usuario']['tipo'], $roles)) {
        redirigirPorRol($_SESSION['usuario']['tipo']);
    }
}

function redirigirPorRol(string $tipo): never {
    session_write_close();
    switch ($tipo) {
        case 'Administrador':
            header('Location: ' . BASE_URL . 'views/admin/dashboard.php');
            break;
        case 'Vendedor':
            header('Location: ' . BASE_URL . 'views/vendedor/dashboard.php');
            break;
        default:
            header('Location: ' . BASE_URL . 'index.php');
            break;
    }
    exit;
}
