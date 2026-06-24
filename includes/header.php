<?php
require_once dirname(__DIR__) . '/config/config.php';

$current_page = basename($_SERVER['PHP_SELF']);
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
            <a href="<?php echo BASE_URL; ?>index.php" class="<?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">
                <i class="fa-solid fa-house"></i> INICIO
            </a>
            
            <a href="<?php echo BASE_URL; ?>views/public/catalogo.php" class="<?php echo ($current_page == 'catalogo.php') ? 'active' : ''; ?>">
                <i class="fa-solid fa-building"></i> CATÁLOGO
            </a>
            
            <a href="<?php echo BASE_URL; ?>views/public/nosotros.php" class="<?php echo ($current_page == 'nosotros.php') ? 'active' : ''; ?>">
                <i class="fa-solid fa-circle-info"></i> NOSOTROS
            </a>
            <a href="<?php echo BASE_URL; ?>views/public/contacto.php" class="<?php echo ($current_page == 'contacto.php') ? 'active' : ''; ?>">
                <i class="fa-solid fa-envelope"></i> CONTACTO
            </a>
        </nav>
        <div class="navbar__actions">
            <a href="<?php echo BASE_URL; ?>views/auth/login.php" class="btn btn--outline">
                <i class="fa-solid fa-arrow-right-to-bracket"></i> ACCEDER
            </a>
            <a href="<?php echo BASE_URL; ?>views/auth/registro.php" class="btn btn--primary">
                <i class="fa-solid fa-user-plus"></i> REGISTRO
            </a>
        </div>
    </header>