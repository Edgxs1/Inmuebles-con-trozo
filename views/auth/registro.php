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
                <h1>REGISTRO</h1>
                <p class="text-light">Crea tu cuenta en Inmuebles con Troso</p>
            </div>

            <?php if ($flash_error): ?>
                <div class="alert alert--error"><?php echo htmlspecialchars($flash_error); ?></div>
            <?php endif; ?>
            <?php if ($flash_success): ?>
                <div class="alert alert--success"><?php echo htmlspecialchars($flash_success); ?></div>
            <?php endif; ?>

            <form id="formRegistro" action="procesar-registro.php" method="POST" novalidate>
                
                <div class="form-group">
                    <label for="nombre">Nombre Completo</label>
                    <input type="text" id="nombre" name="nombre" placeholder="Tu nombre" class="form-control" required minlength="3">
                    <div class="invalid-feedback">Por favor, ingresa tu nombre completo (mínimo 3 caracteres).</div>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="tu@email.com" class="form-control" required>
                    <div class="invalid-feedback">Por favor, ingresa un correo electrónico válido.</div>
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" placeholder="••••••••" class="form-control" required minlength="8">
                    <div class="invalid-feedback">La contraseña debe tener al menos 8 caracteres.</div>
                </div>

                <div class="form-group">
                    <label>Tipo de cuenta</label>
                    <div class="radio-options">
                        <label class="radio-label">
                            <input type="radio" name="tipo_cuenta" value="Comprador" required>
                            <span><strong>Comprador</strong> - Busco propiedades para comprar o rentar</span>
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="tipo_cuenta" value="Vendedor" required>
                            <span><strong>Vendedor</strong> - Quiero publicar mis propiedades</span>
                        </label>
                    </div>
                    <div class="invalid-feedback" id="radio-error" style="display: none;">Debes seleccionar un tipo de cuenta.</div>
                </div>

                <button type="submit" class="btn btn--primary w-100 mt-4">
                    <i class="fa-solid fa-user-plus"></i> CREAR CUENTA
                </button>
            </form>

            <div class="auth-card__footer text-center">
                <p>¿Ya tienes cuenta? <a href="login.php" class="text-accent font-bold">Inicia sesión aquí</a></p>
            </div>

        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('formRegistro');
            const inputs = form.querySelectorAll('.form-control');
            const radios = form.querySelectorAll('input[type="radio"]');
            const radioError = document.getElementById('radio-error');

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

                let isRadioSelected = false;
                radios.forEach(radio => {
                    if (radio.checked) {
                        isRadioSelected = true;
                    }
                });

                if (!isRadioSelected) {
                    radioError.style.display = 'block';
                    isValid = false;
                } else {
                    radioError.style.display = 'none';
                }

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

            radios.forEach(radio => {
                radio.addEventListener('change', function() {
                    radioError.style.display = 'none';
                });
            });
        });
    </script>

<?php 
include '../../includes/footer.php'; 
?>