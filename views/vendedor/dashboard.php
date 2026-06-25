<?php
require_once dirname(__DIR__, 2) . '/includes/auth_check.php';
require_once dirname(__DIR__, 2) . '/includes/db_funciones.php';

requireRol('Vendedor', 'Administrador');

$usuario = $_SESSION['usuario'];
$mis_propiedades = obtenerMisPropiedades($usuario['id']);

$total_vistas = 0;
$total_clics  = 0;
foreach ($mis_propiedades as $p) {
    $total_vistas += (int)$p['vistas'];
    $total_clics  += (int)$p['clics'];
}

$flash_success = $_SESSION['flash_success'] ?? null;
$flash_error   = $_SESSION['flash_error'] ?? null;
unset($_SESSION['flash_success'], $_SESSION['flash_error']);

include '../../includes/header.php';
?>

    <main class="dashboard-page">
        <div class="container">
            
            <div class="dashboard-header-top">
                <div>
                    <h1 class="dashboard-title">PANEL DE VENDEDOR</h1>
                    <p class="text-light">Bienvenido, <?php echo htmlspecialchars($usuario['nombre']); ?></p>
                </div>
                <div>
                    <a href="<?php echo BASE_URL; ?>views/vendedor/nueva-propiedad.php" class="btn btn--primary">
                        <i class="fa-solid fa-plus"></i> PUBLICAR PROPIEDAD
                    </a>
                </div>
            </div>

            <?php if ($flash_success): ?>
                <div class="alert alert--success"><?php echo htmlspecialchars($flash_success); ?></div>
            <?php endif; ?>
            <?php if ($flash_error): ?>
                <div class="alert alert--error"><?php echo htmlspecialchars($flash_error); ?></div>
            <?php endif; ?>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-header"><span>Propiedades Publicadas</span><i class="fa-solid fa-building text-light"></i></div>
                    <div class="stat-value"><?php echo count($mis_propiedades); ?></div>
                </div>
                <div class="stat-card">
                    <div class="stat-header"><span>Vistas Totales</span><i class="fa-regular fa-eye text-light"></i></div>
                    <div class="stat-value"><?php echo number_format($total_vistas); ?></div>
                </div>
                <div class="stat-card">
                    <div class="stat-header"><span>Clics Totales</span><i class="fa-solid fa-arrow-pointer text-light"></i></div>
                    <div class="stat-value"><?php echo number_format($total_clics); ?></div>
                </div>
            </div>

            <h2 class="list-title">Mis Propiedades</h2>
            
            <div class="properties-list">
                <?php if (count($mis_propiedades) > 0): ?>
                    <?php foreach($mis_propiedades as $prop): ?>
                    <article class="property-list-item">
                        <img src="<?php echo imgUrl($prop['imagen']); ?>" alt="<?php echo $prop['titulo']; ?>" class="pli-img">
                        
                        <div class="pli-content">
                            <div class="pli-top-row">
                                <div>
                                    <h3 class="pli-title"><?php echo htmlspecialchars($prop['titulo']); ?></h3>
                                    <p class="pli-location"><?php echo htmlspecialchars($prop['ubicacion']); ?></p>
                                </div>
                                <div class="pli-price-block">
                                    <div class="pli-price">$<?php echo number_format((float)$prop['precio']); ?></div>
                                    <div class="pli-status-badge" style="background-color: <?php echo ($prop['estatus'] == 'Disponible') ? '#10b981' : (($prop['estatus'] == 'En Revisión') ? '#f59e0b' : '#6b7280'); ?>; color: white; padding: 4px 10px; border-radius: 12px; font-size: 0.75rem; display: inline-block;">
                                        <?php echo strtoupper($prop['estatus']); ?>
                                    </div>
                                </div>
                            </div>

                            <div class="pli-metrics-row">
                                <div class="metric"><span class="metric-label">Vistas</span><span class="metric-value"><?php echo $prop['vistas']; ?></span></div>
                                <div class="metric"><span class="metric-label">Clics</span><span class="metric-value"><?php echo $prop['clics']; ?></span></div>
                                <div class="metric"><span class="metric-label">Publicado</span><span class="metric-value"><?php echo date('d/m/Y', strtotime($prop['fecha_publicacion'])); ?></span></div>
                            </div>

                            <div class="pli-actions-row">
                                <a href="<?php echo BASE_URL; ?>views/vendedor/editar-propiedad.php?id=<?php echo $prop['id']; ?>" class="btn btn--light-outline btn--small">
                                    <i class="fa-solid fa-pen-to-square"></i> EDITAR
                                </a>
                                <a href="<?php echo BASE_URL; ?>views/vendedor/eliminar-propiedad.php?id=<?php echo $prop['id']; ?>" class="btn btn--small" style="background-color: #ef4444; color: white;" onclick="return confirm('¿Eliminar esta propiedad?')">
                                    <i class="fa-solid fa-trash"></i> ELIMINAR
                                </a>
                            </div>
                        </div>
                    </article>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="text-align: center; padding: 40px;">
                        <h3>Aún no has publicado propiedades</h3>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

<?php include '../../includes/footer.php'; ?>