<?php 
require_once '../../config/data.php';

$id_propiedad = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!isset($propiedades[$id_propiedad])) {
    header("Location: catalogo.php");
    exit;
}

$prop = $propiedades[$id_propiedad];

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
                            <img src="<?php echo $prop['imagen']; ?>" alt="<?php echo $prop['titulo']; ?>">
                        </div>
                    </div>

                    <div class="property-info-section">
                        <h1 class="property-title"><?php echo $prop['titulo']; ?></h1>
                        <p class="property-location"><i class="fa-solid fa-location-dot"></i> <?php echo $prop['ubicacion']; ?></p>
                        
                        <div class="property-main-specs">
                            <?php if($prop['habitaciones'] > 0): ?>
                                <span><i class="fa-solid fa-bed"></i> <?php echo $prop['habitaciones']; ?></span>
                            <?php endif; ?>
                            
                            <?php if($prop['banos'] > 0): ?>
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
                            $<?php echo number_format($prop['precio']); ?>
                        </div>
                        
                        <div class="action-card__buttons">
                            <button class="btn btn--primary w-100 mb-2"><i class="fa-solid fa-phone"></i> CONTACTAR VENDEDOR</button>
                            <button class="btn btn--light-gray w-100 mb-2"><i class="fa-regular fa-envelope"></i> ENVIAR MENSAJE</button>
                            <button class="btn btn--light-gray w-100"><i class="fa-regular fa-heart"></i> GUARDAR</button>
                        </div>
                    </div>
                </aside>

            </div>
        </div>
    </main>

<?php include '../../includes/footer.php'; ?>