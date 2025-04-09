<?php
include_once "../../controllers/php/productoControl.php";

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $objProducto = new ProductoControl();
    $objProducto->ctrListarProductos();
}
?>