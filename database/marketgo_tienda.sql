
--  este script elimina y vuelve a crear Tienda_Marketgo.
--
-- USUARIOS DEMO (contraseña: password)
-- ADM001 / password
-- EMP001 / password
-- CLI001 / password
-- =============================================================
CREATE DATABASE  marketgo_tienda;
USE marketgo_tienda;

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- 1. USUARIOS
DROP TABLE IF EXISTS usuarios;
CREATE TABLE usuarios (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    identificacion VARCHAR(30) NOT NULL UNIQUE,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    correo VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol ENUM('ADMIN','EMPLEADO','CLIENTE') NOT NULL DEFAULT 'CLIENTE',
    estado ENUM('ACTIVO','INACTIVO') NOT NULL DEFAULT 'ACTIVO',
    fecha_registro TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_usuarios_rol (rol),
    INDEX idx_usuarios_estado (estado)
) ENGINE=InnoDB;

-- 2. CATEGORIAS
DROP TABLE IF EXISTS categorias;
-- Historial separado de registros de usuarios
CREATE TABLE registro_usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT UNSIGNED NOT NULL,
    fecha_registro DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    ip VARCHAR(45) NULL,
    estado ENUM('EXITOSO','ANULADO') NOT NULL DEFAULT 'EXITOSO',
    CONSTRAINT fk_registro_usuario
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE categorias (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(20) NOT NULL UNIQUE,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    descripcion VARCHAR(255) NULL,
    estado ENUM('ACTIVA','INACTIVA') NOT NULL DEFAULT 'ACTIVA',
    fecha_registro TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_categorias_estado (estado)
) ENGINE=InnoDB;

CREATE TABLE proveedores (
    id INT NOT NULL AUTO_INCREMENT,
    codigo VARCHAR(20) NOT NULL,
    nombre VARCHAR(150) NOT NULL,
    ruc VARCHAR(20) NULL,
    telefono VARCHAR(30) NULL,
    correo VARCHAR(150) NULL,
    direccion VARCHAR(255) NULL,
    contacto VARCHAR(150) NULL,
    estado ENUM('ACTIVO','INACTIVO') NOT NULL DEFAULT 'ACTIVO',
    fecha_registro DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion DATETIME NULL DEFAULT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uk_proveedores_codigo (codigo),
    UNIQUE KEY uk_proveedores_ruc (ruc)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. PRODUCTOS
DROP TABLE IF EXISTS productos;
CREATE TABLE productos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(20) NOT NULL UNIQUE,
    nombre VARCHAR(150) NOT NULL,
    descripcion TEXT NULL,
    categoria_id INT UNSIGNED NOT NULL,
    precio DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    stock INT NOT NULL DEFAULT 0,
    stock_minimo INT NOT NULL DEFAULT 5,
    imagen VARCHAR(255) NULL,
    estado ENUM('ACTIVO','INACTIVO') NOT NULL DEFAULT 'ACTIVO',
    fecha_registro TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT chk_productos_precio CHECK (precio >= 0),
    CONSTRAINT chk_productos_stock CHECK (stock >= 0),
    CONSTRAINT chk_productos_stock_minimo CHECK (stock_minimo >= 0),
    CONSTRAINT fk_productos_categoria
        FOREIGN KEY (categoria_id) REFERENCES categorias(id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    INDEX idx_productos_categoria (categoria_id),
    INDEX idx_productos_estado (estado),
    INDEX idx_productos_stock (stock)
) ENGINE=InnoDB;

-- 4. CONTACTOS
DROP TABLE IF EXISTS contactos;
CREATE TABLE contactos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(150) NOT NULL,
    mensaje TEXT NOT NULL,
    estado ENUM('PENDIENTE','ATENDIDO') NOT NULL DEFAULT 'PENDIENTE',
    fecha TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_contactos_estado (estado),
    INDEX idx_contactos_fecha (fecha)
) ENGINE=InnoDB;

