<?php

require "../../models/productoModelo.php";

header('Content-Type: application/json');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

    public $presentaciones;

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

    // Nueva función para registrar producto con presentaciones
    public function ctrRegistrarProductoConPresentaciones() {
        error_log('Entrando a flujo de presentaciones con datos: ' . json_encode($this->presentaciones));
        $objRespuesta = ProductoModelo::mdlRegistrarProductoConPresentaciones(
            $this->nombre,
            $this->categoria,
            $this->descripcion,
            $this->subcategoria,
            $this->id_proveedor,
            $this->presentaciones // Usar el array reconstruido
        );
        echo json_encode($objRespuesta);
    }

    // Nueva función para listar productos con presentaciones
    public function ctrListarProductosConPresentaciones() {
        $respuesta = ProductoModelo::mdlListarProductosConPresentaciones($this->id_proveedor);
        echo json_encode($respuesta);
    }

}

// Log antes del if principal
file_put_contents(__DIR__ . '/debug_antes_if.txt', print_r($_POST, true) . PHP_EOL . print_r($_FILES, true));

// --- FLUJO DE CREAR PRODUCTO CON PRESENTACIONES (PRIORIDAD) ---
if (
    isset($_POST["accion"]) && $_POST["accion"] === "crear" &&
    isset($_POST["nombre"], $_POST["categoria"], $_POST["descripcion"], $_POST["subcategoria"], $_POST["id_proveedor"])
) {
    // Log dentro del if principal
    file_put_contents(__DIR__ . '/debug_dentro_if.txt', 'Entró al if de crear producto con presentaciones');
    try {
        file_put_contents(__DIR__ . '/debug_post.txt', print_r($_POST, true));
        file_put_contents(__DIR__ . '/debug_files.txt', print_r($_FILES, true));

        // Procesar campos de texto y asociar imágenes (soporta ambos formatos: plano y anidado)
        $presentaciones = [];
        // 1. Procesar campos de texto
        if (isset($_POST['presentaciones']) && is_array($_POST['presentaciones'])) {
            foreach ($_POST['presentaciones'] as $idx => $datos) {
                $presentaciones[$idx] = $datos;
                // Renombrar 'tamanio' o 'tamaño' a 'tamano'
                if (isset($presentaciones[$idx]['tamanio'])) {
                    $presentaciones[$idx]['tamano'] = $presentaciones[$idx]['tamanio'];
                    unset($presentaciones[$idx]['tamanio']);
                }
                if (isset($presentaciones[$idx]['tamaño'])) {
                    $presentaciones[$idx]['tamano'] = $presentaciones[$idx]['tamaño'];
                    unset($presentaciones[$idx]['tamaño']);
                }
            }
        }
        // 2. Asociar imágenes (soporta ambos formatos)
        foreach ($_FILES as $key => $fileArr) {
            // Formato plano: presentaciones_imagen_0
            if (preg_match('/^presentaciones_imagen_(\d+)$/', $key, $matches)) {
                $idx = $matches[1];
                $presentaciones[$idx]['imagen_file'] = $fileArr;
            }
            // Formato anidado: presentaciones[name][0][imagen]
            if ($key === 'presentaciones' && isset($fileArr['name'])) {
                foreach ($fileArr['name'] as $idx => $arr) {
                    if (isset($arr['imagen'])) {
                        $presentaciones[$idx]['imagen_file'] = [
                            'name' => $fileArr['name'][$idx]['imagen'],
                            'type' => $fileArr['type'][$idx]['imagen'],
                            'tmp_name' => $fileArr['tmp_name'][$idx]['imagen'],
                            'error' => $fileArr['error'][$idx]['imagen'],
                            'size' => $fileArr['size'][$idx]['imagen'],
                        ];
                    }
                }
            }
        }
        $presentaciones = array_values($presentaciones);
        file_put_contents(__DIR__ . '/debug_presentaciones.txt', print_r($presentaciones, true));

        require_once '../../models/productoModelo.php';
        $resultado = ProductoModelo::mdlRegistrarProductoConPresentaciones(
            $_POST["nombre"],
            $_POST["categoria"],
            $_POST["descripcion"],
            $_POST["subcategoria"],
            $_POST["id_proveedor"],
            $presentaciones
        );
        echo json_encode($resultado);
        exit;
    } catch (Throwable $e) {
        echo json_encode(['success' => false, 'message' => 'Error fatal: ' . $e->getMessage()]);
        exit;
    }
}

// Nuevo endpoint: obtener producto con presentaciones por id
if (isset($_POST['getProductoConPresentaciones']) && isset($_POST['id_producto'])) {
    require_once '../../models/productoModelo.php';
    $id_producto = $_POST['id_producto'];
    $respuesta = ProductoModelo::mdlObtenerProductoConPresentaciones($id_producto);
    echo json_encode($respuesta);
    exit;
}

