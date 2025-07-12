<?php
require '../../config/php/conexion.php';
/**
 * Archivo: productoModelo.php
 * Descripción: Modelo para gestionar las operaciones CRUD de productos
 * Conexiones:
 * - Se conecta con: config/php/conexion.php (para la conexión a la base de datos)
 * - Se utiliza en: controllers/php/productoController.php
 * - Se utiliza en: views/html/Misproductos.php
 * - Se utiliza en: views/html/tienda.php
 */

class ProductoModelo {
    /**
     * Función: mdlRegistrarProducto
     * Descripción: Registra un nuevo producto en la base de datos
     * Parámetros:
     * - $nombre: Nombre del producto
     * - $categoria: ID de la categoría
     * - $precio: Precio del producto
     * - $descripcion: Descripción del producto
     * - $subcategoria: ID de la subcategoría
     * - $stock: Cantidad en stock
     * - $id_proveedor: ID del proveedor
     * - $imagen: Archivo de imagen
     * Flujo:
     * 1. Verifica si la categoría existe
     * 2. Guarda la imagen en el servidor
     * 3. Inserta el producto en la base de datos
     * Consultas SQL:
     * - SELECT COUNT(*) FROM categoria WHERE id_categoria = :categoria
     * - INSERT INTO producto (nombre, id_categoria, id_subcategoria, descripcion, precio, stock, imagen, id_empresa)
     */
    public static function mdlRegistrarProducto($nombre, $categoria, $precio, $descripcion, $subcategoria, $stock, $id_proveedor, $imagen ) {
        try {
            // Verificar si la categoría existe
            $verificarCategoria = Conexion::conectar()->prepare("SELECT COUNT(*) FROM categoria WHERE id_categoria = :categoria");
            $verificarCategoria->bindParam(":categoria", $categoria, PDO::PARAM_INT);
            $verificarCategoria->execute();
            $categoriaExiste = $verificarCategoria->fetchColumn();

            if ($categoriaExiste == 0) {
                return ["success" => false, "message" => "La categoría seleccionada no existe."];
            }

            // Guardar la imagen en la carpeta 'imag'
            $nombreImagen = uniqid() . "_" . basename($imagen['name']);
            $rutaDestino = "../../public/imag/" . $nombreImagen;
            if (!move_uploaded_file($imagen['tmp_name'], $rutaDestino)) {
                return ["success" => false, "message" => "Error al subir la imagen."];
            }

            // Insertar el producto con la imagen
            $stmt = Conexion::conectar()->prepare("INSERT INTO producto (nombre, id_categoria, id_subcategoria, descripcion, precio, stock, imagen, id_empresa) VALUES (:nombre, :categoria, :subcategoria, :descripcion, :precio, :stock, :imagen, :id_empresa)");
            $stmt->bindParam(":id_empresa", $id_proveedor, PDO::PARAM_INT);
            $stmt->bindParam(":nombre", $nombre, PDO::PARAM_STR);
            $stmt->bindParam(":categoria", $categoria, PDO::PARAM_INT);
            $stmt->bindParam(":subcategoria", $subcategoria, PDO::PARAM_INT);
            $stmt->bindParam(":descripcion", $descripcion, PDO::PARAM_STR);
            $stmt->bindParam(":precio", $precio, PDO::PARAM_STR);
            $stmt->bindParam(":stock", $stock, PDO::PARAM_INT);
            $stmt->bindParam(":imagen", $nombreImagen, PDO::PARAM_STR);

            if ($stmt->execute()) {
                return ["success" => true, "message" => "Producto registrado correctamente."];
            } else {
                return ["success" => false, "message" => "Error al registrar el producto. Verifica los datos enviados."];
            }
        } catch (PDOException $e) {
            return ["success" => false, "message" => "Error de base de datos: " . $e->getMessage()];
        }
    }

