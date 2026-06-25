<?php
require_once dirname(__DIR__, 2) . '/includes/auth_check.php';
require_once dirname(__DIR__, 2) . '/config/db.php';

requireAuth();

$usuario = $_SESSION['usuario'];
$flash_error   = $_SESSION['flash_error'] ?? null;
$flash_success = $_SESSION['flash_success'] ?? null;
unset($_SESSION['flash_error'], $_SESSION['flash_success']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    iniciarSesion();

    if ($_POST['accion'] === 'actualizar_perfil') {
        $nombre = trim($_POST['nombre'] ?? '');
        $email  = trim($_POST['email'] ?? '');

        if (strlen($nombre) < 3) {
            $_SESSION['flash_error'] = 'El nombre debe tener al menos 3 caracteres.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['flash_error'] = 'El correo no es válido.';
        } else {
            try {
                $pdo = getDB();
                $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = :email AND id != :id LIMIT 1");
                $stmt->execute(['email' => $email, 'id' => $usuario['id']]);
                if ($stmt->fetch()) {
                    $_SESSION['flash_error'] = 'Este correo ya está en uso por otro usuario.';
                } else {
                    $stmt = $pdo->prepare("UPDATE usuarios SET nombre = :nombre, email = :email WHERE id = :id");
                    $stmt->execute(['nombre' => $nombre, 'email' => $email, 'id' => $usuario['id']]);
                    $_SESSION['usuario']['nombre'] = $nombre;
                    $_SESSION['usuario']['email'] = $email;
                    $_SESSION['flash_success'] = 'Datos actualizados correctamente.';
                }
            } catch (PDOException $e) {
                $_SESSION['flash_error'] = 'Error del servidor. Intenta de nuevo.';
            }
        }
        redirigir('perfil.php');
    }

    if ($_POST['accion'] === 'cambiar_password') {
        $actual    = $_POST['password_actual'] ?? '';
        $nueva     = $_POST['password_nueva'] ?? '';
        $confirmar = $_POST['password_confirmar'] ?? '';

        if (empty($actual) || empty($nueva) || empty($confirmar)) {
            $_SESSION['flash_error'] = 'Todos los campos de contraseña son obligatorios.';
        } elseif (strlen($nueva) < 8) {
            $_SESSION['flash_error'] = 'La nueva contraseña debe tener al menos 8 caracteres.';
        } elseif ($nueva !== $confirmar) {
            $_SESSION['flash_error'] = 'Las contraseñas no coinciden.';
        } else {
            try {
                $pdo = getDB();
                $stmt = $pdo->prepare("SELECT password FROM usuarios WHERE id = :id LIMIT 1");
                $stmt->execute(['id' => $usuario['id']]);
                $row = $stmt->fetch();

                if (!password_verify($actual, $row['password'])) {
                    $_SESSION['flash_error'] = 'La contraseña actual no es correcta.';
                } else {
                    $hash = password_hash($nueva, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("UPDATE usuarios SET password = :hash WHERE id = :id");
                    $stmt->execute(['hash' => $hash, 'id' => $usuario['id']]);
                    $_SESSION['flash_success'] = 'Contraseña actualizada correctamente.';
                }
            } catch (PDOException $e) {
                $_SESSION['flash_error'] = 'Error del servidor. Intenta de nuevo.';
            }
        }
        redirigir('perfil.php');
    }
}

try {
    $pdo = getDB();
    $stmt = $pdo->prepare("SELECT fecha_registro FROM usuarios WHERE id = :id LIMIT 1");
    $stmt->execute(['id' => $usuario['id']]);
    $row = $stmt->fetch();
    $miembro_desde = $row ? date('d/m/Y', strtotime($row['fecha_registro'])) : '—';
} catch (PDOException $e) {
    $miembro_desde = '—';
}

$tipos = ['admin' => 'Administrador', 'vendedor' => 'Vendedor', 'comprador' => 'Comprador'];
$tipo_label = $tipos[$usuario['tipo']] ?? ucfirst($usuario['tipo']);

include '../../includes/header.php';
?>

    <main class="dashboard-page">
        <div class="container">
            <div class="dashboard-header-top">
                <div>
                    <h1 class="dashboard-title">MI PERFIL</h1>
                    <p class="text-light">Administra tu información personal</p>
                </div>
            </div>

            <?php if ($flash_error): ?>
                <div class="alert alert--error"><?php echo htmlspecialchars($flash_error); ?></div>
            <?php endif; ?>
            <?php if ($flash_success): ?>
                <div class="alert alert--success"><?php echo htmlspecialchars($flash_success); ?></div>
            <?php endif; ?>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; align-items: start;">
                <div class="property-form-container" style="max-width: 100%;">
                    <div class="property-form-header">
                        <h2>DATOS PERSONALES</h2>
                    </div>

                    <form id="formPerfil" action="perfil.php" method="POST" novalidate>
                        <input type="hidden" name="accion" value="actualizar_perfil">
                        <div class="form-grid">
                            <div class="form-group full-width">
                                <label for="nombre">Nombre completo</label>
                                <input type="text" id="nombre" name="nombre" class="form-control" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required minlength="3">
                                <div class="invalid-feedback">El nombre debe tener al menos 3 caracteres.</div>
                            </div>

                            <div class="form-group full-width">
                                <label for="email">Correo electrónico</label>
                                <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
                                <div class="invalid-feedback">Ingresa un correo válido.</div>
                            </div>

                            <div class="form-group">
                                <label>Tipo de cuenta</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($tipo_label); ?>" disabled style="background-color: #f3f4f6;">
                            </div>

                            <div class="form-group">
                                <label>Miembro desde</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($miembro_desde); ?>" disabled style="background-color: #f3f4f6;">
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

                    <form id="formPassword" action="perfil.php" method="POST" novalidate>
                        <input type="hidden" name="accion" value="cambiar_password">
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
                    <?php if ($usuario['tipo'] === 'Vendedor' || $usuario['tipo'] === 'Administrador'): ?>
                        <a href="<?php echo BASE_URL; ?>views/vendedor/dashboard.php" class="btn btn--light-outline">
                            <i class="fa-solid fa-chart-simple"></i> MI DASHBOARD
                        </a>
                        <a href="<?php echo BASE_URL; ?>views/vendedor/nueva-propiedad.php" class="btn btn--light-outline">
                            <i class="fa-solid fa-plus"></i> PUBLICAR PROPIEDAD
                        </a>
                    <?php endif; ?>
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