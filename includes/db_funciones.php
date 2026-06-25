<?php
require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/config/db.php';

/* ============ PROPIEDADES ============ */

function obtenerPropiedadesDestacadas(int $limite = 3): array {
    $pdo = getDB();
    $stmt = $pdo->prepare(
        "SELECT p.*, u.nombre AS vendedor_nombre
         FROM propiedades p
         JOIN usuarios u ON p.vendedor_id = u.id
         WHERE p.destacado = 1 AND p.estatus = 'Disponible'
         ORDER BY p.fecha_publicacion DESC
         LIMIT :limite"
    );
    $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}

function obtenerPropiedades(array $filtros = []): array {
    $pdo = getDB();
    $sql = "SELECT p.*, u.nombre AS vendedor_nombre
            FROM propiedades p
            JOIN usuarios u ON p.vendedor_id = u.id
            WHERE p.estatus = 'Disponible'";
    $params = [];

    if (!empty($filtros['ubicacion'])) {
        $sql .= " AND (p.ubicacion LIKE :ubicacion OR p.estado LIKE :ubicacion)";
        $params['ubicacion'] = '%' . $filtros['ubicacion'] . '%';
    }
    if (!empty($filtros['tipo']) && $filtros['tipo'] !== 'todos') {
        $sql .= " AND p.tipo = :tipo";
        $params['tipo'] = $filtros['tipo'];
    }
    if (!empty($filtros['precio_min'])) {
        $sql .= " AND p.precio >= :precio_min";
        $params['precio_min'] = (float)$filtros['precio_min'];
    }
    if (!empty($filtros['precio_max'])) {
        $sql .= " AND p.precio <= :precio_max";
        $params['precio_max'] = (float)$filtros['precio_max'];
    }
    if (!empty($filtros['habitaciones'])) {
        $sql .= " AND p.habitaciones >= :habitaciones";
        $params['habitaciones'] = (int)$filtros['habitaciones'];
    }

    $sql .= " ORDER BY p.fecha_publicacion DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function obtenerPropiedad(int $id): ?array {
    $pdo = getDB();
    $stmt = $pdo->prepare(
        "SELECT p.*, u.nombre AS vendedor_nombre
         FROM propiedades p
         JOIN usuarios u ON p.vendedor_id = u.id
         WHERE p.id = :id"
    );
    $stmt->execute(['id' => $id]);
    $prop = $stmt->fetch();
    return $prop ?: null;
}

function obtenerMisPropiedades(int $vendedor_id): array {
    $pdo = getDB();
    $stmt = $pdo->prepare(
        "SELECT * FROM propiedades
         WHERE vendedor_id = :vendedor_id
         ORDER BY fecha_publicacion DESC"
    );
    $stmt->execute(['vendedor_id' => $vendedor_id]);
    return $stmt->fetchAll();
}

function obtenerTodasPropiedades(): array {
    $pdo = getDB();
    $stmt = $pdo->prepare(
        "SELECT p.*, u.nombre AS vendedor_nombre
         FROM propiedades p
         JOIN usuarios u ON p.vendedor_id = u.id
         ORDER BY p.fecha_publicacion DESC"
    );
    $stmt->execute();
    return $stmt->fetchAll();
}

function crearPropiedad(array $datos): int {
    $pdo = getDB();
    $stmt = $pdo->prepare(
        "INSERT INTO propiedades
         (titulo, precio, ubicacion, estado, habitaciones, banos, area, tipo, descripcion, imagen, vendedor_id, fecha_publicacion, estatus)
         VALUES
         (:titulo, :precio, :ubicacion, :estado, :habitaciones, :banos, :area, :tipo, :descripcion, :imagen, :vendedor_id, :fecha_publicacion, :estatus)"
    );
    $stmt->execute($datos);
    return (int)$pdo->lastInsertId();
}

function actualizarPropiedad(int $id, array $datos): void {
    $pdo = getDB();
    $campos = [];
    $params = ['id' => $id];
    foreach ($datos as $key => $value) {
        $campos[] = "$key = :$key";
        $params[$key] = $value;
    }
    $sql = "UPDATE propiedades SET " . implode(', ', $campos) . " WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
}

function eliminarPropiedad(int $id): void {
    $pdo = getDB();
    $stmt = $pdo->prepare("DELETE FROM propiedades WHERE id = :id");
    $stmt->execute(['id' => $id]);
}

