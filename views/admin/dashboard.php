<?php
require_once dirname(__DIR__, 2) . '/includes/auth_check.php';
require_once dirname(__DIR__, 2) . '/includes/db_funciones.php';

requireRol('Administrador');

$usuario = $_SESSION['usuario'];

$filtro_actual = $_GET['estatus'] ?? 'Todos';

if ($filtro_actual === 'Todos') {
    $propiedades = obtenerTodasPropiedades();
} else {
    $propiedades = obtenerPropiedadesPorEstatus($filtro_actual);
}

$stats_prop = contarPropiedades();
$stats_users = contarUsuarios();

$flash_success = $_SESSION['flash_success'] ?? null;
$flash_error = $_SESSION['flash_error'] ?? null;
unset($_SESSION['flash_success'], $_SESSION['flash_error']);

include '../../includes/header.php';
?>

<main class="dashboard-page">
    <div class="container">

        <div class="dashboard-header-top">
            <div>
                <h1 class="dashboard-title">PANEL DE ADMINISTRACIÓN</h1>
                <p class="text-light">Bienvenido, <?php echo htmlspecialchars($usuario['nombre']); ?></p>
            </div>
            <div>
                <a href="<?php echo BASE_URL; ?>views/admin/usuarios.php" class="btn btn--primary">
                    <i class="fa-solid fa-users-gear"></i> GESTIONAR USUARIOS
                </a>
            </div>
        </div>

        <?php if ($flash_success): ?>
            <div class="alert alert--success"><?php echo htmlspecialchars($flash_success); ?></div>
        <?php endif; ?>
        <?php if ($flash_error): ?>
            <div class="alert alert--error"><?php echo htmlspecialchars($flash_error); ?></div>
        <?php endif; ?>

        <div class="stats-grid-admin">
            <div class="stat-card">
                <div class="stat-header"><span>Propiedades Totales</span><i class="fa-solid fa-building text-light"></i>
                </div>
                <div class="stat-value"><?php echo $stats_prop['total']; ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-header"><span>Usuarios</span><i class="fa-solid fa-users text-light"></i></div>
                <div class="stat-value"><?php echo $stats_users['total']; ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-header"><span>Vistas</span><i class="fa-regular fa-eye text-light"></i></div>
                <div class="stat-value"><?php echo number_format($stats_prop['vistas']); ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-header"><span>En Revisión</span><i class="fa-solid fa-file-signature text-light"></i>
                </div>
                <div class="stat-value"><?php echo $stats_prop['pendientes']; ?></div>
            </div>
        </div>

        <h2 class="list-title">Propiedades Publicadas</h2>

        <form method="GET" style="margin-bottom: 25px; background: #f9f9f9; padding: 15px; border-radius: 8px;">
            <label style="font-weight: bold; margin-right: 10px;">Filtrar por estatus:</label>
            <select name="estatus" onchange="this.form.submit()"
                style="padding: 8px; border-radius: 5px; border: 1px solid #ccc;">
                <option value="Todos" <?php if ($filtro_actual == 'Todos')
                    echo 'selected'; ?>>Todos</option>
                <option value="Disponible" <?php if ($filtro_actual == 'Disponible')
                    echo 'selected'; ?>>Disponibles
                </option>
                <option value="En Revisión" <?php if ($filtro_actual == 'En Revisión')
                    echo 'selected'; ?>>En Revisión
                </option>
                <option value="Vendido" <?php if ($filtro_actual == 'Vendido')
                    echo 'selected'; ?>>Vendidos</option>
                <option value="Rentado" <?php if ($filtro_actual == 'Rentado')
                    echo 'selected'; ?>>Rentados</option>
                <option value="Pausado" <?php if ($filtro_actual == 'Pausado')
                    echo 'selected'; ?>>Pausados</option>
            </select>
        </form>

        <div class="properties-list">
            <?php if (count($propiedades) > 0): ?>
                <?php foreach ($propiedades as $prop): ?>
                    <article class="property-list-item">
                        <img src="<?php echo imgUrl($prop['imagen']); ?>" alt="<?php echo $prop['titulo']; ?>" class="pli-img">
                        <div class="pli-content">
                            <div class="pli-top-row">
                                <div>
                                    <h3 class="pli-title"><?php echo htmlspecialchars($prop['titulo']); ?></h3>
                                    <p class="pli-location"><?php echo htmlspecialchars($prop['ubicacion']); ?></p>
                                    <p class="pli-vendor">Vendedor:
                                        <?php echo htmlspecialchars($prop['vendedor_nombre'] ?? '—'); ?></p>
                                </div>
                                <div class="pli-price-block">
                                    <div class="pli-price">$<?php echo number_format((float) $prop['precio']); ?></div>
                                    <div class="pli-status-badge"
                                        style="background-color: <?php echo ($prop['estatus'] == 'Disponible') ? '#10b981' : (($prop['estatus'] == 'En Revisión') ? '#f59e0b' : '#6b7280'); ?>; color: white; padding: 4px 10px; border-radius: 12px; font-size: 0.75rem; display: inline-block;">
                                        <?php echo strtoupper($prop['estatus']); ?>
                                    </div>
                                </div>
                            </div>

                            <div class="pli-metrics-row">
                                <div class="metric"><span class="metric-label">Vistas</span><span
                                        class="metric-value"><?php echo $prop['vistas']; ?></span></div>
                                <div class="metric"><span class="metric-label">Tipo</span><span
                                        class="metric-value"><?php echo $prop['tipo']; ?></span></div>
                                <div class="metric"><span class="metric-label">Publicado</span><span
                                        class="metric-value"><?php echo date('d/m/Y', strtotime($prop['fecha_publicacion'])); ?></span>
                                </div>
                            </div>

                            <div class="pli-actions-row">
                                <?php if ($prop['estatus'] === 'En Revisión'): ?>
                                    <form action="procesar-aprobar.php" method="POST" style="display: inline;">
                                        <input type="hidden" name="id" value="<?php echo $prop['id']; ?>">
                                        <input type="hidden" name="accion" value="aprobar">
                                        <button type="submit" class="btn btn--success btn--small">APROBAR</button>
                                    </form>
                                <?php elseif ($prop['estatus'] === 'Disponible'): ?>
                                    <form action="procesar-aprobar.php" method="POST" style="display: inline;">
                                        <input type="hidden" name="id" value="<?php echo $prop['id']; ?>">
                                        <input type="hidden" name="accion" value="pausar">
                                        <button type="submit" class="btn btn--warning btn--small">PAUSAR</button>
                                    </form>
                                <?php elseif ($prop['estatus'] === 'Pausado'): ?>
                                    <form action="procesar-aprobar.php" method="POST" style="display: inline;">
                                        <input type="hidden" name="id" value="<?php echo $prop['id']; ?>">
                                        <input type="hidden" name="accion" value="aprobar">
                                        <button type="submit" class="btn btn--success btn--small">ACTIVAR</button>
                                    </form>
                                <?php endif; ?>

                                <form action="procesar-aprobar.php" method="POST" style="display: inline;">
                                    <input type="hidden" name="id" value="<?php echo $prop['id']; ?>">
                                    <input type="hidden" name="accion" value="rechazar">
                                    <button type="submit" class="btn btn--danger btn--small"
                                        onclick="return confirm('¿Eliminar esta propiedad?')">ELIMINAR</button>
                                </form>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="text-align: center; padding: 40px;">
                    <h3>No se encontraron propiedades en este estatus.</h3>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php include '../../includes/footer.php'; ?>