-- 5. OFERTAS
DROP TABLE IF EXISTS ofertas;
CREATE TABLE ofertas (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    producto_id INT UNSIGNED NOT NULL,
    precio_oferta DECIMAL(10,2) NOT NULL,
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NOT NULL,
    estado ENUM('ACTIVA','INACTIVA') NOT NULL DEFAULT 'ACTIVA',
    fecha_registro TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT chk_ofertas_precio CHECK (precio_oferta >= 0),
    CONSTRAINT chk_ofertas_fechas CHECK (fecha_fin >= fecha_inicio),
    CONSTRAINT fk_ofertas_producto
        FOREIGN KEY (producto_id) REFERENCES productos(id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    INDEX idx_ofertas_producto (producto_id),
    INDEX idx_ofertas_fechas (fecha_inicio, fecha_fin),
    INDEX idx_ofertas_estado (estado)
) ENGINE=InnoDB;

-- 6. PEDIDOS
DROP TABLE IF EXISTS pedidos;
CREATE TABLE pedidos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(20) NOT NULL UNIQUE,
    usuario_id INT UNSIGNED NOT NULL,
    total DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    estado ENUM(
        'PENDIENTE',
        'EN PROCESO',
        'PREPARANDO',
        'ENVIADO',
        'ENTREGADO',
        'CANCELADO'
    ) NOT NULL DEFAULT 'PENDIENTE',
    observacion TEXT NULL,
    fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT chk_pedidos_total CHECK (total >= 0),
    CONSTRAINT fk_pedidos_usuario
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    INDEX idx_pedidos_usuario (usuario_id),
    INDEX idx_pedidos_estado (estado),
    INDEX idx_pedidos_fecha (fecha)
) ENGINE=InnoDB;

