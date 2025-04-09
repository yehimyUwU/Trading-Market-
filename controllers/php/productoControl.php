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
    public $id_proveedor; // Nueva propiedad

    public function ctrRegistrarProducto() {
        $imagen = $_FILES['imagen'];
        $objRespuesta = ProductoModelo::mdlRegistrarProducto(
            $this->nombre,
            $this->categoria,
            $this->precio,
            $this->descripcion,
            $this->subcategoria,
            $this->stock,
            $this->id_proveedor, // Pasamos el ID del proveedor
            $imagen
        );
        echo json_encode($objRespuesta);
    }
    
    public function ctrListarTodosProductos() {
        $objRespuesta = ProductoModelo::mdlListarProductos();
        echo json_encode($objRespuesta);
    }

    public function ctrListarProductos() {
        $respuesta = ProductoModelo::mdlListarProductosPorProveedor($this->id_proveedor);
        echo json_encode($respuesta);
    }

    public function ctrEliminarProducto() {
        $objRespuesta = ProductoModelo::mdlEliminarProducto($this->idProducto);
        echo json_encode($objRespuesta);
    }

    public function ctrEditarProducto() {
        $imagen = isset($_FILES['imagen']) && $_FILES['imagen']['error'] !== UPLOAD_ERR_NO_FILE ? $_FILES['imagen'] : null;

        $objRespuesta = ProductoModelo::mdlEditarProducto(
            $this->id_producto,
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
if (isset($_POST["nombre"], $_POST["categoria"], $_POST["precio"], $_POST["descripcion"], $_POST["subcategoria"], $_POST["stock"], $_POST["id_proveedor"], $_FILES["imagen"])) {

    $objProducto = new ProductoControl();
    $objProducto->nombre = $_POST["nombre"];
    $objProducto->categoria = $_POST["categoria"];
    $objProducto->precio = $_POST["precio"];
    $objProducto->descripcion = $_POST["descripcion"];
    $objProducto->subcategoria = $_POST["subcategoria"];
    $objProducto->stock = $_POST["stock"];
    $objProducto->id_proveedor = $_POST["id_proveedor"]; // Obtenemos el ID del proveedor de la sesión

    // Depuración
    error_log("Datos recibidos:");
    error_log("Proveedor ID: " . $objProducto->id_proveedor);
    
    $objProducto->ctrRegistrarProducto();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $objProducto = new ProductoControl();
    $objProducto->ctrListarTodosProductos();
}

if (isset($_POST["id_proveedor"])) {
    $objProducto = new ProductoControl();
    $objProducto->id_proveedor = $_POST["id_proveedor"]; // Obtenemos el ID del proveedor de la sesión
    $objProducto->ctrListarProductos();
}

if (isset($_POST["eliminarProducto"])) {
    $objProducto = new ProductoControl();
    $objProducto->idProducto = $_POST["eliminarProducto"];
    $objProducto->ctrEliminarProducto();
}

if (isset($_POST["nombre"], $_POST["categoria"], $_POST["precio"], $_POST["descripcion"], $_POST["subcategoria"], $_POST["stock"], $_POST["id_proveedor"], $_POST["id_producto"])) {

    $objProducto = new ProductoControl();
    $objProducto->nombre = $_POST["nombre"];
    $objProducto->categoria = $_POST["categoria"];
    $objProducto->precio = $_POST["precio"];
    $objProducto->descripcion = $_POST["descripcion"];
    $objProducto->subcategoria = $_POST["subcategoria"];
    $objProducto->stock = $_POST["stock"];
    $objProducto->id_proveedor = $_POST["id_proveedor"]; 
    $objProducto->id_producto = $_POST["id_producto"];
    
    // Depuración
    error_log("Datos recibidos para edición:");
    error_log("Producto ID: " . $objProducto->id_producto);
    error_log("Proveedor ID: " . $objProducto->id_proveedor);
    
    $objProducto->ctrEditarProducto();
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