// --- FLUJO DE EDITAR PRODUCTO CON PRESENTACIONES ---
if (
    isset($_POST["accion"]) && $_POST["accion"] === "editar" &&
    isset($_POST["id_producto"], $_POST["nombre"], $_POST["categoria"], $_POST["descripcion"], $_POST["subcategoria"], $_POST["id_proveedor"]) 
) {
    try {
        $presentaciones = [];
        if (isset($_POST['presentaciones']) && is_array($_POST['presentaciones'])) {
            foreach ($_POST['presentaciones'] as $idx => $datos) {
                $presentaciones[$idx] = $datos;
                if (isset($presentaciones[$idx]['tamanio'])) {
                    $presentaciones[$idx]['tamano'] = $presentaciones[$idx]['tamanio'];
                    unset($presentaciones[$idx]['tamanio']);
                }
                if (isset($presentaciones[$idx]['tamaño'])) {
                    $presentaciones[$idx]['tamano'] = $presentaciones[$idx]['tamaño'];
                    unset($presentaciones[$idx]['tamaño']);
                }
            }
        }
        foreach ($_FILES as $key => $fileArr) {
            if (preg_match('/^presentaciones_imagen_(\d+)$/', $key, $matches)) {
                $idx = $matches[1];
                $presentaciones[$idx]['imagen_file'] = $fileArr;
            }
            if ($key === 'presentaciones' && isset($fileArr['name'])) {
                foreach ($fileArr['name'] as $idx => $arr) {
                    if (isset($arr['imagen'])) {
                        $presentaciones[$idx]['imagen_file'] = [
                            'name' => $fileArr['name'][$idx]['imagen'],
                            'type' => $fileArr['type'][$idx]['imagen'],
                            'tmp_name' => $fileArr['tmp_name'][$idx]['imagen'],
                            'error' => $fileArr['error'][$idx]['imagen'],
                            'size' => $fileArr['size'][$idx]['imagen'],
                        ];
                    }
                }
            }
        }
        $presentaciones = array_values($presentaciones);
        require_once '../../models/productoModelo.php';
        $resultado = ProductoModelo::mdlEditarProductoConPresentaciones(
            $_POST["id_producto"],
            $_POST["nombre"],
            $_POST["categoria"],
            $_POST["descripcion"],
            $_POST["subcategoria"],
            $_POST["id_proveedor"],
            $presentaciones
        );
        echo json_encode($resultado);
        exit;
    } catch (Throwable $e) {
        echo json_encode(['success' => false, 'message' => 'Error fatal: ' . $e->getMessage()]);
        exit;
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
    $objProducto->id_proveedor = $_POST["id_proveedor"];
    // Depuración
    error_log("Datos recibidos:");
    error_log("Proveedor ID: " . $objProducto->id_proveedor);
    $objProducto->ctrRegistrarProducto();
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $objProducto = new ProductoControl();
    $objProducto->ctrListarTodosProductos();
    exit;
}

if (isset($_POST["id_proveedor"])) {
    $objProducto = new ProductoControl();
    $objProducto->id_proveedor = $_POST["id_proveedor"];
    $objProducto->ctrListarProductos();
    exit;
}

if (isset($_POST["eliminarProducto"])) {
    $objProducto = new ProductoControl();
    $objProducto->idProducto = $_POST["eliminarProducto"];
    $objProducto->ctrEliminarProducto();
    exit;
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
    exit;
}

if (isset($_POST["listarCategorias"]) && $_POST["listarCategorias"] == "ok") {
    $objCategoria = new ProductoControl();
    $objCategoria->ctrListarCategorias();
    exit;
}

if (isset($_POST["eliminarProductos"])) {
    $objProducto = new ProductoControl();
    $objProducto->ids = $_POST["eliminarProductos"];
    $objProducto->ctrEliminarProductos();
    exit;
}

if (isset($_POST["usuarios"]) && $_POST["usuarios"] == "ok") {
    $objUsuario = new ProductoControl();
    $objUsuario->ctrreturnUsuarios();
    exit;
}

if (isset($_POST["ProductosEliminados"]) && $_POST["ProductosEliminados"] == "ok") {
    $objProducto = new ProductoControl();
    $objProducto->ctrPapelera();
    exit;
}

if (isset($_POST["subirProductos"]) && $_POST["subirProductos"] == "ok") {
    $objProducto = new ProductoControl();
    $objProducto->ctrSubirExcel();
    exit;
}

// Nuevo manejo para listar productos con presentaciones
if (isset($_POST["listarConPresentaciones"]) && $_POST["listarConPresentaciones"] == "ok" && isset($_POST["id_proveedor"])) {
    $objProducto = new ProductoControl();
    $objProducto->id_proveedor = $_POST["id_proveedor"];
    $objProducto->ctrListarProductosConPresentaciones();
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !headers_sent()) {
    echo json_encode(['success' => false, 'message' => 'Petición no reconocida o datos incompletos.']);
    exit;
}
?>