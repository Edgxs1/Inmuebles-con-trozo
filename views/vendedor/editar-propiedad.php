<?php
require_once '../../config/data.php';

$id_propiedad = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if (!isset($propiedades[$id_propiedad])) {
    header("Location: dashboard.php");
    exit;
}

$prop = $propiedades[$id_propiedad];

include '../../includes/header.php';
?>

<main class="dashboard-page bg-light">
    <div class="container">

        <div class="back-navigation" style="margin-bottom: 30px;">
            <a href="<?php echo BASE_URL; ?>views/vendedor/dashboard.php" class="back-link">
                <i class="fa-solid fa-arrow-left"></i> VOLVER AL DASHBOARD
            </a>
        </div>

        <div class="property-form-container">
            <div class="property-form-header">
                <h1>EDITAR PROPIEDAD</h1>
                <p class="text-light">Modifica los datos de tu inmueble.</p>
            </div>

            <form id="formEditarPropiedad" action="#" method="POST" enctype="multipart/form-data" novalidate>

                <div class="form-grid">

                    <div class="form-group full-width">
                        <label for="titulo">Título de la publicación <span class="text-accent">*</span></label>
                        <input type="text" id="titulo" name="titulo" class="form-control"
                            value="<?php echo htmlspecialchars($prop['titulo']); ?>" required minlength="10">
                        <div class="invalid-feedback">El título debe tener al menos 10 caracteres.</div>
                    </div>

                    <div class="form-group">
                        <label for="precio">Precio (MXN) <span class="text-accent">*</span></label>
                        <input type="number" id="precio" name="precio" class="form-control"
                            value="<?php echo $prop['precio']; ?>" required min="10000">
                        <div class="invalid-feedback">Ingresa un precio válido.</div>
                    </div>

                    <div class="form-group">
                        <label for="tipo">Tipo de Inmueble <span class="text-accent">*</span></label>
                        <select id="tipo" name="tipo" class="form-control" required>
                            <option value="">Selecciona una opción</option>

                            <option value="Casa" <?php echo $prop['tipo'] == 'Casa' ? 'selected' : ''; ?>>Casa</option>
                            <option value="Departamento" <?php echo $prop['tipo'] == 'Departamento' ? 'selected' : ''; ?>>
                                Departamento</option>
                            <option value="Local Comercial" <?php echo $prop['tipo'] == 'Local Comercial' ? 'selected' : ''; ?>>Local Comercial</option>
                            <option value="Terreno" <?php echo $prop['tipo'] == 'Terreno' ? 'selected' : ''; ?>>Terreno
                            </option>
                        </select>
                        <div class="invalid-feedback">Selecciona el tipo de inmueble.</div>
                    </div>

                    <div class="form-group">
                        <label for="ubicacion">Ubicación Breve <span class="text-accent">*</span></label>
                        <input type="text" id="ubicacion" name="ubicacion" class="form-control"
                            value="<?php echo htmlspecialchars($prop['ubicacion']); ?>" required>
                        <div class="invalid-feedback">La ubicación es obligatoria.</div>
                    </div>

                    <div class="form-group">
                        <label for="estado">Estado <span class="text-accent">*</span></label>
                        <select id="estado" name="estado" class="form-control" required>
                            <option value="">Selecciona un estado</option>

                            <option value="Ciudad de México" <?php echo $prop['estado'] == 'Ciudad de México' ? 'selected' : ''; ?>>Ciudad de México</option>
                            <option value="Estado de México" <?php echo $prop['estado'] == 'Estado de México' ? 'selected' : ''; ?>>Estado de México</option>
                            <option value="Jalisco" <?php echo $prop['estado'] == 'Jalisco' ? 'selected' : ''; ?>>Jalisco
                            </option>
                            <option value="Quintana Roo" <?php echo $prop['estado'] == 'Quintana Roo' ? 'selected' : ''; ?>>Quintana Roo</option>
                            <option value="Nuevo León" <?php echo $prop['estado'] == 'Nuevo León' ? 'selected' : ''; ?>>
                                Nuevo León</option>
                        </select>
                        <div class="invalid-feedback">Selecciona el estado.</div>
                    </div>

                    <div class="form-group">
                        <label for="habitaciones">Habitaciones</label>
                        <input type="number" id="habitaciones" name="habitaciones" class="form-control" min="0"
                            value="<?php echo $prop['habitaciones']; ?>">
                    </div>

                    <div class="form-group">
                        <label for="banos">Baños</label>
                        <input type="number" id="banos" name="banos" class="form-control" min="0"
                            value="<?php echo $prop['banos']; ?>">
                    </div>

                    <div class="form-group full-width" style="max-width: 50%;">
                        <label for="area">Área (m²) <span class="text-accent">*</span></label>
                        <input type="number" id="area" name="area" class="form-control"
                            value="<?php echo $prop['area']; ?>" required min="1">
                        <div class="invalid-feedback">El área debe ser mayor a 0.</div>
                    </div>

                    <div class="form-group full-width">
                        <label for="descripcion">Descripción de la propiedad <span class="text-accent">*</span></label>
                        <textarea id="descripcion" name="descripcion" class="form-control" rows="5" required
                            minlength="30"><?php echo htmlspecialchars($prop['descripcion']); ?></textarea>
                        <div class="invalid-feedback">La descripción debe ser más detallada (mínimo 30 caracteres).
                        </div>
                    </div>

                    <div class="form-group full-width">
                        <label for="imagen">Fotografía Principal</label>
                        <input type="file" id="imagen" name="imagen" class="form-control file-input"
                            accept="image/png, image/jpeg, image/webp">
                        <small class="text-light" style="margin-top: 5px; display: block;">Deja en blanco para mantener
                            la imagen actual.</small>
                    </div>

                </div>

                <div class="form-actions mt-4">
                    <button type="submit" class="btn btn--primary"><i class="fa-solid fa-floppy-disk"></i> GUARDAR
                        CAMBIOS</button>
                    <a href="dashboard.php" class="btn btn--light-outline" style="margin-left: 10px;">CANCELAR</a>
                </div>

            </form>
        </div>
    </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('formEditarPropiedad');
        const inputs = form.querySelectorAll('.form-control[required]');

        form.addEventListener('submit', function (event) {
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
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            } else {
                event.preventDefault();
                alert('¡Cambios guardados correctamente! (Simulación Frontend)');
            }
        });

        inputs.forEach(input => {
            input.addEventListener('input', function () {
                if (this.checkValidity()) {
                    this.classList.remove('is-invalid');
                }
            });
        });
    });
</script>

<?php include '../../includes/footer.php'; ?>