<?php
require_once dirname(__DIR__, 2) . '/includes/db_funciones.php';

$filtro_ubicacion  = $_GET['ubicacion'] ?? '';
$filtro_tipo       = $_GET['tipo'] ?? 'todos';
$filtro_precio_min = $_GET['precio_min'] ?? '';
$filtro_precio_max = $_GET['precio_max'] ?? '';
$filtro_recamaras  = $_GET['recamaras'] ?? 'cualquiera';

$filtros = [];
if ($filtro_ubicacion  !== '')               $filtros['ubicacion']   = $filtro_ubicacion;
if ($filtro_tipo       !== 'todos')          $filtros['tipo']        = $filtro_tipo;
if ($filtro_precio_min !== '')               $filtros['precio_min']  = (float)$filtro_precio_min;
if ($filtro_precio_max !== '')               $filtros['precio_max']  = (float)$filtro_precio_max;
if ($filtro_recamaras  !== 'cualquiera')     $filtros['habitaciones'] = (int)$filtro_recamaras;

$propiedades_filtradas = obtenerPropiedades($filtros);

$usuario = null;
$favoritosIds = [];
if (session_status() === PHP_SESSION_NONE) session_start();
if (isset($_SESSION['usuario'])) {
    $usuario = $_SESSION['usuario'];
    $favoritos = obtenerFavoritos($usuario['id']);
    $favoritosIds = array_column($favoritos, 'id');
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
                                <option value="Casa" <?php echo $filtro_tipo == 'Casa' ? 'selected' : ''; ?>>Casa</option>
                                <option value="Departamento" <?php echo $filtro_tipo == 'Departamento' ? 'selected' : ''; ?>>Departamento</option>
                                <option value="Local Comercial" <?php echo $filtro_tipo == 'Local Comercial' ? 'selected' : ''; ?>>Local Comercial</option>
                                <option value="Terreno" <?php echo $filtro_tipo == 'Terreno' ? 'selected' : ''; ?>>Terreno</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="precio_min">Precio mínimo</label>
                            <input type="number" id="precio_min" name="precio_min" placeholder="$ 0" class="form-control" value="<?php echo htmlspecialchars($filtro_precio_min); ?>">
                        </div>

                        <div class="form-group">
                            <label for="precio_max">Precio máximo</label>
                            <input type="number" id="precio_max" name="precio_max" placeholder="$ 0" class="form-control" value="<?php echo htmlspecialchars($filtro_precio_max); ?>">
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
                                <?php if ($usuario): ?>
                                    <?php $esFav = in_array($prop['id'], $favoritosIds); ?>
                                    <form action="<?php echo BASE_URL; ?>views/public/procesar-favorito.php" method="POST" class="fav-form">
                                        <input type="hidden" name="id" value="<?php echo $prop['id']; ?>">
                                        <input type="hidden" name="redirect" value="<?php echo BASE_URL; ?>views/public/catalogo.php<?php echo !empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : ''; ?>">
                                        <button type="submit" class="fav-btn" title="<?php echo $esFav ? 'Quitar de favoritos' : 'Agregar a favoritos'; ?>">
                                            <i class="fa-solid fa-heart <?php echo $esFav ? 'fav-active' : ''; ?>"></i>
                                        </button>
                                    </form>
                                <?php endif; ?>
                                <a href="<?php echo BASE_URL; ?>views/public/propiedad.php?id=<?php echo $prop['id']; ?>">
                                    <img src="<?php echo imgUrl($prop['imagen']); ?>" alt="<?php echo $prop['titulo']; ?>">
                                </a>
                            </div>
                            <div class="property-card__content">
                                <h2>
                                    <a href="<?php echo BASE_URL; ?>views/public/propiedad.php?id=<?php echo $prop['id']; ?>">
                                        <?php echo $prop['titulo']; ?>
                                    </a>
                                </h2>
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