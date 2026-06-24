<?php 
require_once '../../config/data.php';

include '../../includes/header.php'; 

$vendedores = ['Carlos Vendedor', 'Carlos Vendedor', 'María García', 'Juan Pérez'];
?>

    <main class="dashboard-page">
        <div class="container">
            
            <div class="dashboard-header-top">
                <div>
                    <h1 class="dashboard-title">PANEL DE ADMINISTRACIÓN</h1>
                    <p class="text-light">Bienvenido, Administrador</p>
                </div>
                <div>
                    <a href="<?php echo BASE_URL; ?>views/admin/usuarios.php" class="btn btn--primary">
                        <i class="fa-solid fa-users-gear"></i> GESTIONAR USUARIOS
                    </a>
                </div>
            </div>

            <div class="stats-grid-admin">
                <div class="stat-card">
                    <div class="stat-header">
                        <span>Propiedades Totales</span>
                        <i class="fa-solid fa-building text-light"></i>
                    </div>
                    <div class="stat-value">6</div>
                    <div class="stat-desc">en la plataforma</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-header">
                        <span>Usuarios Registrados</span>
                        <i class="fa-solid fa-users text-light"></i>
                    </div>
                    <div class="stat-value">4</div>
                    <div class="stat-desc">1 compradores, 2 vendedores</div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <span>Vistas Totales</span>
                        <i class="fa-regular fa-eye text-light"></i>
                    </div>
                    <div class="stat-value">6139</div>
                    <div class="stat-desc">en todas las propiedades</div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <span>Pendientes</span>
                        <i class="fa-solid fa-file-signature text-light"></i>
                    </div>
                    <div class="stat-value">5</div>
                    <div class="stat-desc">por revisar</div>
                </div>
            </div>

            <h2 class="list-title">Propiedades Publicadas</h2>
            
            <div class="properties-list">
                
                <?php foreach($propiedades as $index => $prop): 
                    
                    $vistas = 1250 - ($index * 180);
                    $clics = 89 - ($index * 11);
                    $fechas = ['14 de abril de 2026', '19 de abril de 2026', '22 de abril de 2026', '7 de mayo de 2026'];
                    $vendedor_actual = isset($vendedores[$index]) ? $vendedores[$index] : 'Vendedor Anónimo';
                ?>
                <article class="property-list-item">
                    <img src="<?php echo $prop['imagen']; ?>" alt="<?php echo $prop['titulo']; ?>" class="pli-img">
                    
                    <div class="pli-content">
                        
                        <div class="pli-top-row">
                            <div>
                                <h3 class="pli-title"><?php echo $prop['titulo']; ?></h3>
                                <p class="pli-location"><?php echo $prop['ubicacion']; ?></p>
                                <p class="pli-vendor">Vendedor: <?php echo $vendedor_actual; ?></p>
                            </div>
                            <div class="pli-price-block">
                                <div class="pli-price">$<?php echo number_format($prop['precio']); ?></div>
                                <div class="pli-status-badge badge--success"><?php echo strtoupper($prop['estatus']); ?></div>
                            </div>
                        </div>

                        <div class="pli-metrics-row">
                            <div class="metric">
                                <span class="metric-label">Vistas</span>
                                <span class="metric-value"><?php echo $vistas; ?></span>
                            </div>
                            <div class="metric">
                                <span class="metric-label">Clics</span>
                                <span class="metric-value"><?php echo $clics; ?></span>
                            </div>
                            <div class="metric">
                                <span class="metric-label">Tipo</span>
                                <span class="metric-value"><?php echo $prop['tipo']; ?></span>
                            </div>
                            <div class="metric">
                                <span class="metric-label">Publicado</span>
                                <span class="metric-value"><?php echo $fechas[$index]; ?></span>
                            </div>
                        </div>

                        <div class="pli-actions-row">
                            <button class="btn btn--success btn--small">
                                <i class="fa-solid fa-check-circle"></i> APROBAR
                            </button>
                            <button class="btn btn--danger btn--small">
                                <i class="fa-solid fa-circle-xmark"></i> RECHAZAR
                            </button>
                        </div>
                        
                    </div>
                </article>
                <?php endforeach; ?>

            </div>
        </div>
    </main>

<?php 

include '../../includes/footer.php'; 
?>