    /**
     * Función: mdlListarProductos
     * Descripción: Obtiene todos los productos con su categoría
     * Flujo:
     * 1. Realiza una consulta JOIN entre producto y categoria
     * 2. Procesa las rutas de las imágenes
     * 3. Retorna la lista de productos
     * Consulta SQL:
     * SELECT p.*, c.nombre AS nombre_categoria 
     * FROM producto p 
     * INNER JOIN categoria c ON p.id_categoria = c.id_categoria
     */
    public static function mdlListarProductos() {
        $mensaje = array();

        try {
            $objRespuesta = Conexion::conectar()->prepare("
                SELECT p.*, c.nombre AS nombre_categoria 
                FROM producto p 
                INNER JOIN categoria c ON p.id_categoria = c.id_categoria
            ");
            $objRespuesta->execute();
            $listaProductos = $objRespuesta->fetchAll(PDO::FETCH_ASSOC);

            // Asegurarse de incluir la ruta de la imagen
            foreach ($listaProductos as &$producto) {
                $producto['imagen'] = $producto['imagen'] ? "../../public/imag/" . $producto['imagen'] : "../../public/imagenes_P/default.jpeg";
            }

            $mensaje = array("codigo" => "200", "success" => true, "listaProductos" => $listaProductos);
            $objRespuesta = null;
        } catch (Exception $e) {
            $mensaje = array("codigo" => "401", "success" => false, "mensaje" => $e->getMessage());
        }

        return $mensaje;
    }

    /**
     * Función: mdlListarProductosPorProveedor
     * Descripción: Obtiene los productos de un proveedor específico
     * Parámetros:
     * - $id_proveedor: ID del proveedor
     * Flujo:
     * 1. Realiza una consulta JOIN filtrada por proveedor
     * 2. Procesa las rutas de las imágenes
     * 3. Retorna la lista de productos
     * Consulta SQL:
     * SELECT p.*, c.nombre AS nombre_categoria 
     * FROM producto p 
     * INNER JOIN categoria c ON p.id_categoria = c.id_categoria
     * WHERE p.id_empresa = :id_proveedor
     */
    public static function mdlListarProductosPorProveedor($id_proveedor) {
        $mensaje = array();
    
        try {
            $objRespuesta = Conexion::conectar()->prepare("
                SELECT p.*, c.nombre AS nombre_categoria 
                FROM producto p 
                INNER JOIN categoria c ON p.id_categoria = c.id_categoria
                WHERE p.id_empresa = :id_proveedor
            ");
            $objRespuesta->bindParam(":id_proveedor", $id_proveedor, PDO::PARAM_INT);
            $objRespuesta->execute();
            $listaProductos = $objRespuesta->fetchAll(PDO::FETCH_ASSOC);
    
            // Procesar imágenes
            foreach ($listaProductos as &$producto) {
                $rutaDestino = "../../public/imag/";
                $producto['imagen'] = $producto['imagen'] ? $rutaDestino . $producto['imagen'] : "../../public/imagenes_P/default.jpeg";
            }
    
            $mensaje = array(
                "codigo" => "200", 
                "success" => true, 
                "listaProductos" => $listaProductos
            );
            
        } catch (Exception $e) {
            $mensaje = array(
                "codigo" => "401", 
                "success" => false, 
                "mensaje" => "Error al listar productos: " . $e->getMessage()
            );
        }
    
        return $mensaje;
    }

    /**
     * Función: mdlEliminarProducto
     * Descripción: Elimina un producto y sus comentarios asociados
     * Parámetros:
     * - $idProducto: ID del producto a eliminar
     * Flujo:
     * 1. Elimina los comentarios del producto
     * 2. Elimina el producto
     * Consultas SQL:
     * - DELETE FROM comentarios WHERE id_producto = :idProducto
     * - DELETE FROM producto WHERE id_producto = :idProducto
     */
    public static function mdlEliminarProducto($idProducto) {
        $mensaje = array();

        try {
            // Primero eliminar los comentarios relacionados
            $objRespuesta = Conexion::conectar()->prepare("DELETE FROM comentarios WHERE id_producto = :idProducto");
            $objRespuesta->bindParam(":idProducto", $idProducto);
            $objRespuesta->execute();

            // Luego eliminar el producto
            $objRespuesta = Conexion::conectar()->prepare("DELETE FROM producto WHERE id_producto = :idProducto");
            $objRespuesta->bindParam(":idProducto", $idProducto);
            if ($objRespuesta->execute()) {
                $mensaje = array("codigo" => "200", "mensaje" => "Producto eliminado correctamente.");
            } else {
                $mensaje = array("codigo" => "401", "mensaje" => "Error al eliminar el producto.");
            }
            $objRespuesta = null;
        } catch (Exception $e) {
            $mensaje = array("codigo" => "401", "mensaje" => $e->getMessage());
        }

        return $mensaje;
    }

    /**
     * Función: mdlEditarProducto
     * Descripción: Actualiza la información de un producto existente
     * Parámetros:
     * - $id_producto: ID del producto a actualizar
     * - $nombre: Nuevo nombre
     * - $categoria: Nueva categoría
     * - $precio: Nuevo precio
     * - $descripcion: Nueva descripción
     * - $subcategoria: Nueva subcategoría
     * - $stock: Nuevo stock
     * - $imagen: Nueva imagen (opcional)
     * Flujo:
     * 1. Verifica si el producto existe
     * 2. Verifica si la categoría existe
     * 3. Si hay nueva imagen, la procesa
     * 4. Actualiza el producto
     * Consultas SQL:
     * - SELECT COUNT(*) FROM producto WHERE id_producto = :id_producto
     * - SELECT COUNT(*) FROM categoria WHERE id_categoria = :categoria
     * - UPDATE producto SET ... WHERE id_producto = :id_producto
     */
    public static function mdlEditarProducto($id_producto, $nombre, $categoria, $precio, $descripcion, $subcategoria, $stock, $imagen = null) {
        try {
            // Verificar si el producto existe y pertenece al proveedor
            $verificarProducto = Conexion::conectar()->prepare("SELECT COUNT(*) FROM producto WHERE id_producto = :id_producto");
            $verificarProducto->bindParam(":id_producto", $id_producto, PDO::PARAM_INT);
            $verificarProducto->execute();
            $productoExiste = $verificarProducto->fetchColumn();
    
            if ($productoExiste == 0) {
                return ["success" => false, "message" => "El producto no existe o no pertenece a este proveedor."];
            }
    
            // Verificar si la categoría existe
            $verificarCategoria = Conexion::conectar()->prepare("SELECT COUNT(*) FROM categoria WHERE id_categoria = :categoria");
            $verificarCategoria->bindParam(":categoria", $categoria, PDO::PARAM_INT);
            $verificarCategoria->execute();
            $categoriaExiste = $verificarCategoria->fetchColumn();
    
            if ($categoriaExiste == 0) {
                return ["success" => false, "message" => "La categoría seleccionada no existe."];
            }
    
            $nombreImagen = null;
            
            // Si hay una nueva imagen, procesarla
            if ($imagen !== null) {
                $nombreImagen = uniqid() . "_" . basename($imagen['name']);
                $rutaDestino = "../../public/imag/" . $nombreImagen;
                if (!move_uploaded_file($imagen['tmp_name'], $rutaDestino)) {
                    return ["success" => false, "message" => "Error al subir la imagen."];
                }
                
                // Actualizar el producto con la nueva imagen
                $stmt = Conexion::conectar()->prepare("UPDATE producto SET nombre = :nombre, id_categoria = :categoria, id_subcategoria = :subcategoria, descripcion = :descripcion, precio = :precio, stock = :stock, imagen = :imagen WHERE id_producto = :id_producto AND id_empresa = :id_empresa");
                $stmt->bindParam(":imagen", $nombreImagen, PDO::PARAM_STR);
            } else {
                // Actualizar el producto sin cambiar la imagen
                $stmt = Conexion::conectar()->prepare("UPDATE producto SET nombre = :nombre, id_categoria = :categoria, id_subcategoria = :subcategoria, descripcion = :descripcion, precio = :precio, stock = :stock WHERE id_producto = :id_producto");
            }
    
            $stmt->bindParam(":id_producto", $id_producto, PDO::PARAM_INT);
            $stmt->bindParam(":nombre", $nombre, PDO::PARAM_STR);
            $stmt->bindParam(":categoria", $categoria, PDO::PARAM_INT);
            $stmt->bindParam(":subcategoria", $subcategoria, PDO::PARAM_INT);
            $stmt->bindParam(":descripcion", $descripcion, PDO::PARAM_STR);
            $stmt->bindParam(":precio", $precio, PDO::PARAM_STR);
            $stmt->bindParam(":stock", $stock, PDO::PARAM_INT);
    
            if ($stmt->execute()) {
                return ["success" => true, "message" => "Producto actualizado correctamente."];
            } else {
                return ["success" => false, "message" => "Error al actualizar el producto. Verifica los datos enviados."];
            }
        } catch (PDOException $e) {
            return ["success" => false, "message" => "Error de base de datos: " . $e->getMessage()];
        }
    }

    /**
     * Función: mdlListarCategorias
     * Descripción: Obtiene todas las categorías disponibles
     * Flujo:
     * 1. Realiza una consulta simple a la tabla categoria
     * 2. Retorna la lista de categorías
     * Consulta SQL:
     * SELECT id_categoria, nombre FROM categoria
     */
    public static function mdlListarCategorias() {
        try {
            $stmt = Conexion::conectar()->prepare("SELECT id_categoria, nombre FROM categoria");
            $stmt->execute();
            $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return ["success" => true, "listaCategorias" => $categorias];
        } catch (PDOException $e) {
            return ["success" => false, "message" => $e->getMessage()];
        }
    }

    /**
     * Función: mdlEliminarProductos
     * Descripción: Elimina múltiples productos a la vez
     * Parámetros:
     * - $ids: Lista de IDs de productos a eliminar
     * Flujo:
     * 1. Elimina los productos especificados
     * Consulta SQL:
     * DELETE FROM producto WHERE id_producto IN ($ids)
     */
    public static function mdlEliminarProductos($ids) {
        $mensaje = array();

        try {
            $objRespuesta = Conexion::conectar()->prepare("DELETE FROM producto WHERE id_producto IN ($ids)");
            if ($objRespuesta->execute()) {
                $mensaje = array("codigo" => "200", "mensaje" => "Productos eliminados correctamente.");
            } else {
                $mensaje = array("codigo" => "401", "mensaje" => "Error. No fue posible eliminar los productos.");
            }
            $objRespuesta = null;
        } catch (Exception $e) {
            $mensaje = array("codigo" => "401", "mensaje" => $e->getMessage());
        }
        return $mensaje;
    }

    /**
     * Función: mdlreturnUsuarios
     * Descripción: Obtiene todos los usuarios del sistema
     * Flujo:
     * 1. Realiza una consulta simple a la tabla usuario
     * 2. Retorna la lista de usuarios
     * Consulta SQL:
     * SELECT * FROM usuario
     */
    public static function mdlreturnUsuarios() {
        $mensaje = array();

        try {
            $objRespuesta = Conexion::conectar()->prepare("SELECT * FROM usuario");
            $objRespuesta->execute();
            $listaUsuarios = $objRespuesta->fetchAll();
            $mensaje = array("codigo" => "200", "listaUsuarios" => $listaUsuarios);
            $objRespuesta = null;
        } catch (Exception $e) {
            $mensaje = array("codigo" => "401", "mensaje" => $e->getMessage());
        }

        return $mensaje;
    }

    /**
     * Función: mdlRegistrarProductoConPresentaciones
     * Descripción: Registra un producto con múltiples presentaciones/tamaños
     * Parámetros:
     * - $nombre: Nombre del producto
     * - $categoria: ID de la categoría
     * - $descripcion: Descripción del producto
     * - $subcategoria: ID de la subcategoría
     * - $id_proveedor: ID del proveedor
     * - $presentaciones: Array de presentaciones
     */
    public static function mdlRegistrarProductoConPresentaciones($nombre, $categoria, $descripcion, $subcategoria, $id_proveedor, $presentaciones) {
        try {
            error_log('[DEBUG] Iniciando registro de producto con presentaciones');
            // Verificar si la categoría existe
            $verificarCategoria = Conexion::conectar()->prepare("SELECT COUNT(*) FROM categoria WHERE id_categoria = :categoria");
            $verificarCategoria->bindParam(":categoria", $categoria, PDO::PARAM_INT);
            $verificarCategoria->execute();
            $categoriaExiste = $verificarCategoria->fetchColumn();

            if ($categoriaExiste == 0) {
                error_log('[ERROR] La categoría seleccionada no existe.');
                return ["success" => false, "message" => "La categoría seleccionada no existe."];
            }

            // Verificar que hay al menos una presentación
            if (empty($presentaciones)) {
                error_log('[ERROR] No se enviaron presentaciones.');
                return ["success" => false, "message" => "Debe agregar al menos una presentación del producto."];
            }

            // Iniciar transacción
            $pdo = Conexion::conectar();
            $pdo->beginTransaction();
            error_log('[DEBUG] Transacción iniciada');

            // Insertar el producto principal (sin precio ni stock, ya que están en presentaciones)
            $stmt = $pdo->prepare("INSERT INTO producto (nombre, id_categoria, id_subcategoria, descripcion, id_empresa) VALUES (:nombre, :categoria, :subcategoria, :descripcion, :id_empresa)");
            $stmt->bindParam(":nombre", $nombre, PDO::PARAM_STR);
            $stmt->bindParam(":categoria", $categoria, PDO::PARAM_INT);
            $stmt->bindParam(":subcategoria", $subcategoria, PDO::PARAM_INT);
            $stmt->bindParam(":descripcion", $descripcion, PDO::PARAM_STR);
            $stmt->bindParam(":id_empresa", $id_proveedor, PDO::PARAM_INT);

            if (!$stmt->execute()) {
                $errorInfo = $stmt->errorInfo();
                error_log('[ERROR] Error al registrar el producto principal: ' . $errorInfo[2]);
                throw new Exception("Error al registrar el producto principal: " . $errorInfo[2]);
            }

            $id_producto = $pdo->lastInsertId();
            error_log('[DEBUG] Producto insertado con id: ' . $id_producto);
            $presentacionesCreadas = 0;
            $imagenPrincipal = null;

            // Procesar cada presentación
            foreach ($presentaciones as $key => $presentacion) {
                error_log('[DEBUG] Datos recibidos en presentación ' . ($key + 1) . ': ' . json_encode($presentacion));
                // Validar datos de la presentación
                if (empty($presentacion['tamano']) || empty($presentacion['unidad']) || 
                    empty($presentacion['precio']) || empty($presentacion['stock'])) {
                    error_log('[ERROR] Datos incompletos en la presentación ' . ($key + 1));
                    throw new Exception("Datos incompletos en la presentación " . ($key + 1));
                }

                // Procesar imagen de la presentación
                $nombreImagen = null;
                if (isset($presentacion['imagen_file']) && $presentacion['imagen_file']['error'] === UPLOAD_ERR_OK) {
                    $imagen = $presentacion['imagen_file'];
                    $nombreImagen = "producto_" . $id_producto . "_presentacion_" . ($key + 1) . "_" . uniqid() . "_" . basename($imagen['name']);
                    $rutaDestino = "../../public/imag/" . $nombreImagen;
                    error_log('[DEBUG] Moviendo imagen de presentación: TMP=' . $imagen['tmp_name'] . ' DEST=' . $rutaDestino);
                    if (!move_uploaded_file($imagen['tmp_name'], $rutaDestino)) {
                        error_log('[ERROR] Error al subir la imagen de la presentación ' . ($key + 1) . '. TMP: ' . $imagen['tmp_name'] . ', Destino: ' . $rutaDestino);
                        throw new Exception("Error al subir la imagen de la presentación " . ($key + 1) . ". TMP: " . $imagen['tmp_name'] . ", Destino: " . $rutaDestino);
                    }
                    // Guardar la imagen principal solo de la primera presentación
                    if ($key === 0) {
                        $imagenPrincipal = $nombreImagen;
                    }
                } else {
                    error_log('[ERROR] Imagen requerida para la presentación ' . ($key + 1) . '. Error: ' . (isset($presentacion['imagen_file']['error']) ? $presentacion['imagen_file']['error'] : 'No file'));
                    throw new Exception("Imagen requerida para la presentación " . ($key + 1) . ". Error: " . (isset($presentacion['imagen_file']['error']) ? $presentacion['imagen_file']['error'] : 'No file'));
                }

                error_log('[DEBUG] Nombre de imagen generado para presentacion ' . ($key + 1) . ': ' . $nombreImagen);

                // Insertar presentación
                $stmtPresentacion = $pdo->prepare("
                    INSERT INTO presentacion_producto (
                        id_producto, tamano, unidad, precio, stock, 
                        nombre_imagen, largo, ancho, alto, unidad_dimension
                    ) VALUES (
                        :id_producto, :tamano, :unidad, :precio, :stock,
                        :nombre_imagen, :largo, :ancho, :alto, :unidad_dimension
                    )
                ");

                $stmtPresentacion->bindParam(":id_producto", $id_producto, PDO::PARAM_INT);
                $stmtPresentacion->bindParam(":tamano", $presentacion['tamano'], PDO::PARAM_STR);
                $stmtPresentacion->bindParam(":unidad", $presentacion['unidad'], PDO::PARAM_STR);
                $stmtPresentacion->bindParam(":precio", $presentacion['precio'], PDO::PARAM_STR);
                $stmtPresentacion->bindParam(":stock", $presentacion['stock'], PDO::PARAM_INT);
                $stmtPresentacion->bindParam(":nombre_imagen", $nombreImagen, PDO::PARAM_STR);
                
                // Dimensiones opcionales
                $largo = !empty($presentacion['largo']) ? $presentacion['largo'] : null;
                $ancho = !empty($presentacion['ancho']) ? $presentacion['ancho'] : null;
                $alto = !empty($presentacion['alto']) ? $presentacion['alto'] : null;
                $unidad_dimension = !empty($presentacion['unidad_dimension']) ? $presentacion['unidad_dimension'] : null;
                
                $stmtPresentacion->bindParam(":largo", $largo);
                $stmtPresentacion->bindParam(":ancho", $ancho);
                $stmtPresentacion->bindParam(":alto", $alto);
                $stmtPresentacion->bindParam(":unidad_dimension", $unidad_dimension);

                error_log('[DEBUG] Insertando presentacion con nombre_imagen: ' . $nombreImagen);
                if (!$stmtPresentacion->execute()) {
                    $errorInfo = $stmtPresentacion->errorInfo();
                    error_log('[ERROR] Error al registrar la presentación ' . ($key + 1) . ': ' . $errorInfo[2]);
                    throw new Exception("Error al registrar la presentación " . ($key + 1) . ": " . $errorInfo[2]);
                }

                $presentacionesCreadas++;
                error_log('[DEBUG] Presentación ' . ($key + 1) . ' insertada correctamente');
            }

            // Si hay imagen principal, actualizar el producto con esa imagen
            if ($imagenPrincipal) {
                $stmtUpdate = $pdo->prepare("UPDATE producto SET imagen = :imagen WHERE id_producto = :id_producto");
                $stmtUpdate->bindParam(":imagen", $imagenPrincipal, PDO::PARAM_STR);
                $stmtUpdate->bindParam(":id_producto", $id_producto, PDO::PARAM_INT);
                $stmtUpdate->execute();
            }

            // Confirmar transacción
            $pdo->commit();
            error_log('[DEBUG] Transacción confirmada');

            return [
                "success" => true, 
                "message" => "¡Producto y presentaciones registrados exitosamente! 🎉",
                "id_producto" => $id_producto,
                "presentaciones_creadas" => $presentacionesCreadas
            ];

        } catch (Exception $e) {
            // Revertir transacción en caso de error
            if (isset($pdo)) {
                $pdo->rollBack();
                error_log('[ERROR] Transacción revertida');
            }
            error_log('[ERROR] Excepción: ' . $e->getMessage());
            return ["success" => false, "message" => "Error: " . $e->getMessage(), "trace" => $e->getTraceAsString()];
        }
    }

    /**
     * Función: mdlListarProductosConPresentaciones
     * Descripción: Obtiene los productos de un proveedor con sus presentaciones
     * Parámetros:
     * - $id_proveedor: ID del proveedor
     */
    public static function mdlListarProductosConPresentaciones($id_proveedor) {
        $mensaje = array();
    
        try {
            // Obtener productos con sus presentaciones
            $objRespuesta = Conexion::conectar()->prepare("
                SELECT 
                    p.id_producto,
                    p.nombre,
                    p.descripcion,
                    p.id_categoria,
                    p.id_subcategoria,
                    c.nombre AS nombre_categoria,
                    sc.nombre AS nombre_subcategoria,
                    pp.id_presentacion,
                    pp.tamano,
                    pp.unidad,
                    pp.precio,
                    pp.stock,
                    pp.nombre_imagen,
                    pp.largo,
                    pp.ancho,
                    pp.alto,
                    pp.unidad_dimension
                FROM producto p 
                INNER JOIN categoria c ON p.id_categoria = c.id_categoria
                INNER JOIN subcategoria sc ON p.id_subcategoria = sc.id_subcategoria
                LEFT JOIN presentacion_producto pp ON p.id_producto = pp.id_producto
                WHERE p.id_empresa = :id_proveedor
                ORDER BY p.id_producto, pp.id_presentacion
            ");
            
            $objRespuesta->bindParam(":id_proveedor", $id_proveedor, PDO::PARAM_INT);
            $objRespuesta->execute();
            $resultados = $objRespuesta->fetchAll(PDO::FETCH_ASSOC);
    
            // Organizar productos con sus presentaciones
            $productos = [];
            foreach ($resultados as $row) {
                $id_producto = $row['id_producto'];
                
                if (!isset($productos[$id_producto])) {
                    $productos[$id_producto] = [
                        'id_producto' => $row['id_producto'],
                        'nombre' => $row['nombre'],
                        'descripcion' => $row['descripcion'],
                        'id_categoria' => $row['id_categoria'],
                        'id_subcategoria' => $row['id_subcategoria'],
                        'nombre_categoria' => $row['nombre_categoria'],
                        'nombre_subcategoria' => $row['nombre_subcategoria'],
                        'presentaciones' => []
                    ];
                }
                
                // Agregar presentación si existe
                if ($row['id_presentacion']) {
                    $productos[$id_producto]['presentaciones'][] = [
                        'id_presentacion' => $row['id_presentacion'],
                        'tamano' => $row['tamano'],
                        'unidad' => $row['unidad'],
                        'precio' => $row['precio'],
                        'stock' => $row['stock'],
                        'imagen' => $row['nombre_imagen'] ? "../../public/imag/" . $row['nombre_imagen'] : "../../public/imagenes_P/default.jpeg",
                        'dimensiones' => [
                            'largo' => $row['largo'],
                            'ancho' => $row['ancho'],
                            'alto' => $row['alto'],
                            'unidad_dimension' => $row['unidad_dimension']
                        ]
                    ];
                }
            }
    
            $mensaje = array(
                "codigo" => "200", 
                "success" => true, 
                "listaProductos" => array_values($productos)
            );
            
        } catch (Exception $e) {
            $mensaje = array(
                "codigo" => "401", 
                "success" => false, 
                "mensaje" => "Error al listar productos: " . $e->getMessage()
            );
        }

        return $mensaje;
    }

    public static function mdlObtenerProductoConPresentaciones($id_producto) {
        try {
            $pdo = Conexion::conectar();
            // Obtener datos del producto
            $stmt = $pdo->prepare("SELECT * FROM producto WHERE id_producto = :id_producto");
            $stmt->bindParam(":id_producto", $id_producto, PDO::PARAM_INT);
            $stmt->execute();
            $producto = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$producto) {
                return ["success" => false, "message" => "Producto no encontrado"];
            }

            // Obtener presentaciones
            $stmt2 = $pdo->prepare("SELECT *, nombre_imagen as nombre_imagen, CONCAT('../../public/imag/', nombre_imagen) as imagen FROM presentacion_producto WHERE id_producto = :id_producto");
            $stmt2->bindParam(":id_producto", $id_producto, PDO::PARAM_INT);
            $stmt2->execute();
            $presentaciones = $stmt2->fetchAll(PDO::FETCH_ASSOC);

            // Asegura que cada presentación tenga ambas propiedades
            foreach ($presentaciones as &$pres) {
                if (!isset($pres['nombre_imagen']) || !$pres['nombre_imagen']) {
                    $pres['nombre_imagen'] = null;
                }
                if (!isset($pres['imagen']) || !$pres['nombre_imagen']) {
                    $pres['imagen'] = '../../public/imagenes_P/default.jpeg';
                }
            }

            $producto['presentaciones'] = $presentaciones;
            return ["success" => true, "producto" => $producto];
        } catch (Exception $e) {
            return ["success" => false, "message" => $e->getMessage()];
        }
    }

    public static function mdlEditarProductoConPresentaciones($id_producto, $nombre, $categoria, $descripcion, $subcategoria, $id_proveedor, $presentaciones) {
        try {
            $pdo = Conexion::conectar();
            $pdo->beginTransaction();

            // Actualizar datos generales del producto
            $stmt = $pdo->prepare("UPDATE producto SET nombre = :nombre, id_categoria = :categoria, id_subcategoria = :subcategoria, descripcion = :descripcion, id_empresa = :id_empresa WHERE id_producto = :id_producto");
            $stmt->bindParam(":nombre", $nombre, PDO::PARAM_STR);
            $stmt->bindParam(":categoria", $categoria, PDO::PARAM_INT);
            $stmt->bindParam(":subcategoria", $subcategoria, PDO::PARAM_INT);
            $stmt->bindParam(":descripcion", $descripcion, PDO::PARAM_STR);
            $stmt->bindParam(":id_empresa", $id_proveedor, PDO::PARAM_INT);
            $stmt->bindParam(":id_producto", $id_producto, PDO::PARAM_INT);
            $stmt->execute();

            // Obtener IDs de presentaciones actuales
            $stmtIds = $pdo->prepare("SELECT id_presentacion FROM presentacion_producto WHERE id_producto = :id_producto");
            $stmtIds->bindParam(":id_producto", $id_producto, PDO::PARAM_INT);
            $stmtIds->execute();
            $idsActuales = $stmtIds->fetchAll(PDO::FETCH_COLUMN);
            $idsEnviados = [];
            $imagenPrincipal = null;

            foreach ($presentaciones as $key => $presentacion) {
                // Si tiene id_presentacion, actualizar; si no, insertar
                if (!empty($presentacion['id_presentacion'])) {
                    $idsEnviados[] = $presentacion['id_presentacion'];
                    // Si hay nueva imagen, procesarla
                    $nombreImagen = $presentacion['nombre_imagen'] ?? null;
                    if (isset($presentacion['imagen_file']) && $presentacion['imagen_file']['error'] === UPLOAD_ERR_OK) {
                        $imagen = $presentacion['imagen_file'];
                        $nombreImagen = "producto_" . $id_producto . "_presentacion_" . ($key + 1) . "_" . uniqid() . "_" . basename($imagen['name']);
                        $rutaDestino = "../../public/imag/" . $nombreImagen;
                        if (!move_uploaded_file($imagen['tmp_name'], $rutaDestino)) {
                            throw new Exception("Error al subir la imagen de la presentación " . ($key + 1));
                        }
                    }
                    // Si no hay nueva imagen y nombreImagen está vacío, consulta el valor anterior de la base de datos
                    if (empty($nombreImagen)) {
                        $stmtPrev = $pdo->prepare("SELECT nombre_imagen FROM presentacion_producto WHERE id_presentacion = :id_presentacion");
                        $stmtPrev->bindParam(":id_presentacion", $presentacion['id_presentacion']);
                        $stmtPrev->execute();
                        $prev = $stmtPrev->fetch(PDO::FETCH_ASSOC);
                        if ($prev && !empty($prev['nombre_imagen'])) {
                            $nombreImagen = $prev['nombre_imagen'];
                        }
                    }
                    // Actualizar presentación
                    $stmtP = $pdo->prepare("UPDATE presentacion_producto SET tamano = :tamano, unidad = :unidad, precio = :precio, stock = :stock, nombre_imagen = :nombre_imagen, largo = :largo, ancho = :ancho, alto = :alto, unidad_dimension = :unidad_dimension WHERE id_presentacion = :id_presentacion AND id_producto = :id_producto");
                    $stmtP->bindParam(":tamano", $presentacion['tamano']);
                    $stmtP->bindParam(":unidad", $presentacion['unidad']);
                    $stmtP->bindParam(":precio", $presentacion['precio']);
                    $stmtP->bindParam(":stock", $presentacion['stock']);
                    $stmtP->bindParam(":nombre_imagen", $nombreImagen);
                    $largo = !empty($presentacion['largo']) ? $presentacion['largo'] : null;
                    $ancho = !empty($presentacion['ancho']) ? $presentacion['ancho'] : null;
                    $alto = !empty($presentacion['alto']) ? $presentacion['alto'] : null;
                    $unidad_dimension = !empty($presentacion['unidad_dimension']) ? $presentacion['unidad_dimension'] : null;
                    $stmtP->bindParam(":largo", $largo);
                    $stmtP->bindParam(":ancho", $ancho);
                    $stmtP->bindParam(":alto", $alto);
                    $stmtP->bindParam(":unidad_dimension", $unidad_dimension);
                    $stmtP->bindParam(":id_presentacion", $presentacion['id_presentacion']);
                    $stmtP->bindParam(":id_producto", $id_producto);
                    $stmtP->execute();
                    if ($key === 0) {
                        $imagenPrincipal = $nombreImagen;
                    }
                } else {
                    // Insertar nueva presentación
                    $nombreImagen = null;
                    if (isset($presentacion['imagen_file']) && $presentacion['imagen_file']['error'] === UPLOAD_ERR_OK) {
                        $imagen = $presentacion['imagen_file'];
                        $nombreImagen = "producto_" . $id_producto . "_presentacion_" . ($key + 1) . "_" . uniqid() . "_" . basename($imagen['name']);
                        $rutaDestino = "../../public/imag/" . $nombreImagen;
                        if (!move_uploaded_file($imagen['tmp_name'], $rutaDestino)) {
                            throw new Exception("Error al subir la imagen de la presentación nueva " . ($key + 1));
                        }
                    }
                    $stmtP = $pdo->prepare("INSERT INTO presentacion_producto (id_producto, tamano, unidad, precio, stock, nombre_imagen, largo, ancho, alto, unidad_dimension) VALUES (:id_producto, :tamano, :unidad, :precio, :stock, :nombre_imagen, :largo, :ancho, :alto, :unidad_dimension)");
                    $stmtP->bindParam(":id_producto", $id_producto);
                    $stmtP->bindParam(":tamano", $presentacion['tamano']);
                    $stmtP->bindParam(":unidad", $presentacion['unidad']);
                    $stmtP->bindParam(":precio", $presentacion['precio']);
                    $stmtP->bindParam(":stock", $presentacion['stock']);
                    $stmtP->bindParam(":nombre_imagen", $nombreImagen);
                    $largo = !empty($presentacion['largo']) ? $presentacion['largo'] : null;
                    $ancho = !empty($presentacion['ancho']) ? $presentacion['ancho'] : null;
                    $alto = !empty($presentacion['alto']) ? $presentacion['alto'] : null;
                    $unidad_dimension = !empty($presentacion['unidad_dimension']) ? $presentacion['unidad_dimension'] : null;
                    $stmtP->bindParam(":largo", $largo);
                    $stmtP->bindParam(":ancho", $ancho);
                    $stmtP->bindParam(":alto", $alto);
                    $stmtP->bindParam(":unidad_dimension", $unidad_dimension);
                    $stmtP->execute();
                    if ($key === 0) {
                        $imagenPrincipal = $nombreImagen;
                    }
                }
            }

            // Eliminar presentaciones que ya no están
            $idsEliminar = array_diff($idsActuales, $idsEnviados);
            if (!empty($idsEliminar)) {
                $in = implode(',', array_fill(0, count($idsEliminar), '?'));
                $stmtDel = $pdo->prepare("DELETE FROM presentacion_producto WHERE id_presentacion IN ($in) AND id_producto = ?");
                foreach ($idsEliminar as $k => $idDel) {
                    $stmtDel->bindValue($k + 1, $idDel, PDO::PARAM_INT);
                }
                $stmtDel->bindValue(count($idsEliminar) + 1, $id_producto, PDO::PARAM_INT);
                $stmtDel->execute();
            }

            // Actualizar imagen principal del producto
            if ($imagenPrincipal) {
                $stmtUpdate = $pdo->prepare("UPDATE producto SET imagen = :imagen WHERE id_producto = :id_producto");
                $stmtUpdate->bindParam(":imagen", $imagenPrincipal, PDO::PARAM_STR);
                $stmtUpdate->bindParam(":id_producto", $id_producto, PDO::PARAM_INT);
                $stmtUpdate->execute();
            }

            $pdo->commit();
            return ["success" => true, "message" => "¡Producto y presentaciones actualizados exitosamente! 🎉"];
        } catch (Exception $e) {
            if (isset($pdo)) $pdo->rollBack();
            return ["success" => false, "message" => $e->getMessage()];
        }
    }
}

/**
 * Clase: ProductoCliente
 * Descripción: Clase para gestionar operaciones específicas de productos para clientes
 * Conexiones:
 * - Se conecta con: config/php/conexion.php
 * - Se utiliza en: controllers/php/tiendaController.php
 */
class ProductoCliente {
    protected $conn;

    public function __construct() {
        $this->conn = Conexion::conectar();
        if (!$this->conn) {
            die(json_encode(["error" => "No se pudo conectar a la base de datos."]));
        }
    }

    public function ejecutarConsulta($sql, $params = []) {
        $stmt = $this->conn->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key + 1, $value);
        }
        if (!$stmt->execute()) {
            error_log("Error en consulta SQL: " . implode(" - ", $stmt->errorInfo()));
            return false;
        }
        return $stmt;
    }

    // 📌 Obtener productos por categoría
    public function obtenerProductosPorCategoria($categoria) {
        $sql = "SELECT p.id_producto, p.nombre, p.descripcion, p.precio, p.stock, p.imagen 
                FROM producto p
                JOIN categoria c ON p.id_categoria = c.id_categoria
                WHERE c.nombre = ?";
        
        $stmt = $this->ejecutarConsulta($sql, [$categoria]);
        if (!$stmt) {
            return json_encode(["error" => "Error en la consulta SQL."]);
        }

        $productos = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $row['imagen'] = $row['imagen'] ? "../../public/imag/" . $row['imagen'] : "../../public/imagenes_P/default.jpeg";
            $productos[] = $row;
        }

        return empty($productos) ? json_encode(["error" => "No se encontraron productos para la categoría: " . $categoria]) : json_encode($productos);
    }
}
?>