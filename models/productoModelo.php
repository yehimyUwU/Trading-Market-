<?php
require '../../config/php/conexion.php';

class ProductoModelo {

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

    public static function mdlEliminarProducto($idProducto) {
        $mensaje = array();

        try {
            $objRespuesta = Conexion::conectar()->prepare("DELETE FROM producto WHERE id_producto = :idProducto");
            $objRespuesta->bindParam(":idProducto", $idProducto);
            if ($objRespuesta->execute()) {
                $mensaje = array("codigo" => "200", "mensaje" => "Producto eliminado correctamente.");
            } else {
                $mensaje = array("codigo" => "401", "mensaje" => "Error. No fue posible eliminar el producto.");
            }
            $objRespuesta = null;
        } catch (Exception $e) {
            $mensaje = array("codigo" => "401", "mensaje" => $e->getMessage());
        }

        return $mensaje;
    }

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