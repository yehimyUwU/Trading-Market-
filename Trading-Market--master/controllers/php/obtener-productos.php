<?php
require '../../config/php/conexion.php';

header('Content-Type: application/json');

try {
    $conn = Conexion::conectar();

    // Depuración: Verificar la base de datos seleccionada
    $dbName = $conn->query("SELECT DATABASE()")->fetchColumn();
    if ($dbName !== 'inventarios') {
        throw new Exception("Base de datos seleccionada incorrecta: $dbName");
    }

    // Depuración: Verificar si la tabla existe
    $tableExists = $conn->query("SHOW TABLES LIKE 'productos'")->fetch();
    if (!$tableExists) {
        throw new Exception("La tabla 'productos' no existe en la base de datos '$dbName'");
    }

    $input = json_decode(file_get_contents('php://input'), true);
    $busqueda = $input['busqueda'] ?? '';
    $ordenarPor = $input['ordenarPor'] ?? 'reciente';

    $sql = "SELECT * FROM productos WHERE 1=1";
    $params = [];

    // Filtro de búsqueda: Permitir búsqueda por cualquier letra o palabra
    if (!empty($busqueda)) {
        $sql .= " AND (nombre LIKE :busqueda OR descripcion LIKE :busqueda)";
        $params[':busqueda'] = "%{$busqueda}%";
    }

    // Ordenamiento
    switch ($ordenarPor) {
        case 'precio-asc':
            $sql .= " ORDER BY precio ASC";
            break;
        case 'precio-desc':
            $sql .= " ORDER BY precio DESC";
            break;
        case 'antiguo':
            $sql .= " ORDER BY fecha_creacion ASC";
            break;
        case 'reciente':
        default:
            $sql .= " ORDER BY fecha_creacion DESC";
            break;
    }

    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Verificar si se encontraron productos
    if (empty($productos)) {
        echo json_encode(['success' => false, 'message' => 'No se encontraron productos con los filtros aplicados.']);
        exit;
    }

    // Formatear datos para la respuesta
    foreach ($productos as &$producto) {
        $producto['imagen_url'] = '../../public/imag/' . $producto['imagen'];
        $producto['precio_formato'] = number_format($producto['precio'], 2);
    }

    echo json_encode(['success' => true, 'productos' => $productos]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error al obtener los productos: ' . $e->getMessage()]);
}
?>