-- Crear la base de datos si no existe
CREATE DATABASE IF NOT EXISTS sistema_inventario;
USE sistema_inventario;

-- Crear la tabla de vendedores
CREATE TABLE IF NOT EXISTS vendedores (
    id_vendedor INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    imagen_perfil VARCHAR(255) DEFAULT 'default_profile.png',
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
    ultimo_acceso DATETIME DEFAULT CURRENT_TIMESTAMP,
    estado ENUM('activo', 'inactivo') DEFAULT 'activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertar un vendedor de ejemplo
INSERT INTO vendedores (nombre, apellido, email, password) 
VALUES ('Juan', 'Pérez', 'juan.perez@ejemplo.com', SHA2('password123', 256))
ON DUPLICATE KEY UPDATE email = email;

-- Crear la tabla de productos
CREATE TABLE IF NOT EXISTS productos (
    id_producto INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    categoria VARCHAR(50) NOT NULL,
    subcategoria VARCHAR(50) NOT NULL,
    imagen VARCHAR(255) NOT NULL,
    id_vendedor INT NOT NULL,
    estado ENUM('activo', 'inactivo', 'agotado') DEFAULT 'activo',
    fecha_creacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_modificacion DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_categoria (categoria),
    INDEX idx_estado (estado),
    INDEX idx_fecha_creacion (fecha_creacion),
    FOREIGN KEY (id_vendedor) REFERENCES vendedores(id_vendedor)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Corregir las categorías para que coincidan con las subcategorías
INSERT INTO categorias (nombre) VALUES
('Electrónica'),
('Hogar y Jardín'),
('Moda y Accesorios'),
('Deportes y Fitness'),
('Otros');

-- Corregir la última sentencia INSERT de subcategorías
INSERT INTO subcategorias (id_categoria, nombre) VALUES
(1, 'Vehiculos'),
(1, 'Supermercado'),
(1, 'Tecnologia'),
(1, 'Electrodomesticos'),
(2, 'Herramientas'),
(2, 'Belleza y cuidado personal'),
(2, 'Accesorios de vehiculos'),
(2, 'Construccion'),
(3, 'Inmuebles'),
(3, 'Moda'),
(3, 'Juegos y Juguetes'),
(3, 'Bebes'),
(4, 'Fútbol'),
(4, 'Productos sustentables'),
(4, 'Industria y Oficina'),
(4, 'Deportes y Fitness'),
(5, 'Salud y equipo Medico'),
(5, 'Animales y Mascotas'),
(5, 'Antiguedades y Colecciones'),
(5, 'Arte, Papeleria y Merceria'); 

SELECT 
    p.id_producto,
    p.nombre AS nombre_producto,
    p.descripcion,
    p.precio,
    p.imagen,
    p.cantidad_stock,
    p.estado,
    c.nombre AS categoria,
    s.nombre AS subcategoria
FROM productos p
INNER JOIN categorias c ON p.id_categoria = c.id_categoria
INNER JOIN subcategorias s ON p.id_subcategoria = s.id_subcategoria
WHERE p.estado = 'activo'
ORDER BY p.fecha_creacion DESC; 

INSERT INTO productos (
    id_usuario,
    id_categoria,
    id_subcategoria,
    referencia,
    nombre,
    descripcion,
    tipo,
    cantidad_stock,
    precio,
    costo,
    imagen
) VALUES (
    ?, -- id_usuario del usuario logueado
    ?, -- id_categoria seleccionada en el formulario
    ?, -- id_subcategoria seleccionada en el formulario
    ?, -- referencia única generada
    ?, -- nombre del producto
    ?, -- descripción
    ?, -- tipo (producto o servicio)
    ?, -- cantidad_stock
    ?, -- precio
    ?, -- costo
    ?  -- ruta de la imagen guardada
); 

-- Para obtener las categorías
SELECT id_categoria, nombre 
FROM categorias 
WHERE estado = 'activo';

-- Para obtener las subcategorías de una categoría específica
SELECT id_subcategoria, nombre 
FROM subcategorias 
WHERE id_categoria = ?; 