-- 7. DETALLE DE PEDIDO
DROP TABLE IF EXISTS detalle_pedido;
CREATE TABLE detalle_pedido (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT UNSIGNED NOT NULL,
    producto_id INT UNSIGNED NOT NULL,
    cantidad INT NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    CONSTRAINT chk_detalle_pedido_cantidad CHECK (cantidad > 0),
    CONSTRAINT chk_detalle_pedido_precio CHECK (precio >= 0),
    CONSTRAINT chk_detalle_pedido_subtotal CHECK (subtotal >= 0),
    CONSTRAINT fk_detalle_pedido_pedido
        FOREIGN KEY (pedido_id) REFERENCES pedidos(id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_detalle_pedido_producto
        FOREIGN KEY (producto_id) REFERENCES productos(id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    INDEX idx_detalle_pedido_pedido (pedido_id),
    INDEX idx_detalle_pedido_producto (producto_id)
) ENGINE=InnoDB;

-- 8. VENTAS
DROP TABLE IF EXISTS ventas;
CREATE TABLE ventas (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT UNSIGNED NULL,
    usuario_id INT UNSIGNED NOT NULL,
    total DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    estado ENUM('REGISTRADA','ANULADA') NOT NULL DEFAULT 'REGISTRADA',
    fecha TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT chk_ventas_total CHECK (total >= 0),
    CONSTRAINT fk_ventas_pedido
        FOREIGN KEY (pedido_id) REFERENCES pedidos(id)
        ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT fk_ventas_usuario
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    INDEX idx_ventas_pedido (pedido_id),
    INDEX idx_ventas_usuario (usuario_id),
    INDEX idx_ventas_fecha (fecha),
    INDEX idx_ventas_estado (estado)
) ENGINE=InnoDB;

-- 9. DETALLE DE VENTA
DROP TABLE IF EXISTS detalle_venta;
CREATE TABLE detalle_venta (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    venta_id INT UNSIGNED NOT NULL,
    producto_id INT UNSIGNED NOT NULL,
    cantidad INT NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    CONSTRAINT chk_detalle_venta_cantidad CHECK (cantidad > 0),
    CONSTRAINT chk_detalle_venta_precio CHECK (precio >= 0),
    CONSTRAINT chk_detalle_venta_subtotal CHECK (subtotal >= 0),
    CONSTRAINT fk_detalle_venta_venta
        FOREIGN KEY (venta_id) REFERENCES ventas(id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_detalle_venta_producto
        FOREIGN KEY (producto_id) REFERENCES productos(id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    INDEX idx_detalle_venta_venta (venta_id),
    INDEX idx_detalle_venta_producto (producto_id)
) ENGINE=InnoDB;


-- 10. HISTORIAL DE PEDIDOS
DROP TABLE IF EXISTS historial_pedidos;
CREATE TABLE historial_pedidos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT UNSIGNED NOT NULL,
    estado_anterior VARCHAR(30) NULL,
    estado_nuevo VARCHAR(30) NOT NULL,
    observacion TEXT NULL,
    usuario_id INT UNSIGNED NULL,
    fecha TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_historial_pedido
        FOREIGN KEY (pedido_id) REFERENCES pedidos(id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_historial_usuario
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
        ON UPDATE CASCADE ON DELETE SET NULL,
    INDEX idx_historial_pedido (pedido_id),
    INDEX idx_historial_fecha (fecha)
) ENGINE=InnoDB;


-- 11. MOVIMIENTOS DE STOCK
DROP TABLE IF EXISTS movimientos_stock;
CREATE TABLE movimientos_stock (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    producto_id INT UNSIGNED NOT NULL,
    usuario_id INT UNSIGNED NULL,
    tipo ENUM('ENTRADA','SALIDA','AJUSTE') NOT NULL,
    cantidad INT NOT NULL,
    stock_anterior INT NOT NULL,
    stock_nuevo INT NOT NULL,
    referencia VARCHAR(50) NULL,
    motivo VARCHAR(255) NULL,
    fecha TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT chk_mov_stock_cantidad CHECK (cantidad >= 0),
    CONSTRAINT chk_mov_stock_anterior CHECK (stock_anterior >= 0),
    CONSTRAINT chk_mov_stock_nuevo CHECK (stock_nuevo >= 0),
    CONSTRAINT fk_mov_stock_producto
        FOREIGN KEY (producto_id) REFERENCES productos(id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_mov_stock_usuario
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
        ON UPDATE CASCADE ON DELETE SET NULL,
    INDEX idx_mov_stock_producto (producto_id),
    INDEX idx_mov_stock_usuario (usuario_id),
    INDEX idx_mov_stock_fecha (fecha)
) ENGINE=InnoDB;

-- 12. VISTAS PARA REPORTES
DROP VIEW IF EXISTS vw_productos_stock;
CREATE VIEW vw_productos_stock AS
SELECT
    p.id,
    p.codigo,
    p.nombre,
    c.nombre AS categoria,
    p.precio,
    p.stock,
    p.stock_minimo,
    p.estado,
    CASE
        WHEN p.stock <= 0 THEN 'AGOTADO'
        WHEN p.stock <= p.stock_minimo THEN 'STOCK BAJO'
        ELSE 'DISPONIBLE'
    END AS situacion_stock
FROM productos p
INNER JOIN categorias c ON c.id = p.categoria_id;

DROP VIEW IF EXISTS vw_pedidos_resumen;
CREATE VIEW vw_pedidos_resumen AS
SELECT
    p.id,
    p.codigo,
    p.fecha,
    p.total,
    p.estado,
    p.observacion,
    u.id AS usuario_id,
    u.identificacion,
    CONCAT(u.nombre, ' ', u.apellido) AS cliente,
    COUNT(dp.id) AS productos
FROM pedidos p
INNER JOIN usuarios u ON u.id = p.usuario_id
LEFT JOIN detalle_pedido dp ON dp.pedido_id = p.id
GROUP BY
    p.id, p.codigo, p.fecha, p.total, p.estado, p.observacion,
    u.id, u.identificacion, u.nombre, u.apellido;

DROP VIEW IF EXISTS vw_ventas_mensuales;
CREATE VIEW vw_ventas_mensuales AS
SELECT
    DATE_FORMAT(v.fecha, '%Y-%m') AS periodo,
    COUNT(*) AS cantidad_ventas,
    SUM(v.total) AS total_ventas
FROM ventas v
WHERE v.estado = 'REGISTRADA'
GROUP BY DATE_FORMAT(v.fecha, '%Y-%m');

DROP VIEW IF EXISTS vw_productos_mas_vendidos;
CREATE VIEW vw_productos_mas_vendidos AS
SELECT
    p.id,
    p.codigo,
    p.nombre,
    SUM(dv.cantidad) AS unidades_vendidas,
    SUM(dv.subtotal) AS importe_vendido
FROM detalle_venta dv
INNER JOIN productos p ON p.id = dv.producto_id
INNER JOIN ventas v ON v.id = dv.venta_id
WHERE v.estado = 'REGISTRADA'
GROUP BY p.id, p.codigo, p.nombre;


-- Password: password
INSERT INTO usuarios
(identificacion, nombre, apellido, correo, password, rol, estado)
VALUES
('ADM001', 'Administrador', 'MarketGo', 'admin@marketgo.com',
 '$2y$12$sfPLTtTgKQPgXfFLzSSYM.LODnTiaR9URiSscpkxRLIZcc.POPF8C', 'ADMIN', 'ACTIVO'),
('EMP001', 'Juan', 'Empleado', 'empleado@marketgo.com',
 '$2y$12$sfPLTtTgKQPgXfFLzSSYM.LODnTiaR9URiSscpkxRLIZcc.POPF8C', 'EMPLEADO', 'ACTIVO'),
('CLI001', 'Maria', 'Cliente', 'cliente@marketgo.com',
 '$2y$12$sfPLTtTgKQPgXfFLzSSYM.LODnTiaR9URiSscpkxRLIZcc.POPF8C', 'CLIENTE', 'ACTIVO');

-- Historial inicial de las cuentas de demostración
INSERT INTO registro_usuarios (usuario_id, ip, estado) VALUES
(1, NULL, 'EXITOSO'),
(2, NULL, 'EXITOSO'),
(3, NULL, 'EXITOSO');

INSERT INTO categorias (codigo, nombre, descripcion, estado) VALUES
('C001','Abarrotes','Arroz, azúcar, fideos y productos de despensa','ACTIVA'),
('C002','Aceites','Aceites y productos para cocinar','ACTIVA'),
('C003','Lácteos','Leches, yogures, quesos y mantequilla','ACTIVA'),
('C004','Bebidas','Aguas, gaseosas y jugos','ACTIVA'),
('C005','Snacks','Galletas, chocolates y snacks','ACTIVA'),
('C006','Panadería','Pan y productos de panadería','ACTIVA'),
('C007','Frutas','Frutas frescas','ACTIVA'),
('C008','Verduras','Verduras frescas','ACTIVA'),
('C009','Carnes','Carnes y pollo','ACTIVA'),
('C010','Limpieza','Productos para limpieza del hogar','ACTIVA'),
('C011','Cuidado personal','Higiene y cuidado personal','ACTIVA');
-- PRODUCTOS 
INSERT INTO productos
(codigo,nombre,descripcion,categoria_id,precio,stock,stock_minimo,imagen,estado)
VALUES
('P001','Arroz Costeño 1 kg','Arroz extra de 1 kilogramo',1,4.50,50,10,NULL,'ACTIVO'),
('P002','Arroz Paisana 1 kg','Arroz superior de 1 kilogramo',1,4.30,45,10,NULL,'ACTIVO'),
('P003','Azúcar Rubia 1 kg','Azúcar rubia de uso doméstico',1,4.20,40,10,NULL,'ACTIVO'),
('P004','Azúcar Blanca 1 kg','Azúcar blanca refinada',1,4.50,35,10,NULL,'ACTIVO'),
('P005','Fideos Don Vittorio','Fideos spaghetti de 500 gramos',1,3.80,60,10,NULL,'ACTIVO'),
('P006','Fideos Molitalia','Fideos spaghetti de 500 gramos',1,3.50,55,10,NULL,'ACTIVO'),
('P007','Lentejas 500 g','Lentejas seleccionadas',1,4.90,30,8,NULL,'ACTIVO'),
('P008','Frejol Canario 500 g','Frejol canario seleccionado',1,6.50,25,8,NULL,'ACTIVO'),
('P009','Aceite Primor 1 L','Aceite vegetal de 1 litro',2,9.90,25,8,NULL,'ACTIVO'),
('P010','Aceite Cocinero 1 L','Aceite vegetal de cocina',2,8.90,30,8,NULL,'ACTIVO'),
('P011','Atún Florida','Atún en lata',1,5.90,40,10,'img/atun.jpg','ACTIVO'),
('P012','Mayonesa Alacena','Mayonesa clásica',1,7.50,35,8,NULL,'ACTIVO'),
('P013','Ketchup Alacena','Salsa de tomate',1,6.90,30,8,NULL,'ACTIVO'),
('P014','Leche Gloria 400 g','Leche evaporada',3,4.20,50,10,NULL,'ACTIVO'),
('P015','Leche Ideal 400 g','Leche evaporada',3,4.10,45,10,NULL,'ACTIVO'),
('P016','Yogurt Gloria Fresa','Yogurt sabor fresa',3,5.50,25,8,NULL,'ACTIVO'),
('P017','Yogurt Laive Vainilla','Yogurt sabor vainilla',3,5.80,20,6,NULL,'ACTIVO'),
('P018','Queso Edam 200 g','Queso Edam en presentación de 200 gramos',3,12.90,15,5,NULL,'ACTIVO'),
('P019','Mantequilla Gloria','Mantequilla de 200 gramos',3,9.90,20,6,NULL,'ACTIVO'),
('P020','Agua San Luis 625 ml','Agua mineral sin gas',4,2.00,100,20,NULL,'ACTIVO'),
('P021','Agua Cielo 625 ml','Agua mineral',4,1.80,100,20,NULL,'ACTIVO'),
('P022','Coca Cola 500 ml','Bebida gaseosa',4,3.50,70,15,NULL,'ACTIVO'),
('P023','Inca Kola 500 ml','Bebida gaseosa peruana',4,3.50,70,15,NULL,'ACTIVO'),
('P024','Sprite 500 ml','Bebida gaseosa',4,3.20,60,15,NULL,'ACTIVO'),
('P025','Jugo Frugos','Bebida de fruta',4,3.90,40,10,NULL,'ACTIVO'),
('P026','Galletas Oreo','Galletas con crema',5,5.50,40,10,NULL,'ACTIVO'),
('P027','Galletas Casino','Galletas rellenas',5,2.50,50,10,NULL,'ACTIVO'),
('P028','Galletas Soda Field','Galletas saladas',5,2.80,45,10,NULL,'ACTIVO'),
('P029','Papas Lays','Papas fritas clásicas',5,6.50,30,8,NULL,'ACTIVO'),
('P030','Chizitos Frito Lay','Snack de maíz',5,3.50,25,8,NULL,'ACTIVO'),
('P031','Chocolate Sublime','Chocolate con maní',5,3.00,50,10,NULL,'ACTIVO'),
('P032','Chocolate Princesa','Chocolate con leche',5,2.80,40,10,NULL,'ACTIVO'),
('P033','Pan de Molde Bimbo','Pan de molde familiar',6,8.90,25,6,NULL,'ACTIVO'),
('P034','Pan Integral Bimbo','Pan integral de molde',6,9.50,20,6,NULL,'ACTIVO'),
('P035','Pan Francés','Pan francés fresco',6,0.50,100,20,NULL,'ACTIVO'),
('P036','Croissant','Croissant de mantequilla',6,2.50,30,8,NULL,'ACTIVO'),
('P037','Manzana','Manzana fresca por kilogramo',7,5.90,40,10,NULL,'ACTIVO'),
('P038','Plátano','Plátano de seda por kilogramo',7,3.90,50,10,NULL,'ACTIVO'),
('P039','Naranja','Naranja fresca por kilogramo',7,4.50,45,10,'img/mandarina.jpg','ACTIVO'),
('P040','Uva Red Globe','Uva fresca por kilogramo',7,8.90,25,8,NULL,'ACTIVO'),
('P041','Fresa','Fresa fresca por kilogramo',7,9.90,20,6,NULL,'ACTIVO'),
('P042','Mango','Mango fresco por kilogramo',7,5.50,30,8,NULL,'ACTIVO'),
('P043','Papa Blanca','Papa blanca por kilogramo',8,3.50,60,15,NULL,'ACTIVO'),
('P044','Tomate','Tomate fresco por kilogramo',8,4.90,40,10,NULL,'ACTIVO'),
('P045','Cebolla Roja','Cebolla roja por kilogramo',8,4.50,45,10,NULL,'ACTIVO'),
('P046','Zanahoria','Zanahoria fresca por kilogramo',8,3.20,35,10,NULL,'ACTIVO'),
('P047','Lechuga','Lechuga fresca por unidad',8,2.50,30,8,NULL,'ACTIVO'),
('P048','Brócoli','Brócoli fresco por kilogramo',8,5.90,20,6,NULL,'ACTIVO'),
('P049','Pollo Entero','Pollo entero fresco por kilogramo',9,10.90,30,8,NULL,'ACTIVO'),
('P050','Pechuga de Pollo','Pechuga de pollo por kilogramo',9,14.90,20,6,NULL,'ACTIVO'),
('P051','Carne Molida','Carne molida de res por kilogramo',9,19.90,15,5,NULL,'ACTIVO'),
('P052','Bistec de Res','Bistec de res por kilogramo',9,25.90,15,5,NULL,'ACTIVO'),
('P053','Chuleta de Cerdo','Chuleta de cerdo por kilogramo',9,18.90,20,6,NULL,'ACTIVO'),
('P054','Detergente Bolívar','Detergente en polvo',10,8.90,35,8,NULL,'ACTIVO'),
('P055','Detergente Ace','Detergente en polvo',10,7.90,30,8,NULL,'ACTIVO'),
('P056','Lejía Clorox','Lejía para limpieza',10,5.90,40,10,NULL,'ACTIVO'),
('P057','Lavavajilla Ayudín','Lavavajilla líquido',10,6.50,35,8,NULL,'ACTIVO'),
('P058','Limpiador Sapolio','Limpiador multiuso',10,5.50,30,8,NULL,'ACTIVO'),
('P059','Paño Multiuso','Paño absorbente multiuso',10,3.50,50,10,NULL,'ACTIVO'),
('P060','Jabón Dove','Jabón corporal',11,5.90,30,8,NULL,'ACTIVO'),
('P061','Jabón Protex','Jabón antibacterial',11,4.90,35,8,NULL,'ACTIVO'),
('P062','Shampoo Head & Shoulders','Shampoo anticaspa',11,19.90,20,6,NULL,'ACTIVO'),
('P063','Shampoo Pantene','Shampoo para cabello',11,18.90,20,6,NULL,'ACTIVO'),
('P064','Pasta Dental Colgate','Crema dental',11,7.90,40,10,NULL,'ACTIVO'),
('P065','Cepillo Dental Colgate','Cepillo dental',11,8.50,30,8,NULL,'ACTIVO'),
('P066','Barra de Cereal','Barra de cereal',5,3.90,30,8,'img/cerealbar.jpg','ACTIVO'),
('P067','Camote','Camote fresco por kilogramo',8,4.20,30,8,'img/camote.jpg','ACTIVO'),
('P068','Botella de Gas','Botella de gas doméstico',10,55.00,10,3,'img/botellagas.jpg','ACTIVO');


INSERT INTO ofertas
(producto_id, precio_oferta, fecha_inicio, fecha_fin, estado)
VALUES
(1, 4.10, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 30 DAY), 'ACTIVA'),
(11, 5.20, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 30 DAY), 'ACTIVA'),
(20, 1.70, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 30 DAY), 'ACTIVA'),
(26, 4.80, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 30 DAY), 'ACTIVA'),
(66, 3.20, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 30 DAY), 'ACTIVA');

