<?php
include 'conexion.php';
header('Content-Type: application/json');

// Configuración
define('UPLOAD_DIR', '../imagenes/productos/');
define('MAX_FILE_SIZE', 2 * 1024 * 1024); // 2MB
define('ALLOWED_TYPES', ['image/jpeg', 'image/png']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Procesar la imagen
    $imagen = $_FILES['imagen'];
    $imagen_nombre = uniqid() . '_' . $imagen['name'];
    $carpeta_destino = '../imagenes/productos/';
    
    if (!file_exists($carpeta_destino)) {
        mkdir($carpeta_destino, 0777, true);
    }
    
    if (move_uploaded_file($imagen['tmp_name'], $carpeta_destino . $imagen_nombre)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO productos (
                id_usuario,
                id_categoria,
                id_subcategoria,
                referencia,
                nombre,
                descripcion,
                tipo,
                cantidad_stock,
                precio,
                costo,
                imagen
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

            $stmt->execute([
                1, // Cambiar por el ID del usuario real cuando tengas sistema de login
                $_POST['categoria'],
                $_POST['subcategoria'],
                $_POST['referencia'],
                $_POST['nombre'],
                $_POST['descripcion'],
                $_POST['tipo'],
                $_POST['cantidad_stock'],
                $_POST['precio'],
                $_POST['costo'],
                'imagenes/productos/' . $imagen_nombre
            ]);

            echo json_encode(['success' => true, 'message' => 'Producto guardado exitosamente']);
        } catch(PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Error al guardar el producto: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al subir la imagen']);
    }
    exit;
}

try {
    // Validar que sea una petición POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método no permitido');
    }

    // Por ahora usaremos un ID de usuario fijo (después se implementará el login)
    $id_usuario = 1;

    // Validar campos requeridos
    $campos_requeridos = ['nombreProducto', 'descripcion', 'precio', 'stock', 'categoriaGeneral', 'subcategoria'];
    foreach ($campos_requeridos as $campo) {
        if (!isset($_POST[$campo]) || empty($_POST[$campo])) {
            throw new Exception("El campo {$campo} es requerido.");
        }
    }

    // Validar y procesar la imagen
    if (!isset($_FILES['imagenProducto']) || $_FILES['imagenProducto']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('Error al subir la imagen.');
    }

    $imagen = $_FILES['imagenProducto'];
    
    // Validar tipo de archivo
    if (!in_array($imagen['type'], ALLOWED_TYPES)) {
        throw new Exception('Tipo de archivo no permitido. Solo se permiten imágenes JPG y PNG.');
    }

    // Validar tamaño
    if ($imagen['size'] > MAX_FILE_SIZE) {
        throw new Exception('La imagen es demasiado grande. El tamaño máximo permitido es 2MB.');
    }

    // Generar referencia única
    $referencia = 'REF-' . uniqid();

    // Generar nombre único para la imagen
    $extension = pathinfo($imagen['name'], PATHINFO_EXTENSION);
    $nombreImagen = uniqid() . '.' . $extension;
    $rutaImagen = UPLOAD_DIR . $nombreImagen;

    // Mover la imagen
    if (!move_uploaded_file($imagen['tmp_name'], $rutaImagen)) {
        throw new Exception('Error al guardar la imagen.');
    }

    // Iniciar transacción
    $pdo->beginTransaction();

    try {
        // Insertar el producto
        $sql = "INSERT INTO productos (
            id_usuario,
            id_categoria,
            id_subcategoria,
            referencia,
            nombre,
            descripcion,
            tipo,
            cantidad_stock,
            precio,
            costo,
            imagen,
            estado
        ) VALUES (
            :id_usuario,
            :id_categoria,
            :id_subcategoria,
            :referencia,
            :nombre,
            :descripcion,
            'producto',
            :stock,
            :precio,
            :costo,
            :imagen,
            'activo'
        )";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':id_usuario' => $id_usuario,
            ':id_categoria' => $_POST['categoriaGeneral'],
            ':id_subcategoria' => $_POST['subcategoria'],
            ':referencia' => $referencia,
            ':nombre' => $_POST['nombreProducto'],
            ':descripcion' => $_POST['descripcion'],
            ':stock' => $_POST['stock'],
            ':precio' => $_POST['precio'],
            ':costo' => $_POST['precio'] * 0.7, // Ejemplo: costo es 70% del precio
            ':imagen' => $nombreImagen
        ]);

        $id_producto = $pdo->lastInsertId();

        // Registrar en el historial de stock
        $sql = "INSERT INTO historial_stock (
            id_producto,
            cantidad_anterior,
            cantidad_nueva,
            tipo_movimiento
        ) VALUES (
            :id_producto,
            0,
            :cantidad_nueva,
            'entrada'
        )";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':id_producto' => $id_producto,
            ':cantidad_nueva' => $_POST['stock']
        ]);

        // Confirmar transacción
        $pdo->commit();

        // Respuesta exitosa
        echo json_encode([
            'success' => true,
            'message' => 'Producto agregado correctamente',
            'producto' => [
                'id' => $id_producto,
                'referencia' => $referencia,
                'nombre' => $_POST['nombreProducto'],
                'imagen' => $nombreImagen
            ]
        ]);

    } catch (Exception $e) {
        // Revertir transacción en caso de error
        $pdo->rollBack();
        throw $e;
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error en la base de datos: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} 