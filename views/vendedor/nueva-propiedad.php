<?php 
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
                    <h1>PUBLICAR NUEVA PROPIEDAD</h1>
                    <p class="text-light">Completa los detalles de tu inmueble. Una vez publicado, pasará a revisión por un administrador.</p>
                </div>

                <form id="formPropiedad" action="#" method="POST" enctype="multipart/form-data" novalidate>
                    
                    <div class="form-grid">
                        
                        <div class="form-group full-width">
                            <label for="titulo">Título de la publicación <span class="text-accent">*</span></label>
                            <input type="text" id="titulo" name="titulo" class="form-control" placeholder="Ej. Hermosa Casa Moderna en Polanco" required minlength="10">
                            <div class="invalid-feedback">El título debe tener al menos 10 caracteres.</div>
                        </div>

                        <div class="form-group">
                            <label for="precio">Precio (MXN) <span class="text-accent">*</span></label>
                            <input type="number" id="precio" name="precio" class="form-control" placeholder="Ej. 2500000" required min="10000">
                            <div class="invalid-feedback">Ingresa un precio válido.</div>
                        </div>

                        <div class="form-group">
                            <label for="tipo">Tipo de Inmueble <span class="text-accent">*</span></label>
                            <select id="tipo" name="tipo" class="form-control" required>
                                <option value="">Selecciona una opción</option>
                                <option value="Casa">Casa</option>
                                <option value="Departamento">Departamento</option>
                                <option value="Local Comercial">Local Comercial</option>
                                <option value="Terreno">Terreno</option>
                            </select>
                            <div class="invalid-feedback">Selecciona el tipo de inmueble.</div>
                        </div>

                        <div class="form-group">
                            <label for="ubicacion">Ubicación Breve <span class="text-accent">*</span></label>
                            <input type="text" id="ubicacion" name="ubicacion" class="form-control" placeholder="Ej. Polanco, Ciudad de México" required>
                            <div class="invalid-feedback">La ubicación es obligatoria.</div>
                        </div>

                        <div class="form-group">
                            <label for="estado">Estado <span class="text-accent">*</span></label>
                            <select id="estado" name="estado" class="form-control" required>
                                <option value="">Selecciona un estado</option>
                                <option value="Ciudad de México">Ciudad de México</option>
                                <option value="Estado de México">Estado de México</option>
                                <option value="Jalisco">Jalisco</option>
                                <option value="Quintana Roo">Quintana Roo</option>
                                <option value="Nuevo León">Nuevo León</option>
                                </select>
                            <div class="invalid-feedback">Selecciona el estado.</div>
                        </div>

                        <div class="form-group">
                            <label for="habitaciones">Habitaciones</label>
                            <input type="number" id="habitaciones" name="habitaciones" class="form-control" placeholder="0 si es terreno" min="0" value="0">
                        </div>

                        <div class="form-group">
                            <label for="banos">Baños</label>
                            <input type="number" id="banos" name="banos" class="form-control" placeholder="0 si es terreno" min="0" value="0">
                        </div>

                        <div class="form-group full-width" style="max-width: 50%;">
                            <label for="area">Área (m²) <span class="text-accent">*</span></label>
                            <input type="number" id="area" name="area" class="form-control" placeholder="Ej. 120" required min="1">
                            <div class="invalid-feedback">El área debe ser mayor a 0.</div>
                        </div>

                        <div class="form-group full-width">
                            <label for="descripcion">Descripción de la propiedad <span class="text-accent">*</span></label>
                            <textarea id="descripcion" name="descripcion" class="form-control" rows="5" placeholder="Describe las características, amenidades y detalles importantes..." required minlength="30"></textarea>
                            <div class="invalid-feedback">La descripción debe ser más detallada (mínimo 30 caracteres).</div>
                        </div>

                        <div class="form-group full-width">
                            <label for="imagen">Fotografía Principal <span class="text-accent">*</span></label>
                            <input type="file" id="imagen" name="imagen" class="form-control file-input" accept="image/png, image/jpeg, image/webp" required>
                            <div class="invalid-feedback">Debes subir al menos una imagen principal.</div>
                            <small class="text-light" style="margin-top: 5px; display: block;">Formatos aceptados: JPG, PNG, WEBP. Tamaño máximo: 5MB.</small>
                        </div>

                    </div>

                    <div class="form-actions mt-4">
                        <button type="submit" class="btn btn--primary"><i class="fa-solid fa-paper-plane"></i> ENVIAR A REVISIÓN</button>
                        <button type="reset" class="btn btn--light-outline" style="margin-left: 10px;">BORRAR TODO</button>
                    </div>

                </form>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('formPropiedad');
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
                    // Hace scroll automático hacia el primer error
                    const firstError = document.querySelector('.is-invalid');
                    if(firstError) {
                        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                } else {
                    event.preventDefault(); 
                    alert("¡Propiedad enviada a revisión correctamente! (Simulación Frontend)");
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