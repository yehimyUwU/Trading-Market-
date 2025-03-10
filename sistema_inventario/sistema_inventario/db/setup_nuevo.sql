-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS subirProductos_db;
USE subirProductos_db;

-- Tabla de usuarios
CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre varchar(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de categorías
CREATE TABLE categorias (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    descripcion TEXT,
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
('Vehiculos', 'Vehículos y accesorios'),
('Supermercado', 'Productos de consumo diario'),
('Tecnologia', 'Productos tecnológicos'),
('Electrodomesticos', 'Aparatos para el hogar'),
('Herramientas', 'Herramientas y equipamiento'),
('Belleza y cuidado personal', 'Productos de belleza y cuidado'),
('Accesorios de vehiculos', 'Accesorios para vehículos'),
('Construccion', 'Materiales y herramientas de construcción'),
('Inmuebles', 'Propiedades y bienes raíces'),
('Moda', 'Ropa y accesorios'),
('Juegos y Juguetes', 'Entretenimiento'),
('Bebes', 'Productos para bebés'),
('Deportes y Fitness', 'Artículos deportivos'),
('Salud y equipo Medico', 'Equipamiento médico'),
('Animales y Mascotas', 'Productos para mascotas'),
('Arte y Papeleria', 'Artículos de arte y oficina');

-- Insertar un usuario de prueba
INSERT INTO usuarios (nombre, email, password) VALUES 
('Vendedor Demo', 'vendedor@ejemplo.com', SHA2('password123', 256));

-- Modificar la tabla productos
ALTER TABLE productos
ADD COLUMN nuevo_campo tipo_dato,
MODIFY COLUMN campo_existente nuevo_tipo_dato,
DROP COLUMN campo_a_eliminar;

DROP TABLE IF EXISTS nombre_viejo;
CREATE TABLE nombre_nuevo (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nuevo_campo1 VARCHAR(255),
    nuevo_campo2 INT,
    /* ... otros campos ... */
);

RENAME TABLE nombre_viejo TO nombre_nuevo; 