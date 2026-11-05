CREATE DATABASE IF NOT EXISTS cafe_portafolio CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE cafe_portafolio;
CREATE TABLE IF NOT EXISTS productos (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 nombre VARCHAR(100) NOT NULL,
 descripcion VARCHAR(255) NOT NULL,
 precio DECIMAL(9,2) NOT NULL,
 categoria ENUM('Bebidas','Postres','Comidas') NOT NULL DEFAULT 'Bebidas',
 activo TINYINT(1) NOT NULL DEFAULT 1,
 creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS mensajes (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 nombre VARCHAR(100) NOT NULL,
 correo VARCHAR(190) NOT NULL,
 mensaje TEXT NOT NULL,
 creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE IF NOT EXISTS administradores (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 usuario VARCHAR(80) NOT NULL UNIQUE,
 password_hash VARCHAR(255) NOT NULL
);
INSERT INTO productos (nombre, descripcion, precio, categoria) VALUES
('Latte de vainilla','Espresso doble, leche cremosa y un toque de vainilla.',3.50,'Bebidas'),
('Cold Brew','Café de extracción lenta servido con hielo.',3.25,'Bebidas'),
('Capuchino','Espresso aromático con espuma de leche.',3.00,'Bebidas'),
('Cheesecake de frutos rojos','Tarta suave con salsa de frutos rojos.',4.25,'Postres'),
('Croissant de almendra','Hojaldre horneado y relleno de crema de almendra.',2.75,'Postres'),
('Sándwich artesanal','Pan tostado, queso, vegetales y aderezo de la casa.',5.50,'Comidas');

CREATE TABLE IF NOT EXISTS pedidos (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 cliente_nombre VARCHAR(100) NOT NULL,
 cliente_telefono VARCHAR(25) NOT NULL,
 nota VARCHAR(500) NOT NULL DEFAULT '',
 total DECIMAL(10,2) NOT NULL,
 estado ENUM('Pendiente','Preparando','Listo','Entregado','Cancelado') NOT NULL DEFAULT 'Pendiente',
 creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
CREATE TABLE IF NOT EXISTS pedido_detalles (
 id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 pedido_id INT UNSIGNED NOT NULL,
 producto_id INT UNSIGNED NULL,
 producto_nombre VARCHAR(100) NOT NULL,
 precio_unitario DECIMAL(9,2) NOT NULL,
 cantidad INT UNSIGNED NOT NULL,
 subtotal DECIMAL(10,2) NOT NULL,
 CONSTRAINT fk_pedido FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE,
 CONSTRAINT fk_producto FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE SET NULL
) ENGINE=InnoDB;
