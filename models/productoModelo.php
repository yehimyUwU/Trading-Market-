<?php
require_once '../../config/php/conexion.php';

class ProductoModel {
    private $conn;

    public function __construct() {
        $this->conn = Conexion::conectar();
    }

    public function registrar($nombre, $categoria, $precio, $descripcion, $subcategoria, $stock, $imagen) {
        try {
            // Verificar si la categoría existe
            $verificarCategoria = $this->conn->prepare("SELECT COUNT(*) FROM categoria WHERE id_categoria = ?");
            $verificarCategoria->execute([$categoria]);
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
            $stmt = $this->conn->prepare("INSERT INTO producto (nombre, id_categoria, id_subcategoria, descripcion, precio, stock, imagen) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bindParam(1, $nombre);
            $stmt->bindParam(2, $categoria);
            $stmt->bindParam(3, $subcategoria);
            $stmt->bindParam(4, $descripcion);
            $stmt->bindParam(5, $precio);
            $stmt->bindParam(6, $stock);
            $stmt->bindParam(7, $nombreImagen);

            if ($stmt->execute()) {
                return ["success" => true, "message" => "Producto registrado correctamente."];
            } else {
                return ["success" => false, "message" => "Error al registrar el producto. Verifica los datos enviados."];
            }
        } catch (PDOException $e) {
            return ["success" => false, "message" => "Error de base de datos: " . $e->getMessage()];
        }
    }

    public function listar() {
        try {
            $stmt = $this->conn->prepare("
                SELECT p.*, c.nombre AS nombre_categoria 
                FROM producto p 
                INNER JOIN categoria c ON p.id_categoria = c.id_categoria
            ");
            $stmt->execute();
            $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($productos as &$producto) {
                $producto['imagen'] = $producto['imagen'] ? "../../public/imag/" . $producto['imagen'] : "../../public/imagenes_P/default.jpeg";
            }

            return ["success" => true, "productos" => $productos];
        } catch (Exception $e) {
            return ["success" => false, "message" => $e->getMessage()];
        }
    }

    public function editar($id_producto, $nombre, $descripcion, $precio, $stock) {
        try {
            $stmt = $this->conn->prepare("UPDATE productos SET nombre = ?, descripcion = ?, precio = ?, stock = ? WHERE id_producto = ?");
            $success = $stmt->execute([$nombre, $descripcion, $precio, $stock, $id_producto]);

            return [
                'success' => $success,
                'message' => $success ? 'Producto actualizado correctamente.' : 'Error al actualizar el producto.'
            ];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()];
        }
    }

    public function obtenerProductos($busqueda = '', $ordenarPor = 'reciente') {
        $sql = "SELECT * FROM productos WHERE 1=1";
        $params = [];

        if (!empty($busqueda)) {
            $sql .= " AND (nombre LIKE ? OR descripcion LIKE ?)";
            $params[] = "%{$busqueda}%";
            $params[] = "%{$busqueda}%";
        }

        switch ($ordenarPor) {
            case 'precio-asc': $sql .= " ORDER BY precio ASC"; break;
            case 'precio-desc': $sql .= " ORDER BY precio DESC"; break;
            case 'antiguo': $sql .= " ORDER BY fecha_creacion ASC"; break;
            case 'reciente':
            default: $sql .= " ORDER BY fecha_creacion DESC"; break;
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($productos)) {
            return ['success' => false, 'message' => 'No se encontraron productos con los filtros aplicados.'];
        }

        foreach ($productos as &$producto) {
            $producto['imagen_url'] = '../../public/imag/' . $producto['imagen'];
            $producto['precio_formato'] = number_format($producto['precio'], 2);
        }

        return ['success' => true, 'productos' => $productos];
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