function contarPropiedades(): array {
    $pdo = getDB();
    $total = (int)$pdo->query("SELECT COUNT(*) FROM propiedades")->fetchColumn();
    $pendientes = (int)$pdo->query("SELECT COUNT(*) FROM propiedades WHERE estatus = 'En Revisión'")->fetchColumn();
    $vistas = (int)$pdo->query("SELECT COALESCE(SUM(vistas), 0) FROM propiedades")->fetchColumn();
    return ['total' => $total, 'pendientes' => $pendientes, 'vistas' => $vistas];
}

function imgUrl(?string $path): string {
    if (!$path) return BASE_URL . 'assets/img/no-image.jpg';
    if (strpos($path, 'http') === 0) return $path;
    return BASE_URL . $path;
}

function subirImagen(array $archivo): string {
    $maxSize = 5 * 1024 * 1024;
    $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];

    if (!isset($archivo['tmp_name']) || $archivo['error'] !== UPLOAD_ERR_OK) {
        throw new \RuntimeException('Error al recibir el archivo.');
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $archivo['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mime, $allowedMimes, true)) {
        throw new \RuntimeException('Formato no permitido. Solo JPG, PNG o WEBP.');
    }

    if ($archivo['size'] > $maxSize) {
        throw new \RuntimeException('La imagen es demasiado grande (máx 5MB).');
    }

    $uploadDir = dirname(__DIR__) . '/assets/uploads/propiedades/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $ext = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'][$mime];
    $filename = uniqid('prop_') . '.' . $ext;
    $dstPath = $uploadDir . $filename;

    if (!move_uploaded_file($archivo['tmp_name'], $dstPath)) {
        throw new \RuntimeException('Error al guardar la imagen en el servidor.');
    }

    if (function_exists('imagecreatetruecolor')) {
        try {
            [$ancho, $alto] = getimagesize($dstPath);

            if ($ancho > 800) {
                $ratio = 800 / $ancho;
                $nuevoAlto = (int)($alto * $ratio);

                $src = match ($mime) {
                    'image/jpeg' => imagecreatefromjpeg($dstPath),
                    'image/png'  => imagecreatefrompng($dstPath),
                    'image/webp' => imagecreatefromwebp($dstPath),
                };

                if (!$src) {
                    return 'assets/uploads/propiedades/' . $filename;
                }

                $dst = imagecreatetruecolor(800, $nuevoAlto);

                if ($mime === 'image/png') {
                    imagealphablending($dst, false);
                    imagesavealpha($dst, true);
                }

                imagecopyresampled($dst, $src, 0, 0, 0, 0, 800, $nuevoAlto, $ancho, $alto);

                match ($mime) {
                    'image/jpeg' => imagejpeg($dst, $dstPath, 85),
                    'image/png'  => imagepng($dst, $dstPath, 6),
                    'image/webp' => imagewebp($dst, $dstPath, 85),
                };
            }
        } catch (\Throwable) {
        }
    }

    return 'assets/uploads/propiedades/' . $filename;
}

/* ============ USUARIOS ============ */

function obtenerUsuarios(): array {
    $pdo = getDB();
    $stmt = $pdo->query(
        "SELECT id, nombre, email, tipo, activo, fecha_registro
         FROM usuarios ORDER BY fecha_registro DESC"
    );
    return $stmt->fetchAll();
}

function obtenerUsuario(int $id): ?array {
    $pdo = getDB();
    $stmt = $pdo->prepare(
        "SELECT id, nombre, email, tipo, activo, fecha_registro
         FROM usuarios WHERE id = :id"
    );
    $stmt->execute(['id' => $id]);
    $row = $stmt->fetch();
    return $row ?: null;
}

function contarUsuarios(): array {
    $pdo = getDB();
    $total = (int)$pdo->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
    $admins = (int)$pdo->query("SELECT COUNT(*) FROM usuarios WHERE tipo = 'Administrador'")->fetchColumn();
    $vendedores = (int)$pdo->query("SELECT COUNT(*) FROM usuarios WHERE tipo = 'Vendedor'")->fetchColumn();
    $compradores = (int)$pdo->query("SELECT COUNT(*) FROM usuarios WHERE tipo = 'Comprador'")->fetchColumn();
    return [
        'total' => $total, 'admins' => $admins,
        'vendedores' => $vendedores, 'compradores' => $compradores,
    ];
}

function actualizarUsuario(int $id, array $datos): void {
    $pdo = getDB();
    $campos = [];
    $params = ['id' => $id];
    foreach ($datos as $key => $value) {
        $campos[] = "$key = :$key";
        $params[$key] = $value;
    }
    $sql = "UPDATE usuarios SET " . implode(', ', $campos) . " WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
}

/* ============ CONTACTOS / MENSAJES ============ */

