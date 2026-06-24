<?php 
require_once '../../config/data.php';

include '../../includes/header.php'; 

$mis_propiedades = array_slice($propiedades, 0, 3);
?>

    <main class="dashboard-page">
        <div class="container">
            
            <div class="dashboard-header-top">
                <div>
                    <h1 class="dashboard-title">PANEL DE VENDEDOR</h1>
                    <p class="text-light">Bienvenido, Carlos Vendedor</p>
                </div>
                <div>
                    <a href="<?php echo BASE_URL; ?>views/vendedor/nueva-propiedad.php" class="btn btn--primary">
                        <i class="fa-solid fa-plus"></i> PUBLICAR PROPIEDAD
                    </a>
                </div>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-header">
                        <span>Propiedades Publicadas</span>
                        <i class="fa-solid fa-building text-light"></i>
                    </div>
                    <div class="stat-value">3</div>
                    <div class="stat-desc">3 disponibles</div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-header">
                        <span>Vistas Totales</span>
                        <i class="fa-regular fa-eye text-light"></i>
                    </div>
                    <div class="stat-value">2929</div>
                    <div class="stat-desc">en todas tus propiedades</div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <span>Clics Totales</span>
                        <i class="fa-solid fa-arrow-pointer text-light"></i>
                    </div>
                    <div class="stat-value">190</div>
                    <div class="stat-desc">Interacciones</div>
                </div>
            </div>

            <h2 class="list-title">Mis Propiedades</h2>
            
            <div class="properties-list">
                
                <?php foreach($mis_propiedades as $index => $prop): 
                    $vistas = 1250 - ($index * 360);
                    $clics = 89 - ($index * 22);
                    $fechas = ['14 de abril de 2026', '19 de abril de 2026', '7 de mayo de 2026'];
                ?>
                <article class="property-list-item">
                    <img src="<?php echo $prop['imagen']; ?>" alt="<?php echo $prop['titulo']; ?>" class="pli-img">
                    
                    <div class="pli-content">
                        
                        <div class="pli-top-row">
                            <div>
                                <h3 class="pli-title"><?php echo $prop['titulo']; ?></h3>
                                <p class="pli-location"><?php echo $prop['ubicacion']; ?></p>
                            </div>
                            <div class="pli-price-block">
                                <div class="pli-price">$<?php echo number_format($prop['precio']); ?></div>
                                <div class="pli-status"><?php echo $prop['estatus']; ?></div>
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
                                <span class="metric-label">Publicado</span>
                                <span class="metric-value"><?php echo $fechas[$index]; ?></span>
                            </div>
                        </div>

                        <div class="pli-actions-row">
                            <a href="<?php echo BASE_URL; ?>views/vendedor/editar-propiedad.php?id=<?php echo $prop['id']; ?>" class="btn btn--light-outline btn--small">
                                <i class="fa-solid fa-pen-to-square"></i> EDITAR
                            </a>
                            <button class="btn btn--light-outline btn--small">
                                <i class="fa-solid fa-pause"></i> PAUSAR
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