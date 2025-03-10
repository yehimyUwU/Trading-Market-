<?php
include_once "productoControl.php";

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $objProducto = new ProductoControl();
    $objProducto->ctrListarProductos();
}
?>
