<?php 
require_once dirname(__DIR__, 2) . '/includes/auth_check.php';
require_once dirname(__DIR__, 2) . '/includes/db_funciones.php';

iniciarSesion();

$id_propiedad = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$prop = obtenerPropiedad($id_propiedad);

if (!$prop) {
    redirigir(BASE_URL . 'views/public/catalogo.php');
}

$usuario = $_SESSION['usuario'] ?? null;
$esFavorito = $usuario ? esFavorito($usuario['id'], $id_propiedad) : false;
$resenas = obtenerResenas($id_propiedad);
$promedio = obtenerCalificacionPromedio($id_propiedad);

include '../../includes/header.php'; 
?>

    <main class="property-detail-page bg-light">
        <div class="container">
            
            <div class="back-navigation">
                <a href="<?php echo BASE_URL; ?>views/public/catalogo.php" class="back-link">
                    <i class="fa-solid fa-arrow-left"></i> VOLVER AL CATÁLOGO
                </a>
            </div>

            <div class="property-layout">
                
                <div class="property-main-content">
                    
                    <div class="property-gallery">
                        <div class="main-image">
                            <img src="<?php echo imgUrl($prop['imagen']); ?>" alt="<?php echo $prop['titulo']; ?>">
                        </div>
                    </div>

                    <div class="property-info-section">
                        <h1 class="property-title"><?php echo $prop['titulo']; ?></h1>
                        <p class="property-location"><i class="fa-solid fa-location-dot"></i> <?php echo $prop['ubicacion']; ?></p>
                        
                        <div class="property-main-specs">
                            <?php if((int)$prop['habitaciones'] > 0): ?>
                                <span><i class="fa-solid fa-bed"></i> <?php echo $prop['habitaciones']; ?></span>
                            <?php endif; ?>
                            
                            <?php if((int)$prop['banos'] > 0): ?>
                                <span><i class="fa-solid fa-bath"></i> <?php echo $prop['banos']; ?></span>
                            <?php endif; ?>
                            
                            <span><i class="fa-solid fa-vector-square"></i> <?php echo $prop['area']; ?> m²</span>
                        </div>

                        <div class="property-description">
                            <h1>Descripción</h1>
                            <p><?php echo $prop['descripcion']; ?></p>
                        </div>

                        <div class="property-details-grid">
                            <div class="detail-item">
                                <span class="detail-label">Tipo</span>
                                <span class="detail-value"><?php echo $prop['tipo']; ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Estado</span>
                                <span class="detail-value"><?php echo $prop['estado']; ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Área</span>
                                <span class="detail-value"><?php echo $prop['area']; ?> m²</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Estatus</span>
                                <span class="detail-value text-accent font-bold"><?php echo $prop['estatus']; ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <aside class="property-sidebar">
                    <div class="action-card">
                        <div class="action-card__price">
                            $<?php echo number_format((float)$prop['precio']); ?>
                        </div>
                        
                        <div class="action-card__buttons">
                            <a href="tel:..." class="btn btn--primary w-100 mb-2"><i class="fa-solid fa-phone"></i> CONTACTAR VENDEDOR</a>
                            <a href="<?php echo BASE_URL; ?>views/public/contacto.php" class="btn btn--light-gray w-100 mb-2"><i class="fa-regular fa-envelope"></i> ENVIAR MENSAJE</a>
                            <?php if ($usuario): ?>
                                <form action="<?php echo BASE_URL; ?>views/public/procesar-favorito.php" method="POST">
                                    <input type="hidden" name="id" value="<?php echo $prop['id']; ?>">
                                    <input type="hidden" name="redirect" value="<?php echo BASE_URL; ?>views/public/propiedad.php?id=<?php echo $prop['id']; ?>">
                                    <button type="submit" class="btn <?php echo $esFavorito ? 'btn--primary' : 'btn--light-gray'; ?> w-100">
                                        <i class="fa-solid fa-heart"></i> <?php echo $esFavorito ? 'QUITAR DE FAVORITOS' : 'GUARDAR'; ?>
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </aside>

            </div>

            <div class="reviews-section">
                <h2 class="list-title"><i class="fa-solid fa-star" style="color: #f59e0b;"></i> RESEÑAS</h2>

                <?php if ($promedio > 0): ?>
                    <div class="reviews-summary">
                        <div class="reviews-average">
                            <span class="avg-rating"><?php echo $promedio; ?></span>
                            <div class="avg-stars">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="fa-solid fa-star" style="color: <?php echo $i <= round($promedio) ? '#f59e0b' : '#d1d5db'; ?>;"></i>
                                <?php endfor; ?>
                            </div>
                            <span class="avg-count"><?php echo count($resenas); ?> reseña(s)</span>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($usuario): ?>
                    <div class="property-form-container" style="margin-bottom: 30px;">
                        <div class="property-form-header">
                            <h2>DEJA TU RESEÑA</h2>
                        </div>
                        <form action="<?php echo BASE_URL; ?>views/public/procesar-resena.php" method="POST">
                            <input type="hidden" name="propiedad_id" value="<?php echo $prop['id']; ?>">
                            <div class="form-group">
                                <label>Calificación</label>
                                <div class="star-rating">
                                    <input type="radio" name="calificacion" value="5" id="star5" required>
                                    <label for="star5"><i class="fa-solid fa-star"></i></label>
                                    <input type="radio" name="calificacion" value="4" id="star4">
                                    <label for="star4"><i class="fa-solid fa-star"></i></label>
                                    <input type="radio" name="calificacion" value="3" id="star3">
                                    <label for="star3"><i class="fa-solid fa-star"></i></label>
                                    <input type="radio" name="calificacion" value="2" id="star2">
                                    <label for="star2"><i class="fa-solid fa-star"></i></label>
                                    <input type="radio" name="calificacion" value="1" id="star1">
                                    <label for="star1"><i class="fa-solid fa-star"></i></label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="comentario">Comentario</label>
                                <textarea name="comentario" id="comentario" class="form-control" placeholder="Comparte tu experiencia..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn--primary">PUBLICAR RESEÑA</button>
                        </form>
                    </div>
                <?php else: ?>
                    <div class="property-form-container" style="margin-bottom: 30px; text-align: center; padding: 30px;">
                        <p class="text-light">
                            <a href="<?php echo BASE_URL; ?>views/auth/login.php" style="color: var(--primary-color);">Inicia sesión</a> para dejar una reseña.
                        </p>
                    </div>
                <?php endif; ?>

                <div class="reviews-list">
                    <?php if (count($resenas) > 0): ?>
                        <?php foreach ($resenas as $resena): ?>
                            <div class="review-card">
                                <div class="review-header">
                                    <div class="review-user">
                                        <i class="fa-solid fa-user-circle" style="font-size: 2rem; color: var(--text-light);"></i>
                                        <div>
                                            <strong><?php echo htmlspecialchars($resena['usuario_nombre']); ?></strong>
                                            <div class="review-stars">
                                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                                    <i class="fa-solid fa-star" style="color: <?php echo $i <= $resena['calificacion'] ? '#f59e0b' : '#d1d5db'; ?>; font-size: 0.85rem;"></i>
                                                <?php endfor; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <p class="review-comment"><?php echo htmlspecialchars($resena['comentario']); ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-light" style="text-align: center; padding: 20px;">No hay reseñas aún. ¡Sé el primero en opinar!</p>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </main>

<?php include '../../includes/footer.php'; ?>