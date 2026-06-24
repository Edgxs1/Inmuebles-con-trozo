<?php 
include '../../includes/header.php'; 

$usuarios = [
    1 => ['nombre' => 'Admin Principal', 'email' => 'admin@inmueblescontreso.mx', 'tipo' => 'Administrador', 'fecha' => '01/01/2025', 'activo' => true],
    2 => ['nombre' => 'Carlos Vendedor', 'email' => 'carlos@email.com', 'tipo' => 'Vendedor', 'fecha' => '15/02/2025', 'activo' => true],
    3 => ['nombre' => 'María García', 'email' => 'maria@email.com', 'tipo' => 'Vendedor', 'fecha' => '03/03/2025', 'activo' => true],
    4 => ['nombre' => 'Juan Pérez', 'email' => 'juan@email.com', 'tipo' => 'Vendedor', 'fecha' => '20/04/2025', 'activo' => true],
    5 => ['nombre' => 'Ana López', 'email' => 'ana@email.com', 'tipo' => 'Comprador', 'fecha' => '10/05/2025', 'activo' => true],
    6 => ['nombre' => 'Pedro Martínez', 'email' => 'pedro@email.com', 'tipo' => 'Comprador', 'fecha' => '01/06/2025', 'activo' => false],
];
?>

    <main class="dashboard-page">
        <div class="container">
            
            <div class="back-navigation" style="margin-bottom: 20px;">
                <a href="<?php echo BASE_URL; ?>views/admin/index.php" class="back-link">
                    <i class="fa-solid fa-arrow-left"></i> VOLVER AL DASHBOARD
                </a>
            </div>

            <div class="dashboard-header-top">
                <div>
                    <h1 class="dashboard-title">USUARIOS</h1>
                    <p class="text-light"><?php echo count($usuarios); ?> usuarios registrados</p>
                </div>
            </div>

            <div class="stats-grid-admin">
                <div class="stat-card">
                    <div class="stat-header">
                        <span>Totales</span>
                        <i class="fa-solid fa-users text-light"></i>
                    </div>
                    <div class="stat-value"><?php echo count($usuarios); ?></div>
                    <div class="stat-desc">usuarios registrados</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-header">
                        <span>Administradores</span>
                        <i class="fa-solid fa-user-shield text-light"></i>
                    </div>
                    <div class="stat-value">1</div>
                    <div class="stat-desc">con acceso total</div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <span>Vendedores</span>
                        <i class="fa-solid fa-store text-light"></i>
                    </div>
                    <div class="stat-value">3</div>
                    <div class="stat-desc">publican propiedades</div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <span>Compradores</span>
                        <i class="fa-solid fa-cart-shopping text-light"></i>
                    </div>
                    <div class="stat-value">2</div>
                    <div class="stat-desc">buscando inmuebles</div>
                </div>
            </div>

            <div class="properties-list">
                <?php foreach($usuarios as $id => $user): ?>
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
                                    <?php echo htmlspecialchars($user['tipo']); ?>
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
                                <span class="metric-value"><?php echo $user['fecha']; ?></span>
                            </div>
                        </div>

                        <div class="pli-actions-row">
                            <button class="btn btn--light-outline btn--small">
                                <i class="fa-solid fa-pen-to-square"></i> EDITAR
                            </button>
                            <button class="btn btn--small" style="background-color: #ef4444; color: white;">
                                <i class="fa-solid fa-ban"></i> <?php echo $user['activo'] ? 'SUSPENDER' : 'ACTIVAR'; ?>
                            </button>
                        </div>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        </div>
    </main>

<?php include '../../includes/footer.php'; ?>
