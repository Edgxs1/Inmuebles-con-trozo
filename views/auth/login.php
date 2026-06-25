<?php
require_once dirname(__DIR__, 2) . '/includes/auth_check.php';

iniciarSesion();

if (estaLogueado()) {
    redirigirPorRol($_SESSION['usuario']['tipo']);
}

$flash_error   = $_SESSION['flash_error'] ?? null;
$flash_success = $_SESSION['flash_success'] ?? null;
unset($_SESSION['flash_error'], $_SESSION['flash_success']);

include '../../includes/header.php';
?>

    <main class="auth-page">
        <div class="auth-card">
            
            <div class="auth-card__header text-center">
                <h1>INICIAR SESIÓN</h1>
                <p class="text-light">Accede a tu panel de control</p>
            </div>

            <?php if ($flash_error): ?>
                <div class="alert alert--error"><?php echo htmlspecialchars($flash_error); ?></div>
            <?php endif; ?>
            <?php if ($flash_success): ?>
                <div class="alert alert--success"><?php echo htmlspecialchars($flash_success); ?></div>
            <?php endif; ?>

            <form id="formLogin" action="procesar-login.php" method="POST" novalidate>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="tu@email.com" class="form-control" required>
                    <div class="invalid-feedback">Por favor, ingresa un correo electrónico válido.</div>
                </div>

                <div class="form-group">
                    <div class="flex-between mb-2">
                        <label for="password" style="margin-bottom: 0;">Contraseña</label>
                        <a href="#" class="text-accent text-sm">¿Olvidaste tu contraseña?</a>
                    </div>
                    <input type="password" id="password" name="password" placeholder="••••••••" class="form-control" required>
                    <div class="invalid-feedback">La contraseña es obligatoria.</div>
                </div>

                <button type="submit" class="btn btn--primary w-100 mt-4">
                    <i class="fa-solid fa-arrow-right-to-bracket"></i> ACCEDER
                </button>
            </form>

            <div class="auth-card__footer text-center">
                <p>¿No tienes cuenta? <a href="registro.php" class="text-accent font-bold">Regístrate aquí</a></p>
            </div>

        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('formLogin');
            const inputs = form.querySelectorAll('.form-control');

            form.addEventListener('submit', function(event) {
                let isValid = true;

                inputs.forEach(input => {
                    if (!input.checkValidity()) {
                        input.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        input.classList.remove('is-invalid');
                    }
                });

                if (!isValid) {
                    event.preventDefault();
                }
            });

            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    if (this.checkValidity()) {
                        this.classList.remove('is-invalid');
                    }
                });
            });
        });
    </script>

<?php 
include '../../includes/footer.php'; 
?>