-- CONTACTOS 
INSERT INTO contactos (nombre, correo, mensaje, estado) VALUES
('Juan Pérez','juan@gmail.com','Consulta sobre productos disponibles.','PENDIENTE'),
('María López','maria@gmail.com','Quisiera información sobre las ofertas.','ATENDIDO'),
('Carlos Torres','carlos@gmail.com','Consulta sobre mi pedido.','PENDIENTE');

-- PEDIDOS Y VENTAS DEMO
INSERT INTO pedidos (codigo, usuario_id, total, estado, observacion) VALUES
('PED001', 3, 13.00, 'PENDIENTE', 'Pedido de demostración'),
('PED002', 3, 18.80, 'PREPARANDO', 'Preparando productos');

INSERT INTO detalle_pedido (pedido_id, producto_id, cantidad, precio, subtotal) VALUES
(1, 1, 1, 4.50, 4.50),
(1, 26, 1, 5.50, 5.50),
(1, 20, 1, 2.00, 2.00),
(2, 9, 1, 9.90, 9.90),
(2, 14, 1, 4.20, 4.20),
(2, 22, 1, 3.50, 3.50);

INSERT INTO historial_pedidos (pedido_id, estado_anterior, estado_nuevo, observacion, usuario_id) VALUES
(1, NULL, 'PENDIENTE', 'Pedido de demostración', 3),
(2, NULL, 'PENDIENTE', 'Pedido creado', 3),
(2, 'PENDIENTE', 'PREPARANDO', 'Preparando productos', 2);

