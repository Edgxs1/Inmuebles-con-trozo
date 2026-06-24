<?php 
include '../../includes/header.php'; 

$usuario = [
    'nombre' => 'Carlos Vendedor',
    'email' => 'carlos@email.com',
    'tipo' => 'Vendedor',
    'miembro_desde' => 'Enero 2026'
];
?>

    <main class="dashboard-page">
        <div class="container">
            <div class="dashboard-header-top">
                <div>
                    <h1 class="dashboard-title">MI PERFIL</h1>
                    <p class="text-light">Administra tu información personal</p>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; align-items: start;">
                <div class="property-form-container" style="max-width: 100%;">
                    <div class="property-form-header">
                        <h2>DATOS PERSONALES</h2>
                    </div>

                    <form id="formPerfil" action="#" method="POST" novalidate>
                        <div class="form-grid">
                            <div class="form-group full-width">
                                <label for="nombre">Nombre completo</label>
                                <input type="text" id="nombre" name="nombre" class="form-control" value="<?php echo $usuario['nombre']; ?>" required minlength="3">
                                <div class="invalid-feedback">El nombre debe tener al menos 3 caracteres.</div>
                            </div>

                            <div class="form-group full-width">
                                <label for="email">Correo electrónico</label>
                                <input type="email" id="email" name="email" class="form-control" value="<?php echo $usuario['email']; ?>" required>
                                <div class="invalid-feedback">Ingresa un correo válido.</div>
                            </div>

                            <div class="form-group">
                                <label>Tipo de cuenta</label>
                                <input type="text" class="form-control" value="<?php echo $usuario['tipo']; ?>" disabled style="background-color: #f3f4f6;">
                            </div>

                            <div class="form-group">
                                <label>Miembro desde</label>
                                <input type="text" class="form-control" value="<?php echo $usuario['miembro_desde']; ?>" disabled style="background-color: #f3f4f6;">
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn--primary">
                                <i class="fa-solid fa-floppy-disk"></i> GUARDAR CAMBIOS
                            </button>
                        </div>
                    </form>
                </div>

                <div class="property-form-container" style="max-width: 100%;">
                    <div class="property-form-header">
                        <h2>CAMBIAR CONTRASEÑA</h2>
                    </div>

                    <form id="formPassword" action="#" method="POST" novalidate>
                        <div class="form-grid">
                            <div class="form-group full-width">
                                <label for="password_actual">Contraseña actual</label>
                                <input type="password" id="password_actual" name="password_actual" class="form-control" placeholder="••••••••" required>
                                <div class="invalid-feedback">La contraseña actual es obligatoria.</div>
                            </div>

                            <div class="form-group">
                                <label for="password_nueva">Nueva contraseña</label>
                                <input type="password" id="password_nueva" name="password_nueva" class="form-control" placeholder="••••••••" required minlength="8">
                                <div class="invalid-feedback">Mínimo 8 caracteres.</div>
                            </div>

                            <div class="form-group">
                                <label for="password_confirmar">Confirmar contraseña</label>
                                <input type="password" id="password_confirmar" name="password_confirmar" class="form-control" placeholder="••••••••" required>
                                <div class="invalid-feedback">Las contraseñas deben coincidir.</div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn--primary">
                                <i class="fa-solid fa-key"></i> ACTUALIZAR CONTRASEÑA
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="property-form-container" style="max-width: 100%; margin-top: 30px;">
                <div class="property-form-header">
                    <h2>ACCESO RÁPIDO</h2>
                </div>
                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                    <a href="<?php echo BASE_URL; ?>views/vendedor/dashboard.php" class="btn btn--light-outline">
                        <i class="fa-solid fa-chart-simple"></i> MI DASHBOARD
                    </a>
                    <a href="<?php echo BASE_URL; ?>views/vendedor/nueva-propiedad.php" class="btn btn--light-outline">
                        <i class="fa-solid fa-plus"></i> PUBLICAR PROPIEDAD
                    </a>
                    <a href="<?php echo BASE_URL; ?>views/public/catalogo.php" class="btn btn--light-outline">
                        <i class="fa-solid fa-building"></i> VER CATÁLOGO
                    </a>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const formPerfil = document.getElementById('formPerfil');
            const inputsPerfil = formPerfil.querySelectorAll('.form-control[required]');

            formPerfil.addEventListener('submit', function(event) {
                let isValid = true;
                inputsPerfil.forEach(input => {
                    if (!input.checkValidity()) {
                        input.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        input.classList.remove('is-invalid');
                    }
                });
                if (!isValid) {
                    event.preventDefault();
                } else {
                    event.preventDefault();
                    alert('Datos actualizados correctamente.');
                }
            });

            inputsPerfil.forEach(input => {
                input.addEventListener('input', function() {
                    if (this.checkValidity()) {
                        this.classList.remove('is-invalid');
                    }
                });
            });

            const formPassword = document.getElementById('formPassword');
            const passwordNueva = document.getElementById('password_nueva');
            const passwordConfirmar = document.getElementById('password_confirmar');

            formPassword.addEventListener('submit', function(event) {
                let isValid = true;
                const inputs = formPassword.querySelectorAll('.form-control[required]');
                inputs.forEach(input => {
                    if (!input.checkValidity()) {
                        input.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        input.classList.remove('is-invalid');
                    }
                });
                if (passwordNueva.value !== passwordConfirmar.value) {
                    passwordConfirmar.classList.add('is-invalid');
                    isValid = false;
                }
                if (!isValid) {
                    event.preventDefault();
                } else {
                    event.preventDefault();
                    alert('Contraseña actualizada correctamente.');
                    formPassword.reset();
                }
            });

            [passwordNueva, passwordConfirmar].forEach(input => {
                input.addEventListener('input', function() {
                    if (this.checkValidity()) {
                        this.classList.remove('is-invalid');
                    }
                });
            });
        });
    </script>

<?php include '../../includes/footer.php'; ?>
