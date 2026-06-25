<?php 
require_once '../../config/config.php';
include '../../includes/header.php';

$flash_success = $_SESSION['flash_success'] ?? null;
$flash_error   = $_SESSION['flash_error'] ?? null;
unset($_SESSION['flash_success'], $_SESSION['flash_error']);

$usuario_sesion = $_SESSION['usuario'] ?? null;
$nombre_valor = $usuario_sesion['nombre'] ?? '';
$email_valor = $usuario_sesion['email'] ?? '';
$es_miembro = !empty($usuario_sesion);
?>

    <main class="about-page">
        <div class="container">
            <div class="section-title about-header">
                <h1>CONTACTO</h1>
                <h2 class="text-accent">¡VA CON TODO!</h2>
            </div>

            <div class="about-content">
                <div class="about-block">
                    <p style="text-align: center; margin-bottom: 40px;">
                        Estamos listos para ayudarte. Escríbenos y te responderemos a la brevedad.
                    </p>
                </div>

                <?php if ($flash_success): ?>
                    <div class="alert alert--success"><?php echo htmlspecialchars($flash_success); ?></div>
                <?php endif; ?>
                <?php if ($flash_error): ?>
                    <div class="alert alert--error"><?php echo htmlspecialchars($flash_error); ?></div>
                <?php endif; ?>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; align-items: start;">
                    <div>
                        <form id="formContacto" action="procesar-contacto.php" method="POST" novalidate>
                            <div class="form-group">
                                <label for="nombre">Nombre completo <span class="text-accent">*</span></label>
                                <input type="text" id="nombre" name="nombre" class="form-control" 
                                       placeholder="Tu nombre" required minlength="3" 
                                       value="<?php echo htmlspecialchars($nombre_valor); ?>">
                                <div class="invalid-feedback">Ingresa tu nombre (mín. 3 caracteres).</div>
                            </div>

                            <div class="form-group">
                                <label for="email">Correo electrónico <span class="text-accent">*</span></label>
                                <input type="email" id="email" name="email" class="form-control" 
                                       placeholder="tu@email.com" required 
                                       value="<?php echo htmlspecialchars($email_valor); ?>"
                                       <?php echo $es_miembro ? 'readonly' : ''; ?>>
                                <div class="invalid-feedback">Ingresa un correo válido.</div>
                            </div>

                            <div class="form-group">
                                <label for="telefono">Teléfono</label>
                                <input type="tel" id="telefono" name="telefono" class="form-control" placeholder="+52 55 1234 5678">
                            </div>

                            <div class="form-group">
                                <label for="asunto">Asunto <span class="text-accent">*</span></label>
                                <select id="asunto" name="asunto" class="form-control" required>
                                    <option value="">Selecciona un asunto</option>
                                    <option value="compra">Quiero comprar</option>
                                    <option value="venta">Quiero vender</option>
                                    <option value="renta">Quiero rentar</option>
                                    <option value="informacion">Información general</option>
                                    <option value="otro">Otro</option>
                                </select>
                                <div class="invalid-feedback">Selecciona un asunto.</div>
                            </div>

                            <div class="form-group">
                                <label for="mensaje">Mensaje <span class="text-accent">*</span></label>
                                <textarea id="mensaje" name="mensaje" class="form-control" rows="5" placeholder="Escribe tu mensaje..." required minlength="10"></textarea>
                                <div class="invalid-feedback">El mensaje debe tener al menos 10 caracteres.</div>
                            </div>

                            <button type="submit" class="btn btn--primary w-100">
                                <i class="fa-solid fa-paper-plane"></i> ENVIAR MENSAJE
                            </button>
                        </form>
                    </div>

                    <div>
                        <div class="action-card" style="margin-bottom: 25px;">
                            <h3 style="font-size: 1.2rem; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                                <i class="fa-solid fa-circle-info text-accent"></i> Información de contacto
                            </h3>
                            <p style="color: var(--text-light); margin-bottom: 5px;"><i class="fa-solid fa-phone text-accent" style="width: 20px;"></i> +52 55 1234 5678</p>
                            <p style="color: var(--text-light); margin-bottom: 5px;"><i class="fa-solid fa-envelope text-accent" style="width: 20px;"></i> contacto@inmueblescontreso.mx</p>
                            <p style="color: var(--text-light);"><i class="fa-solid fa-location-dot text-accent" style="width: 20px;"></i> Cobertura en todo México</p>
                        </div>

                        <div class="action-card">
                            <h3 style="font-size: 1.2rem; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                                <i class="fa-regular fa-clock text-accent"></i> Horarios de atención
                            </h3>
                            <p style="color: var(--text-light); margin-bottom: 8px;"><strong>Lun - Vie:</strong> 9:00 AM - 7:00 PM</p>
                            <p style="color: var(--text-light); margin-bottom: 8px;"><strong>Sábados:</strong> 10:00 AM - 2:00 PM</p>
                            <p style="color: var(--text-light);"><strong>Domingos:</strong> Cerrado</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('formContacto');
            const inputs = form.querySelectorAll('.form-control[required]');

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
                    const firstError = document.querySelector('.is-invalid');
                    if(firstError) firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            });

            inputs.forEach(input => {
                input.addEventListener('input', function() {
                    if (this.checkValidity()) this.classList.remove('is-invalid');
                });
            });
        });
    </script>

<?php include '../../includes/footer.php'; ?>