INSERT INTO ventas (pedido_id, usuario_id, total, estado) VALUES
(1, 3, 13.00, 'REGISTRADA'),
(2, 3, 18.80, 'REGISTRADA');

INSERT INTO detalle_venta (venta_id, producto_id, cantidad, precio, subtotal) VALUES
(1, 1, 1, 4.50, 4.50),
(1, 26, 1, 5.50, 5.50),
(1, 20, 1, 2.00, 2.00),
(2, 9, 1, 9.90, 9.90),
(2, 14, 1, 4.20, 4.20),
(2, 22, 1, 3.50, 3.50);

INSERT INTO movimientos_stock
(producto_id, usuario_id, tipo, cantidad, stock_anterior, stock_nuevo, referencia, motivo)
SELECT id, 1, 'ENTRADA', stock, 0, stock, codigo, 'Carga inicial de productos'
FROM productos;

SET FOREIGN_KEY_CHECKS = 1;


INSERT INTO productos
(codigo, nombre, descripcion, categoria_id, precio, stock, stock_minimo, imagen, estado)
VALUES


('P069','Mandarina','Mandarina fresca por kilogramo',7,4.90,40,10,NULL,'ACTIVO'),
('P070','Pera','Pera fresca por kilogramo',7,6.90,35,8,NULL,'ACTIVO'),
('P071','Papaya','Papaya fresca por kilogramo',7,5.90,30,8,NULL,'ACTIVO'),
('P072','Piña','Piña fresca por unidad',7,6.50,25,6,NULL,'ACTIVO'),
('P073','Sandía','Sandía fresca por kilogramo',7,3.50,30,8,NULL,'ACTIVO'),

