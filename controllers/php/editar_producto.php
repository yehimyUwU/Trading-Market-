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

}

if (isset($_POST["nombre"], $_POST["categoria"], $_POST["precio"], $_POST["descripcion"], $_POST["subcategoria"], $_POST["stock"], $_POST["id_producto"])) {

    $objProducto = new ProductoControl();
    $objProducto->nombre = $_POST["nombre"];
    $objProducto->categoria = $_POST["categoria"];
    $objProducto->precio = $_POST["precio"];
    $objProducto->descripcion = $_POST["descripcion"];
    $objProducto->subcategoria = $_POST["subcategoria"];
    $objProducto->stock = $_POST["stock"];
    $objProducto->id_producto = $_POST["id_producto"];
    
    // Depuración
    error_log("Datos recibidos para edición:");
    error_log("Producto ID: " . $objProducto->id_producto);
    error_log("Proveedor ID: " . $objProducto->id_proveedor);
    
    $objProducto->ctrEditarProducto();
}
