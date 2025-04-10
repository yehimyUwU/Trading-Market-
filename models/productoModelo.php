
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