('P074','Pepino','Pepino fresco por kilogramo',8,3.90,35,8,NULL,'ACTIVO'),
('P075','Zapallo','Zapallo fresco por kilogramo',8,4.50,30,8,NULL,'ACTIVO'),
('P076','Ajo','Ajo fresco por kilogramo',8,12.90,25,6,NULL,'ACTIVO'),
('P077','Apio','Apio fresco por unidad',8,2.50,30,8,NULL,'ACTIVO'),
('P078','Espinaca','Espinaca fresca por atado',8,2.90,30,8,NULL,'ACTIVO'),

('P079','Arroz Extra 5 kg','Arroz extra en presentación familiar',1,22.50,30,8,NULL,'ACTIVO'),
('P080','Azúcar Rubia 5 kg','Azúcar rubia en presentación familiar',1,19.90,25,6,NULL,'ACTIVO'),
('P081','Lentejas 1 kg','Lentejas seleccionadas',1,8.90,30,8,NULL,'ACTIVO'),
('P082','Frejol Canario 1 kg','Frejol canario seleccionado',1,12.50,25,6,NULL,'ACTIVO'),
('P083','Quinua 500 g','Quinua seleccionada',1,7.90,30,8,NULL,'ACTIVO'),

('P084','Leche Gloria 1 L','Leche evaporada en presentación familiar',3,8.90,30,8,NULL,'ACTIVO'),
('P085','Leche Laive 400 g','Leche evaporada Laive',3,4.30,35,8,NULL,'ACTIVO'),
('P086','Yogurt Gloria Durazno','Yogurt sabor durazno',3,5.50,25,6,NULL,'ACTIVO'),
('P087','Queso Fresco 250 g','Queso fresco en presentación de 250 gramos',3,10.90,20,5,NULL,'ACTIVO'),
('P088','Mantequilla Laive 200 g','Mantequilla en presentación de 200 gramos',3,9.50,20,5,NULL,'ACTIVO'),

