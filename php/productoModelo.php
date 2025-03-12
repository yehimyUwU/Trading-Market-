<?php
include_once "conexion.php";

class ProductoModelo {

    public static function mdlRegistrarProducto($nombre, $categoria, $precio, $descripcion, $subcategoria, $stock) {
        $mensaje = array();

        try {
            $objRespuesta = Conexion::conectar()->prepare("INSERT INTO producto (nombre, descripcion, precio, stock, id_categoria) VALUES (:nombre, :descripcion, :precio, :stock, :id_categoria)");
            $objRespuesta->bindParam(":nombre", $nombre);
            $objRespuesta->bindParam(":descripcion", $descripcion);
            $objRespuesta->bindParam(":precio", $precio);
            $objRespuesta->bindParam(":stock", $stock);
            $objRespuesta->bindParam(":id_categoria", $categoria);
            if ($objRespuesta->execute()) {
                $mensaje = array("codigo" => "200", "mensaje" => "Producto registrado correctamente.");
            } else {
                $mensaje = array("codigo" => "401", "mensaje" => "Error. No fue posible registrar el producto.");
            }
            $objRespuesta = null;
        } catch (Exception $e) {
            $mensaje = array("codigo" => "401", "mensaje" => $e->getMessage());
        }
        return $mensaje;
    }

    public static function mdlListarProductos() {
        $mensaje = array();

        try {
            $objRespuesta = Conexion::conectar()->prepare("SELECT p.*, c.nombre AS nombre_categoria FROM producto p INNER JOIN categoria c ON p.id_categoria = c.id_categoria");
            $objRespuesta->execute();
            $listaProductos = $objRespuesta->fetchAll(PDO::FETCH_ASSOC);
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
        $mensaje = array();

        try {
            $objRespuesta = Conexion::conectar()->prepare("SELECT * FROM categoria");
            $objRespuesta->execute();
            $listaCategorias = $objRespuesta->fetchAll();
            $mensaje = array("codigo" => "200", "listaCategorias" => $listaCategorias);
            $objRespuesta = null;
        } catch (Exception $e) {
            $mensaje = array("codigo" => "401", "mensaje" => $e->getMessage());
        }

        return $mensaje;
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