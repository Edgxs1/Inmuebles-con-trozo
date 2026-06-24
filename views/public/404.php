<?php 
include '../../includes/header.php'; 
?>

    <main class="auth-page">
        <div class="auth-card text-center" style="max-width: 450px;">
            <div style="font-size: 4rem; color: var(--primary-color); margin-bottom: 20px;">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <h1 style="font-size: 5rem; font-weight: 800; color: var(--text-dark); line-height: 1;">404</h1>
            <p style="font-size: 1.3rem; color: var(--text-light); margin: 15px 0 30px 0;">
                Página no encontrada
            </p>
            <p style="color: var(--text-light); margin-bottom: 30px;">
                La página que buscas no existe o ha sido movida.
            </p>
            <a href="<?php echo BASE_URL; ?>index.php" class="btn btn--primary">
                <i class="fa-solid fa-house"></i> VOLVER AL INICIO
            </a>
        </div>
    </main>

<?php include '../../includes/footer.php'; ?>