('P089','Agua San Luis 2.5 L','Agua mineral sin gas',4,4.50,50,12,NULL,'ACTIVO'),
('P090','Agua Cielo 2.5 L','Agua mineral',4,4.20,50,12,NULL,'ACTIVO'),
('P091','Coca Cola 1.5 L','Bebida gaseosa',4,7.50,40,10,NULL,'ACTIVO'),
('P092','Inca Kola 1.5 L','Bebida gaseosa peruana',4,7.50,40,10,NULL,'ACTIVO'),
('P093','Frugos Naranja 1 L','Bebida de fruta sabor naranja',4,5.50,35,8,NULL,'ACTIVO'),

('P094','Detergente Ariel','Detergente en polvo para ropa',10,10.90,30,8,NULL,'ACTIVO'),
('P095','Lejía Clorox 1 L','Lejía para limpieza del hogar',10,4.90,40,10,NULL,'ACTIVO'),
('P096','Lavavajilla Ayudín 750 ml','Lavavajilla líquido',10,8.50,30,8,NULL,'ACTIVO'),
('P097','Limpiador Poett','Limpiador líquido para pisos',10,6.90,30,8,NULL,'ACTIVO'),
('P098','Esponja Multiuso','Esponja para limpieza doméstica',10,2.50,50,12,NULL,'ACTIVO');


