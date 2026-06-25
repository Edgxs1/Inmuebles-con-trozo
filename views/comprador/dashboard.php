<?php
require_once dirname(__DIR__, 2) . '/includes/auth_check.php';
require_once dirname(__DIR__, 2) . '/includes/db_funciones.php';

requireRol('Comprador');

$favoritos = obtenerFavoritos($_SESSION['usuario']['id']);
$resenas   = obtenerResenasPorUsuario($_SESSION['usuario']['id']);

include dirname(__DIR__, 2) . '/includes/header.php';
?>
    <main class="dashboard-page">
        <div class="container">
            <div class="dashboard-header-top">
                <div>
                    <h1 class="dashboard-title">MI DASHBOARD</h1>
                    <p class="text-light">Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario']['nombre']); ?></p>
                </div>
                <a href="<?php echo BASE_URL; ?>views/public/catalogo.php" class="btn btn--primary">
                    <i class="fa-solid fa-building"></i> EXPLORAR PROPIEDADES
                </a>
            </div>

            <?php if (isset($_SESSION['flash_success'])): ?>
                <div class="alert alert--success"><?php echo $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?></div>
            <?php endif; ?>
            <?php if (isset($_SESSION['flash_error'])): ?>
                <div class="alert alert--error"><?php echo $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?></div>
            <?php endif; ?>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-header">
                        <span>MIS FAVORITOS</span>
                        <i class="fa-solid fa-heart" style="color: var(--primary-color);"></i>
                    </div>
                    <div class="stat-value"><?php echo count($favoritos); ?></div>
                    <div class="stat-desc">Propiedades guardadas</div>
                </div>
                <div class="stat-card">
                    <div class="stat-header">
                        <span>MIS RESEÑAS</span>
                        <i class="fa-solid fa-star" style="color: #f59e0b;"></i>
                    </div>
                    <div class="stat-value"><?php echo count($resenas); ?></div>
                    <div class="stat-desc">Reseñas publicadas</div>
                </div>
                <div class="stat-card">
                    <div class="stat-header">
                        <span>MI PERFIL</span>
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <div class="stat-value" style="font-size: 1.1rem; font-weight: 400;"><?php echo htmlspecialchars($_SESSION['usuario']['email']); ?></div>
                    <div class="stat-desc">
                        <a href="<?php echo BASE_URL; ?>views/auth/perfil.php" style="color: var(--primary-color);">Editar perfil</a>
                    </div>
                </div>
            </div>

            <h2 class="list-title"><i class="fa-solid fa-heart" style="color: var(--primary-color);"></i> MIS FAVORITOS</h2>

            <div class="properties-list">
                <?php if (count($favoritos) > 0): ?>
                    <?php foreach ($favoritos as $prop): ?>
                        <article class="property-list-item">
                            <img src="<?php echo imgUrl($prop['imagen']); ?>" alt="<?php echo $prop['titulo']; ?>" class="pli-img">
                            <div class="pli-content">
                                <div class="pli-top-row">
                                    <div>
                                        <div class="pli-title">
                                            <a href="<?php echo BASE_URL; ?>views/public/propiedad.php?id=<?php echo $prop['id']; ?>" style="color: inherit; text-decoration: none;">
                                                <?php echo $prop['titulo']; ?>
                                            </a>
                                        </div>
                                        <div class="pli-location"><i class="fa-solid fa-location-dot"></i> <?php echo $prop['ubicacion']; ?></div>
                                        <div class="pli-vendor"><i class="fa-solid fa-user"></i> <?php echo htmlspecialchars($prop['vendedor_nombre']); ?></div>
                                    </div>
                                    <div class="pli-price-block">
                                        <div class="pli-price">$<?php echo number_format((float)$prop['precio']); ?></div>
                                        <span class="pli-status-badge <?php echo $prop['estatus'] === 'Disponible' ? 'badge--success' : ''; ?>">
                                            <?php echo $prop['estatus']; ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="pli-metrics-row">
                                    <div class="metric">
                                        <span class="metric-label">Tipo</span>
                                        <span class="metric-value"><?php echo $prop['tipo']; ?></span>
                                    </div>
                                    <div class="metric">
                                        <span class="metric-label">Área</span>
                                        <span class="metric-value"><?php echo $prop['area']; ?> m²</span>
                                    </div>
                                    <div class="metric">
                                        <span class="metric-label">Habitaciones</span>
                                        <span class="metric-value"><?php echo $prop['habitaciones']; ?></span>
                                    </div>
                                    <div class="metric">
                                        <span class="metric-label">Baños</span>
                                        <span class="metric-value"><?php echo $prop['banos']; ?></span>
                                    </div>
                                </div>
                                <div class="pli-actions-row">
                                    <a href="<?php echo BASE_URL; ?>views/public/propiedad.php?id=<?php echo $prop['id']; ?>" class="btn btn--primary btn--small">
                                        <i class="fa-solid fa-eye"></i> VER DETALLE
                                    </a>
                                    <form action="<?php echo BASE_URL; ?>views/public/procesar-favorito.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="id" value="<?php echo $prop['id']; ?>">
                                        <input type="hidden" name="redirect" value="<?php echo BASE_URL; ?>views/comprador/dashboard.php">
                                        <button type="submit" class="btn btn--danger btn--small">
                                            <i class="fa-solid fa-heart-broken"></i> QUITAR
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="text-align: center; padding: 50px;">
                        <h3>No tienes propiedades favoritas</h3>
                        <p class="text-light">Explora el catálogo y guarda las que te interesen.</p>
                        <a href="<?php echo BASE_URL; ?>views/public/catalogo.php" class="btn btn--primary" style="margin-top: 15px;">
                            <i class="fa-solid fa-building"></i> VER CATÁLOGO
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <h2 class="list-title" style="margin-top: 40px;"><i class="fa-solid fa-star" style="color: #f59e0b;"></i> MIS RESEÑAS</h2>

            <div class="properties-list">
                <?php if (count($resenas) > 0): ?>
                    <?php foreach ($resenas as $resena): ?>
                        <article class="property-list-item" style="align-items: flex-start;">
                            <img src="<?php echo imgUrl($resena['propiedad_imagen']); ?>" alt="<?php echo $resena['propiedad_titulo']; ?>" class="pli-img" style="width: 120px; height: 90px;">
                            <div class="pli-content">
                                <div class="pli-top-row">
                                    <div>
                                        <div class="pli-title" style="font-size: 1.1rem;">
                                            <a href="<?php echo BASE_URL; ?>views/public/propiedad.php?id=<?php echo $resena['propiedad_id']; ?>" style="color: inherit; text-decoration: none;">
                                                <?php echo $resena['propiedad_titulo']; ?>
                                            </a>
                                        </div>
                                        <div class="pli-location" style="margin-top: 5px;">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <i class="fa-solid fa-star" style="color: <?php echo $i <= $resena['calificacion'] ? '#f59e0b' : '#d1d5db'; ?>;"></i>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                </div>
                                <p style="margin-top: 10px; color: var(--text-dark); font-size: 0.9rem; line-height: 1.5;">
                                    <?php echo htmlspecialchars($resena['comentario']); ?>
                                </p>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="text-align: center; padding: 50px;">
                        <h3>No has publicado reseñas</h3>
                        <p class="text-light">Visita una propiedad y deja tu opinión.</p>
                        <a href="<?php echo BASE_URL; ?>views/public/catalogo.php" class="btn btn--primary" style="margin-top: 15px;">
                            <i class="fa-solid fa-building"></i> VER CATÁLOGO
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
<?php include dirname(__DIR__, 2) . '/includes/footer.php'; ?>
