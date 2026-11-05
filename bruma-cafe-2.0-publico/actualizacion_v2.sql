USE cafe_portafolio;

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
