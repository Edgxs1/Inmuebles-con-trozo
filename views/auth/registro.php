<?php 
include '../../includes/header.php'; 
?>

    <main class="auth-page">
        <div class="auth-card">
            
            <div class="auth-card__header text-center">
                <h1>REGISTRO</h1>
                <p class="text-light">Crea tu cuenta en Inmuebles con Troso</p>
            </div>

            <form id="formRegistro" action="#" method="POST" novalidate>
                
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
                            <input type="radio" name="tipo_cuenta" value="comprador" required>
                            <span><strong>Comprador</strong> - Busco propiedades para comprar o rentar</span>
                        </label>
                        <label class="radio-label">
                            <input type="radio" name="tipo_cuenta" value="vendedor" required>
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

                // 1. Validar inputs de texto, email y password
                inputs.forEach(input => {
                    if (!input.checkValidity()) {
                        input.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        input.classList.remove('is-invalid');
                    }
                });

                // 2. Validar radio buttons (Tipo de cuenta)
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

                // 3. Si algo es inválido, detenemos el envío
                if (!isValid) {
                    event.preventDefault(); // Evita que la página recargue
                } else {
                    event.preventDefault(); 
                    alert("¡Validación exitosa! Listo para enviar a PHP.");
                }
            });

            // Limpiar el error visual cuando el usuario empiece a escribir
            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    if (this.checkValidity()) {
                        this.classList.remove('is-invalid');
                    }
                });
            });

            // Limpiar el error de los radios al seleccionar uno
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