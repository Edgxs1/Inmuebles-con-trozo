<?php 
// 1. Requerimos la base de datos simulada
require_once '../../config/data.php';

// 2. LÓGICA DE FILTROS
// Obtenemos los valores de la URL si existen, si no, los dejamos vacíos o por defecto
$filtro_ubicacion = isset($_GET['ubicacion']) ? $_GET['ubicacion'] : '';
$filtro_tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 'todos';
$filtro_precio_min = isset($_GET['precio_min']) && $_GET['precio_min'] !== '' ? (int)$_GET['precio_min'] : 0;
$filtro_precio_max = isset($_GET['precio_max']) && $_GET['precio_max'] !== '' ? (int)$_GET['precio_max'] : 999999999;
$filtro_recamaras = isset($_GET['recamaras']) ? $_GET['recamaras'] : 'cualquiera';

// Creamos un nuevo arreglo solo con las propiedades que pasen los filtros
$propiedades_filtradas = [];

foreach($propiedades as $id => $prop) {
    $pasa_filtros = true;

    // Filtro de ubicación (busca si la palabra clave está dentro de la ubicación)
    if($filtro_ubicacion !== '' && stripos($prop['ubicacion'], $filtro_ubicacion) === false) {
        $pasa_filtros = false;
    }
    // Filtro de tipo
    if($filtro_tipo !== 'todos' && strtolower($prop['tipo']) !== strtolower($filtro_tipo)) {
        $pasa_filtros = false;
    }
    // Filtro de precio mínimo y máximo
    if($prop['precio'] < $filtro_precio_min || $prop['precio'] > $filtro_precio_max) {
        $pasa_filtros = false;
    }
    // Filtro de recámaras (verifica que tenga al menos las recámaras solicitadas)
    if($filtro_recamaras !== 'cualquiera' && $prop['habitaciones'] < (int)$filtro_recamaras) {
        $pasa_filtros = false;
    }

    // Si pasó todas las pruebas, la agregamos a los resultados
    if($pasa_filtros) {
        $propiedades_filtradas[$id] = $prop;
    }
}

include '../../includes/header.php'; 
?>

    <main class="catalog-page">
        <div class="container">
            <div class="catalog-header">
                <h1>CATÁLOGO DE PROPIEDADES</h1>
                <p class="text-light"><?php echo count($propiedades_filtradas); ?> propiedades encontradas</p>
            </div>

            <div class="catalog-layout">
                
                <aside class="filters-panel">
                    <h2><i class="fa-solid fa-filter"></i> Filtros</h2>
                    
                    <form action="catalogo.php" method="GET" class="filters-form">
                        
                        <div class="form-group">
                            <label for="ubicacion">Ubicación</label>
                            <input type="text" id="ubicacion" name="ubicacion" placeholder="Ciudad o estado" class="form-control" value="<?php echo htmlspecialchars($filtro_ubicacion); ?>">
                        </div>

                        <div class="form-group">
                            <label for="tipo">Tipo de inmueble</label>
                            <select id="tipo" name="tipo" class="form-control">
                                <option value="todos" <?php echo $filtro_tipo == 'todos' ? 'selected' : ''; ?>>TODOS</option>
                                <option value="casa" <?php echo $filtro_tipo == 'casa' ? 'selected' : ''; ?>>Casa</option>
                                <option value="departamento" <?php echo $filtro_tipo == 'departamento' ? 'selected' : ''; ?>>Departamento</option>
                                <option value="local comercial" <?php echo $filtro_tipo == 'local comercial' ? 'selected' : ''; ?>>Local Comercial</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="precio_min">Precio mínimo</label>
                            <input type="number" id="precio_min" name="precio_min" placeholder="$ 0" class="form-control" value="<?php echo isset($_GET['precio_min']) ? $_GET['precio_min'] : ''; ?>">
                        </div>

                        <div class="form-group">
                            <label for="precio_max">Precio máximo</label>
                            <input type="number" id="precio_max" name="precio_max" placeholder="$ 0" class="form-control" value="<?php echo isset($_GET['precio_max']) ? $_GET['precio_max'] : ''; ?>">
                        </div>

                        <div class="form-group">
                            <label for="recamaras">Recámaras mínimas</label>
                            <select id="recamaras" name="recamaras" class="form-control">
                                <option value="cualquiera" <?php echo $filtro_recamaras == 'cualquiera' ? 'selected' : ''; ?>>CUALQUIERA</option>
                                <option value="1" <?php echo $filtro_recamaras == '1' ? 'selected' : ''; ?>>1+</option>
                                <option value="2" <?php echo $filtro_recamaras == '2' ? 'selected' : ''; ?>>2+</option>
                                <option value="3" <?php echo $filtro_recamaras == '3' ? 'selected' : ''; ?>>3+</option>
                                <option value="4" <?php echo $filtro_recamaras == '4' ? 'selected' : ''; ?>>4+</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn--primary w-100" style="margin-bottom: 10px;">APLICAR FILTROS</button>
                        <a href="catalogo.php" class="btn btn--light-outline w-100" style="text-align: center; display: block;">LIMPIAR</a>
                    </form>
                </aside>

                <div class="catalog-grid">
                    
                    <?php if(count($propiedades_filtradas) > 0): ?>
                        <?php foreach($propiedades_filtradas as $prop): ?>
                        <article class="property-card">
                            <div class="property-card__img-wrapper">
                                <?php if($prop['destacado']): ?>
                                    <span class="badge">DESTACADO</span>
                                <?php endif; ?>
                                <a href="<?php echo BASE_URL; ?>views/public/propiedad.php?id=<?php echo $prop['id']; ?>">
                                    <img src="<?php echo $prop['imagen']; ?>" alt="<?php echo $prop['titulo']; ?>">
                                </a>
                            </div>
                            <div class="property-card__content">
                                <h2>
                                    <a href="<?php echo BASE_URL; ?>views/public/propiedad.php?id=<?php echo $prop['id']; ?>">
                                        <?php echo $prop['titulo']; ?>
                                    </a>
                                </h2>
                                <p class="price">$<?php echo number_format($prop['precio']); ?></p>
                                <p class="location"><i class="fa-solid fa-location-dot"></i> <?php echo $prop['ubicacion']; ?></p>
                                
                                <div class="property-specs">
                                    <?php if($prop['habitaciones'] > 0): ?>
                                        <span><i class="fa-solid fa-bed"></i> <?php echo $prop['habitaciones']; ?></span>
                                    <?php endif; ?>
                                    <?php if($prop['banos'] > 0): ?>
                                        <span><i class="fa-solid fa-bath"></i> <?php echo $prop['banos']; ?></span>
                                    <?php endif; ?>
                                    <span><i class="fa-solid fa-vector-square"></i> <?php echo $prop['area']; ?>m²</span>
                                </div>
                            </div>
                        </article>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div style="grid-column: 1 / -1; text-align: center; padding: 50px;">
                            <h2>No se encontraron propiedades</h2>
                            <p class="text-light">Intenta modificar tus filtros de búsqueda.</p>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </main>

<?php include '../../includes/footer.php'; ?>