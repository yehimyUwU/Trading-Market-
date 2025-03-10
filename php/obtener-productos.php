<?php
require_once 'conexion.php';
header('Content-Type: application/json');

try {
    $busqueda = isset($_GET['busqueda']) ? $_GET['busqueda'] : '';
    $categoria = isset($_GET['categoria']) ? $_GET['categoria'] : '';
    $orden = isset($_GET['orden']) ? $_GET['orden'] : 'reciente';
    
    $sql = "SELECT p.*, c.nombre as categoria_nombre, s.nombre as subcategoria_nombre, 
            u.nombre as vendedor_nombre
            FROM productos p
            INNER JOIN categorias c ON p.id_categoria = c.id_categoria
            INNER JOIN subcategorias s ON p.id_subcategoria = s.id_subcategoria
            INNER JOIN usuarios u ON p.id_usuario = u.id_usuario
            WHERE 1=1";
    $params = [];
    
    // Aplicar filtros de búsqueda
    if (!empty($busqueda)) {
        $sql .= " AND (p.nombre LIKE :busqueda OR p.descripcion LIKE :busqueda)";
        $params[':busqueda'] = "%{$busqueda}%";
    }
    
    if (!empty($categoria)) {
        $sql .= " AND c.id_categoria = :categoria";
        $params[':categoria'] = $categoria;
    }
    
    // Aplicar ordenamiento
    switch ($orden) {
        case 'precio-asc':
            $sql .= " ORDER BY p.precio ASC";
            break;
        case 'precio-desc':
            $sql .= " ORDER BY p.precio DESC";
            break;
        case 'antiguo':
            $sql .= " ORDER BY p.fecha_creacion ASC";
            break;
        case 'reciente':
        default:
            $sql .= " ORDER BY p.fecha_creacion DESC";
            break;
    }
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Formatear los datos para la respuesta
    foreach ($productos as &$producto) {
        $producto['imagen_url'] = '../imagenes/productos/' . $producto['imagen'];
        $producto['precio_formato'] = number_format($producto['precio'], 2);
        $producto['stock'] = $producto['cantidad_stock'];
        
        // Obtener historial de precios
        $sqlHistorial = "SELECT * FROM historial_precios 
                        WHERE id_producto = :id_producto 
                        ORDER BY fecha_cambio DESC LIMIT 1";
        $stmtHistorial = $pdo->prepare($sqlHistorial);
        $stmtHistorial->execute([':id_producto' => $producto['id_producto']]);
        $ultimoCambio = $stmtHistorial->fetch();
        
        if ($ultimoCambio) {
            $producto['ultimo_cambio_precio'] = [
                'precio_anterior' => $ultimoCambio['precio_anterior'],
                'fecha_cambio' => $ultimoCambio['fecha_cambio']
            ];
        }
    }
    
    echo json_encode([
        'success' => true,
        'productos' => $productos
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error al obtener los productos: ' . $e->getMessage()
    ]);
} 