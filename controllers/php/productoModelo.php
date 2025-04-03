<?php
include_once "../../config/php/conexion.php";

class ProductoModelo {

    public static function mdlRegistrarProducto($nombre, $categoria, $precio, $descripcion, $subcategoria, $stock, $imagen) {
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
            $stmt = Conexion::conectar()->prepare("INSERT INTO producto (nombre, id_categoria, id_subcategoria, descripcion, precio, stock, imagen) VALUES (:nombre, :categoria, :subcategoria, :descripcion, :precio, :stock, :imagen)");
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
                $producto['imagen'] = $producto['imagen'] ? "../imag/" . $producto['imagen'] : "../../public/imagenes_P/default.jpeg";
            }

            $mensaje = array("codigo" => "200", "success" => true, "listaProductos" => $listaProductos);
            $objRespuesta = null;
        } catch (Exception $e) {
            $mensaje = array("codigo" => "401", "success" => false, "mensaje" => $e->getMessage());
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

    public static function mdlEditarProducto($nuevoNombre, $nuevaCategoria, $nuevoPrecio, $idProducto) {
        $mensaje = array();

        try {
            $objRespuesta = Conexion::conectar()->prepare("UPDATE producto SET nombre = :nuevoNombre, precio = :nuevoPrecio, id_categoria = :nuevaCategoria WHERE id_producto = :idProducto");
            $objRespuesta->bindParam(":nuevoNombre", $nuevoNombre);
            $objRespuesta->bindParam(":nuevoPrecio", $nuevoPrecio);
            $objRespuesta->bindParam(":nuevaCategoria", $nuevaCategoria);
            $objRespuesta->bindParam(":idProducto", $idProducto);
            if ($objRespuesta->execute()) {
                $mensaje = array("codigo" => "200", "mensaje" => "Producto editado correctamente.");
            } else {
                $mensaje = array("codigo" => "401", "mensaje" => "Error. No fue posible editar el producto.");
            }
            $objRespuesta = null;
        } catch (Exception $e) {
            $mensaje = array("codigo" => "401", "mensaje" => $e->getMessage());
        }

        return $mensaje;
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
?>