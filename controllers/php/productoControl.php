<?php

require "../../models/productoModelo.php";

class ProductoControl {

    public $idProducto;
    public $nombre;
    public $categoria;
    public $precio;
    public $descripcion;
    public $subcategoria;
    public $stock;

    public $ids;

    public $nuevoNombre;
    public $nuevaCategoria;
    public $nuevoPrecio;

    public function ctrRegistrarProducto() {
        $imagen = $_FILES['imagen'];
        $objRespuesta = ProductoModelo::mdlRegistrarProducto(
            $this->nombre,
            $this->categoria,
            $this->precio,
            $this->descripcion,
            $this->subcategoria,
            $this->stock,
            $imagen
        );
        echo json_encode($objRespuesta);
    }

    public function ctrListarProductos() {
        $objRespuesta = ProductoModelo::mdlListarProductos();
        echo json_encode($objRespuesta);
    }

    public function ctrEliminarProducto() {
        $objRespuesta = ProductoModelo::mdlEliminarProducto($this->idProducto);
        echo json_encode($objRespuesta);
    }

    public function ctrEditarProducto() {
        $objRespuesta = ProductoModelo::mdlEditarProducto($this->nuevoNombre, $this->nuevaCategoria, $this->nuevoPrecio, $this->idProducto);
        echo json_encode($objRespuesta);
    }

    public function ctrListarCategorias() {
        $objRespuesta = ProductoModelo::mdlListarCategorias();
        echo json_encode($objRespuesta);
    }

    public function ctrEliminarProductos() {
        $objRespuesta = ProductoModelo::mdlEliminarProductos($this->ids);
        echo json_encode($objRespuesta);
    }

    public function ctrreturnUsuarios() {
        $objRespuesta = ProductoModelo::mdlreturnUsuarios();
        echo json_encode($objRespuesta);   
    }


}

if (isset($_POST["nombre"], $_POST["categoria"], $_POST["precio"], $_POST["descripcion"], $_POST["subcategoria"], $_POST["stock"], $_FILES["imagen"])) {
    $objProducto = new ProductoControl();
    $objProducto->nombre = $_POST["nombre"];
    $objProducto->categoria = $_POST["categoria"];
    $objProducto->precio = $_POST["precio"];
    $objProducto->descripcion = $_POST["descripcion"];
    $objProducto->subcategoria = $_POST["subcategoria"];
    $objProducto->stock = $_POST["stock"];

    // Depuración: Verificar los datos recibidos
    error_log("Datos recibidos en el backend:");
    error_log("Nombre: " . $objProducto->nombre);
    error_log("Categoría: " . $objProducto->categoria);
    error_log("Subcategoría: " . $objProducto->subcategoria);
    error_log("Precio: " . $objProducto->precio);
    error_log("Descripción: " . $objProducto->descripcion);
    error_log("Stock: " . $objProducto->stock);

    // Agregar manejo de imagen
    $objProducto->ctrRegistrarProducto();
}

if (isset($_POST["listarProductos"]) && $_POST["listarProductos"] == "ok") {
    $objProducto = new ProductoControl();
    $objProducto->ctrListarProductos();
}

if (isset($_POST["eliminarProducto"])) {
    $objProducto = new ProductoControl();
    $objProducto->idProducto = $_POST["eliminarProducto"];
    $objProducto->ctrEliminarProducto();
}

if (isset($_POST["nuevoNombre"], $_POST["nuevaCategoria"], $_POST["nuevoPrecio"], $_POST["producto"])) {
    $objEditarProducto = new ProductoControl();
    $objEditarProducto->nuevoNombre = $_POST["nuevoNombre"];
    $objEditarProducto->nuevaCategoria = $_POST["nuevaCategoria"];
    $objEditarProducto->nuevoPrecio = $_POST["nuevoPrecio"];
    $objEditarProducto->idProducto = $_POST["producto"];
    $objEditarProducto->ctrEditarProducto();
}

if (isset($_POST["listarCategorias"]) && $_POST["listarCategorias"] == "ok") {
    $objCategoria = new ProductoControl();
    $objCategoria->ctrListarCategorias();
}

if (isset($_POST["eliminarProductos"])) {
    $objProducto = new ProductoControl();
    $objProducto->ids = $_POST["eliminarProductos"];
    $objProducto->ctrEliminarProductos();
}

if (isset($_POST["usuarios"]) && $_POST["usuarios"] == "ok") {
    $objUsuario = new ProductoControl();
    $objUsuario->ctrreturnUsuarios();
}

if (isset($_POST["ProductosEliminados"]) && $_POST["ProductosEliminados"] == "ok") {
    $objProducto = new ProductoControl();
    $objProducto->ctrPapelera();
}

if (isset($_POST["subirProductos"]) && $_POST["subirProductos"] == "ok") {
    $objProducto = new ProductoControl();
    $objProducto->ctrSubirExcel();
}
?>