function guardarContacto(array $datos): int {
    $pdo = getDB();
    $stmt = $pdo->prepare(
        "INSERT INTO mensajes (nombre, email, telefono, asunto, mensaje)
         VALUES (:nombre, :email, :telefono, :asunto, :mensaje)"
    );
    $stmt->execute($datos);
    return (int)$pdo->lastInsertId();
}

/* ============ FAVORITOS ============ */

function agregarFavorito(int $usuarioId, int $propiedadId): void {
    $pdo = getDB();
    $stmt = $pdo->prepare(
        "INSERT IGNORE INTO favoritos (usuario_id, propiedad_id)
         VALUES (:usuario_id, :propiedad_id)"
    );
    $stmt->execute(['usuario_id' => $usuarioId, 'propiedad_id' => $propiedadId]);
}

function eliminarFavorito(int $usuarioId, int $propiedadId): void {
    $pdo = getDB();
    $stmt = $pdo->prepare(
        "DELETE FROM favoritos WHERE usuario_id = :usuario_id AND propiedad_id = :propiedad_id"
    );
    $stmt->execute(['usuario_id' => $usuarioId, 'propiedad_id' => $propiedadId]);
}

function esFavorito(int $usuarioId, int $propiedadId): bool {
    $pdo = getDB();
    $stmt = $pdo->prepare(
        "SELECT COUNT(*) FROM favoritos WHERE usuario_id = :usuario_id AND propiedad_id = :propiedad_id"
    );
    $stmt->execute(['usuario_id' => $usuarioId, 'propiedad_id' => $propiedadId]);
    return $stmt->fetchColumn() > 0;
}

function obtenerFavoritos(int $usuarioId): array {
    $pdo = getDB();
    $stmt = $pdo->prepare(
        "SELECT p.*, u.nombre AS vendedor_nombre
         FROM favoritos f
         JOIN propiedades p ON f.propiedad_id = p.id
         JOIN usuarios u ON p.vendedor_id = u.id
         WHERE f.usuario_id = :usuario_id
         ORDER BY p.fecha_publicacion DESC"
    );
    $stmt->execute(['usuario_id' => $usuarioId]);
    return $stmt->fetchAll();
}

/* ============ RESEÑAS ============ */

function agregarResena(int $propiedadId, int $usuarioId, int $calificacion, string $comentario): void {
    $pdo = getDB();
    $stmt = $pdo->prepare(
        "INSERT INTO resenas (propiedad_id, usuario_id, calificacion, comentario)
         VALUES (:propiedad_id, :usuario_id, :calificacion, :comentario)"
    );
    $stmt->execute([
        'propiedad_id' => $propiedadId,
        'usuario_id'   => $usuarioId,
        'calificacion' => $calificacion,
        'comentario'   => $comentario,
    ]);
}

function obtenerResenas(int $propiedadId): array {
    $pdo = getDB();
    $stmt = $pdo->prepare(
        "SELECT r.*, u.nombre AS usuario_nombre
         FROM resenas r
         JOIN usuarios u ON r.usuario_id = u.id
         WHERE r.propiedad_id = :propiedad_id"
    );
    $stmt->execute(['propiedad_id' => $propiedadId]);
    return $stmt->fetchAll();
}

function obtenerResenasPorUsuario(int $usuarioId): array {
    $pdo = getDB();
    $stmt = $pdo->prepare(
        "SELECT r.*, p.titulo AS propiedad_titulo, p.imagen AS propiedad_imagen
         FROM resenas r
         JOIN propiedades p ON r.propiedad_id = p.id
         WHERE r.usuario_id = :usuario_id"
    );
    $stmt->execute(['usuario_id' => $usuarioId]);
    return $stmt->fetchAll();
}

function obtenerCalificacionPromedio(int $propiedadId): float {
    $pdo = getDB();
    $stmt = $pdo->prepare(
        "SELECT AVG(calificacion) FROM resenas WHERE propiedad_id = :propiedad_id"
    );
    $stmt->execute(['propiedad_id' => $propiedadId]);
    $avg = $stmt->fetchColumn();
    return $avg ? round((float)$avg, 1) : 0.0;
}

function obtenerPropiedadesPorEstatus(?string $estatus = null): array {
    $pdo = getDB();
    $sql = "SELECT p.*, u.nombre AS vendedor_nombre 
            FROM propiedades p 
            JOIN usuarios u ON p.vendedor_id = u.id";
    
    if ($estatus) {
        $sql .= " WHERE p.estatus = :estatus";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['estatus' => $estatus]);
    } else {
        $stmt = $pdo->query($sql);
    }
    return $stmt->fetchAll();
}