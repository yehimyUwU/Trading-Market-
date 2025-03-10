CREATE DATABASE IF NOT EXISTS  subirProductos_db;

USE  subirProductos_db;

CREATE TABLE producto_servicio(
	id INT AUTO_INCREMENT PRIMARY KEY,
	nombre varchar(100) NOT NULL,
	email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de categorías
CREATE TABLE categorias (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    estado ENUM('activo', 'inactivo') DEFAULT 'activo'
);

-- Tabla de subcategorías
CREATE TABLE subcategorias (
    id_subcategoria INT AUTO_INCREMENT PRIMARY KEY,
    id_categoria INT NOT NULL,
    nombre VARCHAR(50) NOT NULL,
    descripcion TEXT,
    FOREIGN KEY (id_categoria) REFERENCES categorias(id_categoria)
);

-- Tabla principal de productos
CREATE TABLE productos (
    id_producto INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    id_categoria INT NOT NULL,
    id_subcategoria INT NOT NULL,
    referencia VARCHAR(50) UNIQUE NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT NOT NULL,
    tipo ENUM('producto', 'servicio') NOT NULL,
    cantidad_stock INT NOT NULL DEFAULT 0,
    precio DECIMAL(10,2) NOT NULL,
    costo DECIMAL(10,2) NOT NULL,
    imagen VARCHAR(255) NOT NULL,
    estado ENUM('activo', 'inactivo', 'agotado') DEFAULT 'activo',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario),
    FOREIGN KEY (id_categoria) REFERENCES categorias(id_categoria),
    FOREIGN KEY (id_subcategoria) REFERENCES subcategorias(id_subcategoria)
);

-- Tabla para el historial de precios
CREATE TABLE historial_precios (
    id_historial INT AUTO_INCREMENT PRIMARY KEY,
    id_producto INT NOT NULL,
    precio_anterior DECIMAL(10,2) NOT NULL,
    precio_nuevo DECIMAL(10,2) NOT NULL,
    fecha_cambio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_producto) REFERENCES productos(id_producto)
);

-- Tabla para el historial de stock
CREATE TABLE historial_stock (
    id_historial INT AUTO_INCREMENT PRIMARY KEY,
    id_producto INT NOT NULL,
    cantidad_anterior INT NOT NULL,
    cantidad_nueva INT NOT NULL,
    tipo_movimiento ENUM('entrada', 'salida', 'ajuste') NOT NULL,
    fecha_movimiento TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_producto) REFERENCES productos(id_producto)
);

-- Insertar categorías predefinidas
INSERT INTO categorias (nombre, descripcion) VALUES
('Electrónica', 'Productos electrónicos y tecnológicos'),
('Ropa', 'Vestimenta y accesorios'),
('Hogar', 'Artículos para el hogar'),
('Deportes', 'Artículos deportivos y fitness');

-- Insertar subcategorías predefinidas
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
(5, 'Arte, Papeleria y Merceria'),
);

