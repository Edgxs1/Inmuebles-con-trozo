<?php
require_once dirname(__DIR__, 2) . '/includes/auth_check.php';
require_once dirname(__DIR__, 2) . '/includes/db_funciones.php';

requireRol('Administrador');

$usuarios = obtenerUsuarios();
$stats = contarUsuarios();

$flash_success = $_SESSION['flash_success'] ?? null;
$flash_error   = $_SESSION['flash_error'] ?? null;
unset($_SESSION['flash_success'], $_SESSION['flash_error']);

$tipos_roles = [
    'Administrador' => 'Administrador',
    'Vendedor'    => 'Vendedor',
    'Comprador'     => 'Comprador',
];

include '../../includes/header.php';
?>
    <main class="dashboard-page">
        <div class="container">
            
            <div class="back-navigation" style="margin-bottom: 20px;">
                <a href="<?php echo BASE_URL; ?>views/admin/dashboard.php" class="back-link">
                    <i class="fa-solid fa-arrow-left"></i> VOLVER AL DASHBOARD
                </a>
            </div>

            <div class="dashboard-header-top">
                <div>
                    <h1 class="dashboard-title">USUARIOS</h1>
                    <p class="text-light"><?php echo $stats['total']; ?> usuarios registrados</p>
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
                    <div class="stat-header">
                        <span>Totales</span>
                        <i class="fa-solid fa-users text-light"></i>
                    </div>
                    <div class="stat-value"><?php echo $stats['total']; ?></div>
                    <div class="stat-desc">usuarios registrados</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-header">
                        <span>Administradores</span>
                        <i class="fa-solid fa-user-shield text-light"></i>
                    </div>
                    <div class="stat-value"><?php echo $stats['admins']; ?></div>
                    <div class="stat-desc">con acceso total</div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <span>Vendedores</span>
                        <i class="fa-solid fa-store text-light"></i>
                    </div>
                    <div class="stat-value"><?php echo $stats['vendedores']; ?></div>
                    <div class="stat-desc">publican propiedades</div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <span>Compradores</span>
                        <i class="fa-solid fa-cart-shopping text-light"></i>
                    </div>
                    <div class="stat-value"><?php echo $stats['compradores']; ?></div>
                    <div class="stat-desc">buscando inmuebles</div>
                </div>
            </div>

            <div class="properties-list">
                <?php if (count($usuarios) > 0): ?>
                    <?php foreach($usuarios as $user): ?>
                    <article class="property-list-item">
                        <div style="width: 50px; height: 50px; background-color: var(--bg-light); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; color: var(--text-light); flex-shrink: 0;">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        
                        <div class="pli-content">
                            <div class="pli-top-row">
                                <div>
                                    <h2 class="pli-title"><?php echo htmlspecialchars($user['nombre']); ?></h2>
                                    <p class="pli-location"><?php echo htmlspecialchars($user['email']); ?></p>
                                </div>
                                <div class="pli-price-block">
                                    <div style="font-size: 0.85rem; font-weight: 600; color: var(--text-dark); margin-bottom: 3px;">
                                        <?php echo $tipos_roles[$user['tipo']] ?? ucfirst($user['tipo']); ?>
                                    </div>
                                    <div class="pli-status-badge <?php echo $user['activo'] ? 'badge--success' : ''; ?>" 
                                         style="<?php echo !$user['activo'] ? 'background-color: #ef4444; color: white;' : ''; ?>">
                                        <?php echo $user['activo'] ? 'ACTIVO' : 'SUSPENDIDO'; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="pli-metrics-row">
                                <div class="metric">
                                    <span class="metric-label">Registro</span>
                                    <span class="metric-value"><?php echo date('d/m/Y', strtotime($user['fecha_registro'])); ?></span>
                                </div>
                            </div>

                            <div class="pli-actions-row">
                                <form action="procesar-usuario.php" method="POST" style="display: inline;">
                                    <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                                    <input type="hidden" name="accion" value="toggle_activo">
                                    <button type="submit" class="btn btn--small" style="background-color: #ef4444; color: white;">
                                        <i class="fa-solid fa-ban"></i> <?php echo $user['activo'] ? 'SUSPENDER' : 'ACTIVAR'; ?>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </article>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="text-align: center; padding: 40px;">
                        <h3>No hay usuarios registrados</h3>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

<?php include '../../includes/footer.php'; ?>