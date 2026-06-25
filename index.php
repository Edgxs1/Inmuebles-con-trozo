<?php 
require_once 'config/config.php';
require_once 'config/db.php';

require_once 'includes/auth_check.php';
require_once 'includes/db_funciones.php';

iniciarSesion();

if (estaLogueado() && in_array($_SESSION['usuario']['tipo'], ['Administrador', 'Vendedor'])) {
    redirigirPorRol($_SESSION['usuario']['tipo']);
}

include 'includes/header.php'; 

$propiedades_destacadas = obtenerPropiedadesDestacadas(3);
?>

    <main>
        <div class="hero">
            <div class="hero__content">
                <h1>ENCUENTRA TU PROPIEDAD IDEAL</h1>
                <h2 class="text-accent">¡VA CON TODO!</h2>
                <p>Soluciones rápidas, transparentes y efectivas para la compra, venta y alquiler de propiedades en todo México.</p>
                
                <form action="<?php echo BASE_URL; ?>views/public/catalogo.php" method="GET" class="search-bar">
                    <input type="text" name="ubicacion" placeholder="Ciudad, estado o código postal" class="search-input">
                    <div class="search-divider"></div>
                    <select name="tipo" class="search-select">
                        <option value="todos">TIPO DE INMUEBLE</option>
                        <option value="Casa">Casa</option>
                        <option value="Departamento">Departamento</option>
                        <option value="Local Comercial">Local Comercial</option>
                        <option value="Terreno">Terreno</option>
                    </select>
                    <button type="submit" class="btn btn--primary search-btn"><i class="fa-solid fa-magnifying-glass"></i> BUSCAR</button>
                </form>
            </div>
        </div>

        <section class="features">
            <div class="container features__grid">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fa-solid fa-bolt"></i></div>
                    <h3>Agilidad</h3>
                    <p>Eliminamos la burocracia tradicional para procesos rápidos y eficientes.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="fa-solid fa-shield-halved"></i></div>
                    <h3>Transparencia</h3>
                    <p>Servicio honesto y directo, sin complicaciones ni sorpresas.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="fa-solid fa-arrow-trend-up"></i></div>
                    <h3>Resultados</h3>
                    <p>Orientados 100% a lograr tus objetivos inmobiliarios.</p>
                </div>
            </div>
        </section>

        <section class="properties">
            <div class="container">
                <div class="section-title">
                    <h2>PROPIEDADES DESTACADAS</h2>
                    <p>Las mejores oportunidades del momento</p>
                </div>

                <div class="properties__grid">
                    <?php foreach($propiedades_destacadas as $prop): ?>
                    <article class="property-card">
                        <div class="property-card__img-wrapper">
                            <span class="badge">DESTACADO</span>
                            <a href="<?php echo BASE_URL; ?>views/public/propiedad.php?id=<?php echo $prop['id']; ?>">
                                <img src="<?php echo imgUrl($prop['imagen']); ?>" alt="<?php echo $prop['titulo']; ?>">
                            </a>
                        </div>
                        <div class="property-card__content">
                            <h3>
                                <a href="<?php echo BASE_URL; ?>views/public/propiedad.php?id=<?php echo $prop['id']; ?>"
                                    style="color: inherit; text-decoration: none;">
                                    <?php echo $prop['titulo']; ?>
                                </a>
                            </h3>
                            <p class="price">$<?php echo number_format((float)$prop['precio']); ?></p>
                            <p class="location"><i class="fa-solid fa-location-dot"></i> <?php echo $prop['ubicacion']; ?></p>
                            
                            <div class="property-specs">
                                <?php if((int)$prop['habitaciones'] > 0): ?>
                                    <span><i class="fa-solid fa-bed"></i> <?php echo $prop['habitaciones']; ?></span>
                                <?php endif; ?>
                                
                                <?php if((int)$prop['banos'] > 0): ?>
                                    <span><i class="fa-solid fa-bath"></i> <?php echo $prop['banos']; ?></span>
                                <?php endif; ?>
                                
                                <span><i class="fa-solid fa-vector-square"></i> <?php echo $prop['area']; ?>m²</span>
                            </div>
                        </div>
                    </article>
                    <?php endforeach; ?>
                </div>
                
                <div class="text-center mt-4">
                    <a href="<?php echo BASE_URL; ?>views/public/catalogo.php" class="btn btn--primary">VER TODAS LAS PROPIEDADES</a>
                </div>
            </div>
        </section>
    </main>

<?php 
include 'includes/footer.php'; 
?>