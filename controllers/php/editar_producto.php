
<?php
/**
 * Archivo: editar_producto.php
 * Descripción: Controlador para la edición de productos en el sistema
 * Conexiones:
 * - Se conecta con: models/productoModelo.php (para el modelo de productos)
 * - Se utiliza en: views/html/Misproductos.php (para editar productos)
 * - Interactúa con la tabla: producto
 * Flujo general:
 * 1. Recibe los datos del producto a editar vía POST
 * 2. Procesa la imagen si se ha subido una nueva
 * 3. Llama al modelo para actualizar el producto
 * 4. Retorna una respuesta JSON con el resultado
 */

require "../../models/productoModelo.php";

/**
 * Clase: ProductoControl
 * Descripción: Controlador para gestionar las operaciones de productos
 * Métodos:
 * - ctrEditarProducto(): Maneja la edición de productos existentes
 */
class ProductoControl {
    // Propiedades públicas para almacenar los datos del producto
    public $idProducto;
    public $nombre;
    public $categoria;
    public $precio;
    public $descripcion;
    public $subcategoria;
    public $stock;

    /**
     * Método: ctrEditarProducto
     * Descripción: Procesa la edición de un producto existente
     * Flujo:
     * 1. Verifica si se ha subido una nueva imagen
     * 2. Llama al modelo para actualizar el producto
     * 3. Retorna la respuesta en formato JSON
     */
    public function ctrEditarProducto() {
        // Verificar si se ha subido una nueva imagen
        $imagen = isset($_FILES['imagen']) && $_FILES['imagen']['error'] !== UPLOAD_ERR_NO_FILE ? $_FILES['imagen'] : null;

        // Llamar al modelo para actualizar el producto
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

/**
 * Procesamiento de la solicitud POST
 * Descripción: Maneja la recepción de datos del formulario de edición
 * Flujo:
 * 1. Verifica que todos los campos requeridos estén presentes
 * 2. Crea una instancia del controlador
 * 3. Asigna los valores recibidos
 * 4. Ejecuta el método de edición
 */
if (isset($_POST["nombre"], $_POST["categoria"], $_POST["precio"], $_POST["descripcion"], $_POST["subcategoria"], $_POST["stock"], $_POST["id_producto"])) {

    $objProducto = new ProductoControl();
    $objProducto->nombre = $_POST["nombre"];
    $objProducto->categoria = $_POST["categoria"];
    $objProducto->precio = $_POST["precio"];
    $objProducto->descripcion = $_POST["descripcion"];
    $objProducto->subcategoria = $_POST["subcategoria"];
    $objProducto->stock = $_POST["stock"];
    $objProducto->id_producto = $_POST["id_producto"];
    
    // Depuración: Registrar los datos recibidos
    error_log("Datos recibidos para edición:");
    error_log("Producto ID: " . $objProducto->id_producto);
    error_log("Proveedor ID: " . $objProducto->id_proveedor);
    
    $objProducto->ctrEditarProducto();
}