INSERT INTO movimientos_stock
(producto_id, usuario_id, tipo, cantidad, stock_anterior, stock_nuevo, referencia, motivo)
SELECT
    id,
    1,
    'ENTRADA',
    stock,
    0,
    stock,
    codigo,
    'Carga inicial de productos'
FROM productos
WHERE id >= 69;


INSERT INTO proveedores
(codigo, nombre, ruc, telefono, correo, direccion, contacto, estado)
VALUES
('PRV001','Distribuidora Marketgo','20123456781','987654321','ventas@distribuidoramarketgo.com','Lima','Carlos Mendoza','ACTIVO'),
('PRV002','Distribuidora San Martín','20234567892','986543210','ventas@sanmartin.com','Lima','Luis Ramírez','ACTIVO'),
('PRV003','Lácteos del Perú','20345678903','985432109','ventas@lacteosperu.com','Lima','Pedro Torres','ACTIVO'),
('PRV004','Bebidas Andinas','20456789014','984321098','ventas@bebidasandinas.com','Lima','Jorge Flores','ACTIVO'),
('PRV005','Frutas y Verduras del Valle','20567890125','983210987','ventas@frutasvalle.com','Lima','Rosa Quispe','ACTIVO'),
('PRV006','Limpieza Hogar SAC','20678901236','982109876','ventas@limpiezahogar.com','Lima','Patricia León','ACTIVO');

-- CONSULTAS DE COMPROBACION
SELECT 'usuarios' AS tabla, COUNT(*) AS registros FROM usuarios
UNION ALL SELECT 'categorias', COUNT(*) FROM categorias
UNION ALL SELECT 'productos', COUNT(*) FROM productos
UNION ALL SELECT 'ofertas', COUNT(*) FROM ofertas
UNION ALL SELECT 'contactos', COUNT(*) FROM contactos
UNION ALL SELECT 'pedidos', COUNT(*) FROM pedidos
UNION ALL SELECT 'detalle_pedido', COUNT(*) FROM detalle_pedido
UNION ALL SELECT 'ventas', COUNT(*) FROM ventas
UNION ALL SELECT 'detalle_venta', COUNT(*) FROM detalle_venta
UNION ALL SELECT 'historial_pedidos', COUNT(*) FROM historial_pedidos
UNION ALL SELECT 'movimientos_stock', COUNT(*) FROM movimientos_stock;