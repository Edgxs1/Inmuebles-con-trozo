<?php
require_once dirname(__DIR__) . '/config/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$current_page = basename($_SERVER['PHP_SELF']);
$usuario_actual = $_SESSION['usuario'] ?? null;
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inmuebles con Troso</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/styles.css">
</head>

<body>

    <header class="navbar">
        <div class="navbar__logo">
            <img src="<?php echo BASE_URL; ?>assets/img/logo60.jpeg" alt="Inmuebles con Troso" class="logo-img">
        </div>
        <nav class="navbar__links">
            <a href="<?php echo BASE_URL; ?>index.php"
                class="<?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">
                <i class="fa-solid fa-house"></i> INICIO
            </a>

            <a href="<?php echo BASE_URL; ?>views/public/catalogo.php"
                class="<?php echo ($current_page == 'catalogo.php') ? 'active' : ''; ?>">
                <i class="fa-solid fa-building"></i> CATÁLOGO
            </a>

            <a href="<?php echo BASE_URL; ?>views/public/nosotros.php"
                class="<?php echo ($current_page == 'nosotros.php') ? 'active' : ''; ?>">
                <i class="fa-solid fa-circle-info"></i> NOSOTROS
            </a>
            <a href="<?php echo BASE_URL; ?>views/public/contacto.php"
                class="<?php echo ($current_page == 'contacto.php') ? 'active' : ''; ?>">
                <i class="fa-solid fa-envelope"></i> CONTACTO
            </a>
        </nav>
        <div class="navbar__actions">
                    <?php if ($usuario_actual): ?>
                <a href="<?php echo BASE_URL; ?>views/auth/perfil.php"
                    style="color: #fff; margin-right: 15px; font-size: 0.9rem; text-decoration: none;">
                    <i class="fa-solid fa-user"></i> <?php echo htmlspecialchars($usuario_actual['nombre']); ?>
                </a>

                <?php if ($usuario_actual['tipo'] === 'Administrador'): ?>
                    <a href="<?php echo BASE_URL; ?>views/admin/dashboard.php"
                        class="btn btn--light-outline btn--small">PANEL</a>
                <?php elseif ($usuario_actual['tipo'] === 'Vendedor'): ?>
                    <a href="<?php echo BASE_URL; ?>views/vendedor/dashboard.php"
                        class="btn btn--light-outline btn--small">PANEL</a>
                <?php elseif ($usuario_actual['tipo'] === 'Comprador'): ?>
                    <a href="<?php echo BASE_URL; ?>views/comprador/dashboard.php"
                        class="btn btn--light-outline btn--small">MIS FAVORITOS</a>
                <?php endif; ?>

                <a href="<?php echo BASE_URL; ?>views/auth/cerrar-sesion.php" class="btn btn--primary btn--small">
                    <i class="fa-solid fa-right-from-bracket"></i> SALIR
                </a>
                    <?php else: ?>
                <a href="<?php echo BASE_URL; ?>views/auth/login.php" class="btn btn--outline">
                    <i class="fa-solid fa-arrow-right-to-bracket"></i> ACCEDER
                </a>
                <a href="<?php echo BASE_URL; ?>views/auth/registro.php" class="btn btn--primary">
                    <i class="fa-solid fa-user-plus"></i> REGISTRO
                </a>
            <?php endif; ?>
        </div>
    </header>