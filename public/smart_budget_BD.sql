/* Aplicación SmartBudget
Grupo 4
Integrantes: 	Donovan Jesús Aguilar Cárdenas
				Jenny Liang Jiang
                Ángel Felipe Rodríguez Vargas
                Xavier Antonio Marín Araya
Curso Ambiente Web Cliente Seridor SC-502
*/

DROP DATABASE IF EXISTS smart_budget;

-- Creación de la base de datos
CREATE DATABASE smart_budget;

-- Selección de la BD smart_budget
USE smart_budget;

-- creación de tablas
CREATE TABLE estados(
	id_estado INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50)
);

CREATE TABLE usuarios (
	id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50),
    primer_apellido VARCHAR(50),
    segundo_apellido VARCHAR(50),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    id_estado INT DEFAULT 2,
    CONSTRAINT usuarios_id_estado_fk FOREIGN KEY(id_estado) REFERENCES estados(id_estado)
);

SELECT * FROM usuarios;

CREATE TABLE categorias_ingreso(
    id_usuario INT NOT NULL,
	id_categoria INT NOT NULL,
    nombre VARCHAR(100),
    id_estado INT NOT NULL,

    CONSTRAINT categorias_ingreso_id_usuario_id_categoria_pk PRIMARY KEY(id_usuario, id_categoria),

    CONSTRAINT categorias_ingreso_id_usuario_fk FOREIGN KEY(id_usuario) REFERENCES usuarios(id_usuario),
    CONSTRAINT categorias_ingreso_id_estado_fk FOREIGN KEY(id_estado) REFERENCES estados(id_estado)
);

CREATE TABLE ingresos(
	id_usuario INT NOT NULL,
	id_ingreso INT NOT NULL,
    nombre VARCHAR(100),
    monto DECIMAL(12,2),
    fecha DATE,
    descripcion TEXT,
    id_categoria INT,
    id_estado INT,

    CONSTRAINT ingresos_id_usuario_id_ingreso_pk PRIMARY KEY(id_usuario, id_ingreso),

    CONSTRAINT ingresos_id_usuario_id_categoria_fk FOREIGN KEY(id_usuario, id_categoria) REFERENCES categorias_ingreso(id_usuario, id_categoria),
    CONSTRAINT ingresos_id_usuario_fk FOREIGN KEY(id_usuario) REFERENCES usuarios(id_usuario),
    CONSTRAINT ingresos_id_estado_fk FOREIGN KEY(id_estado) REFERENCES estados(id_estado)
);

CREATE TABLE categorias_gasto(
    id_usuario INT NOT NULL,
    id_categoria INT NOT NULL,
    nombre VARCHAR(100),
    id_estado INT,

    CONSTRAINT categorias_gasto_id_usuario_id_categoria_pk PRIMARY KEY(id_usuario, id_categoria),

    CONSTRAINT categorias_gasto_id_usuario_fk FOREIGN KEY(id_usuario) REFERENCES usuarios(id_usuario),
    CONSTRAINT categorias_gasto_id_estado_fk FOREIGN KEY(id_estado) REFERENCES estados(id_estado)
);

CREATE TABLE gastos(
    id_usuario INT NOT NULL,
    id_gasto INT NOT NULL,
    nombre VARCHAR(100),
    monto DECIMAL(12,2),
    fecha DATE,
    descripcion TEXT,
    id_categoria INT,
    id_estado INT,

    CONSTRAINT gastos_id_usuario_id_gasto_pk PRIMARY KEY(id_usuario, id_gasto),

    CONSTRAINT gastos_id_usuario_id_categoria_fk FOREIGN KEY(id_usuario, id_categoria) REFERENCES categorias_gasto(id_usuario, id_categoria),
    CONSTRAINT gastos_id_usuario_fk FOREIGN KEY(id_usuario) REFERENCES usuarios(id_usuario),
    CONSTRAINT gastos_id_estado_fk FOREIGN KEY(id_estado) REFERENCES estados(id_estado)
);

CREATE TABLE recordatorios_pago(
    id_usuario INT NOT NULL,
    id_recordatorio INT NOT NULL,
    nombre VARCHAR(100),
    monto DECIMAL(12,2),
    fecha_pago DATE,
    descripcion TEXT,
    id_categoria INT,
    id_estado INT,

    CONSTRAINT recordatorios_pago_id_usuario_id_recordatorio_pk PRIMARY KEY(id_usuario, id_recordatorio),

    CONSTRAINT recordatorios_pago_id_usuario_id_categoria_fk FOREIGN KEY(id_usuario, id_categoria) REFERENCES categorias_gasto(id_usuario, id_categoria),
    CONSTRAINT recordatorios_pago_id_usuario_fk FOREIGN KEY(id_usuario) REFERENCES usuarios(id_usuario),
    CONSTRAINT recordatorios_pago_id_estado_fk FOREIGN KEY(id_estado) REFERENCES estados(id_estado)
);

CREATE TABLE metas_ahorro(
    id_usuario INT NOT NULL,
    id_meta INT NOT NULL,
    nombre VARCHAR(100),
    monto_actual DECIMAL(12,2),
    monto_objetivo DECIMAL(12,2),
    fecha_inicio DATE,
    fecha_cumplimiento DATE,    
    descripcion TEXT,
    id_estado INT,

    CONSTRAINT metas_ahorro_id_usuario_id_meta_pk PRIMARY KEY(id_usuario, id_meta),

    CONSTRAINT metas_ahorro_id_usuario_fk FOREIGN KEY(id_usuario) REFERENCES usuarios(id_usuario),
    CONSTRAINT metas_ahorro_id_estado_fk FOREIGN KEY(id_estado) REFERENCES estados(id_estado)
);

CREATE TABLE tipos_notificacion(
    id_tipo INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    id_estado INT NOT NULL,
    CONSTRAINT tipos_notificacion_id_estado_fk FOREIGN KEY(id_estado) REFERENCES estados(id_estado)
);

CREATE TABLE notificaciones(
    id_usuario INT NOT NULL,
    id_notificacion INT NOT NULL,
    id_tipo INT NOT NULL,
    titulo VARCHAR(100),
    mensaje TEXT,
    fecha_creacion DATE,
    leida BOOLEAN DEFAULT FALSE,
    enviada_correo BOOLEAN DEFAULT FALSE,
    id_estado INT,
    CONSTRAINT notificaciones_id_usuario_id_notificacion_pk PRIMARY KEY(id_usuario, id_notificacion),

    CONSTRAINT notificaciones_id_tipo_fk FOREIGN KEY(id_tipo) REFERENCES tipos_notificacion(id_tipo),
    CONSTRAINT notificaciones_id_usuario_fk FOREIGN KEY(id_usuario) REFERENCES usuarios(id_usuario),
    CONSTRAINT notificaciones_id_estado_fk FOREIGN KEY(id_estado) REFERENCES estados(id_estado)
);

CREATE TABLE configuraciones_notificaciones(
    id_usuario INT NOT NULL,
    id_tipo INT NOT NULL,
    notificacion_app BOOLEAN DEFAULT TRUE,
    notificacion_correo BOOLEAN DEFAULT TRUE,
    id_estado INT NOT NULL,

    CONSTRAINT configuraciones_notificaciones_id_usuario_id_tipo_pk PRIMARY KEY(id_usuario, id_tipo),

    CONSTRAINT configuraciones_notif_id_tipo_fk FOREIGN KEY(id_tipo) REFERENCES tipos_notificacion(id_tipo),
    CONSTRAINT configuraciones_notif_id_usuario_fk FOREIGN KEY(id_usuario) REFERENCES usuarios(id_usuario),
    CONSTRAINT configuraciones_notif_id_estado_fk FOREIGN KEY(id_estado) REFERENCES estados(id_estado)
);

CREATE TABLE presupuestos_categorias(
    id_usuario INT NOT NULL,
    id_categoria INT NOT NULL,
    mes INT NOT NULL,
    anio INT NOT NULL,
    monto_limite DECIMAL(12,2) NOT NULL,
    id_estado INT NOT NULL,

    CONSTRAINT presupuestos_categorias_id_usuario_id_categoria_mes_anio_pk PRIMARY KEY(id_usuario, id_categoria, mes, anio),

    CONSTRAINT presupuestos_categorias_id_usuario_id_categoria_fk FOREIGN KEY(id_usuario, id_categoria) REFERENCES categorias_gasto(id_usuario, id_categoria),
    CONSTRAINT presupuestos_categorias_id_usuario_fk FOREIGN KEY(id_usuario) REFERENCES usuarios(id_usuario),
    CONSTRAINT presupuestos_categorias_id_estado_fk FOREIGN KEY(id_estado) REFERENCES estados(id_estado)
);

-- Datos de prueba precargados
INSERT INTO estados (nombre) VALUES
('inactivo'),
('activo');

-- Creación de 5 usuarios de prueba
INSERT INTO usuarios(nombre, primer_apellido, segundo_apellido, email, password, id_estado)
VALUES
('Admin', 'PrimerApellido', 'SegundoApellido', 'admin@smartbudget.com', '$2y$10$UlaJNZHxopZWGLaZsIvK7u7OEoXbX6GEKbu9w.iYEkusCFeznAAHG', 2),
('Donovan', 'Aguilar', 'Cárdenas', 'donovan@correo.com', '$2y$10$DjJasykFWOMX4OdOBXLXaeKlwigwrOjZv7tj9qg/HesoycL5/cn6C', 2),
('Jenny', 'Liang', 'Jiang', 'jenny@correo.com', '$2y$10$DjJasykFWOMX4OdOBXLXaeKlwigwrOjZv7tj9qg/HesoycL5/cn6C', 2),
('Ángel', 'Rodríguez', 'Vargas', 'angel@correo.com', '$2y$10$DjJasykFWOMX4OdOBXLXaeKlwigwrOjZv7tj9qg/HesoycL5/cn6C', 2),
('Xavier', 'Marín', 'Araya', 'xavier@correo.com', '$2y$10$DjJasykFWOMX4OdOBXLXaeKlwigwrOjZv7tj9qg/HesoycL5/cn6C', 2);

INSERT INTO categorias_ingreso (id_usuario, id_categoria, nombre, id_estado)
VALUES
(1, 1, 'Salario', 2),
(1, 2, 'Freelance / Trabajo independiente', 2),
(1, 3, 'Negocio propio', 2),
(1, 4, 'Bonos y comisiones', 2),
(1, 5, 'Inversiones', 2),
(1, 6, 'Alquileres', 2),
(1, 7, 'Reembolsos', 2),
(1, 8, 'Regalos', 2),
(1, 9, 'Ayuda familiar', 2),
(1, 10, 'Becas', 2),
(1, 11, 'Ventas ocasionales', 2),
(1, 12, 'Otros ingresos', 2),
(2, 1, 'Salario', 2),
(2, 2, 'Freelance / Trabajo independiente', 2),
(2, 3, 'Negocio propio', 2),
(2, 4, 'Bonos y comisiones', 2),
(2, 5, 'Inversiones', 2),
(2, 6, 'Alquileres', 2),
(2, 7, 'Reembolsos', 2),
(2, 8, 'Regalos', 2),
(2, 9, 'Ayuda familiar', 2),
(2, 10, 'Becas', 2),
(2, 11, 'Ventas ocasionales', 2),
(2, 12, 'Otros ingresos', 2),
(3, 1, 'Salario', 2),
(3, 2, 'Freelance / Trabajo independiente', 2),
(3, 3, 'Negocio propio', 2),
(3, 4, 'Bonos y comisiones', 2),
(3, 5, 'Inversiones', 2),
(3, 6, 'Alquileres', 2),
(3, 7, 'Reembolsos', 2),
(3, 8, 'Regalos', 2),
(3, 9, 'Ayuda familiar', 2),
(3, 10, 'Becas', 2),
(3, 11, 'Ventas ocasionales', 2),
(3, 12, 'Otros ingresos', 2),
(4, 1, 'Salario', 2),
(4, 2, 'Freelance / Trabajo independiente', 2),
(4, 3, 'Negocio propio', 2),
(4, 4, 'Bonos y comisiones', 2),
(4, 5, 'Inversiones', 2),
(4, 6, 'Alquileres', 2),
(4, 7, 'Reembolsos', 2),
(4, 8, 'Regalos', 2),
(4, 9, 'Ayuda familiar', 2),
(4, 10, 'Becas', 2),
(4, 11, 'Ventas ocasionales', 2),
(4, 12, 'Otros ingresos', 2),
(5, 1, 'Salario', 2),
(5, 2, 'Freelance / Trabajo independiente', 2),
(5, 3, 'Negocio propio', 2),
(5, 4, 'Bonos y comisiones', 2),
(5, 5, 'Inversiones', 2),
(5, 6, 'Alquileres', 2),
(5, 7, 'Reembolsos', 2),
(5, 8, 'Regalos', 2),
(5, 9, 'Ayuda familiar', 2),
(5, 10, 'Becas', 2),
(5, 11, 'Ventas ocasionales', 2),
(5, 12, 'Otros ingresos', 2);


INSERT INTO ingresos(id_usuario, id_ingreso, nombre, monto, fecha, descripcion, id_categoria, id_estado)
VALUES
(1, 1, 'Salario mensual', 250000.00, '2026-01-15', 'Salario recibido por trabajo en la empresa XYZ', 1, 2),
(1, 2, 'Reembolso de transporte', 12000.00, '2026-01-28', 'Reembolso por gastos de traslado laboral', 7, 2),
(2, 1, 'Proyecto freelance', 100000.00, '2026-02-10', 'Pago por proyecto de diseño web', 2, 2),
(2, 2, 'Venta de ilustraciones', 65000.00, '2026-02-22', 'Ingreso por venta de trabajos digitales', 11, 2),
(3, 1, 'Venta de productos', 50000.00, '2026-03-05', 'Ingresos por venta de productos en línea', 11, 2),
(3, 2, 'Bono por referidos', 30000.00, '2026-03-18', 'Bono recibido por recomendar nuevos clientes', 4, 2),
(3, 3, 'Regalo de aniversario', 45000.00, '2026-03-28', 'Dinero recibido como regalo familiar', 8, 2),
(4, 1, 'Alquiler de propiedad', 975000.00, '2026-04-01', 'Ingreso mensual por alquiler de propiedad', 6, 2),
(4, 2, 'Intereses de ahorro', 18500.00, '2026-04-20', 'Intereses generados por cuenta de ahorro', 5, 2),
(5, 1, 'Bono de desempeño', 18000.00, '2026-05-20', 'Bono recibido por buen desempeño laboral', 4, 2),
(5, 2, 'Ayuda familiar', 50000.00, '2026-05-30', 'Ayuda económica recibida para cubrir gastos del mes', 9, 2);

INSERT INTO categorias_gasto (id_usuario, id_categoria, nombre, id_estado)
VALUES
(1, 1, 'Alimentación', 2),
(1, 2, 'Transporte', 2),
(1, 3, 'Salud', 2),
(1, 4, 'Educación', 2),
(1, 5, 'Ocio', 2),
(1, 6, 'Membresías y suscripciones', 2),
(1, 7, 'Ahorros programados', 2),
(1, 8, 'Pagos municipales', 2),
(1, 9, 'Tarjetas de crédito', 2),
(1, 10, 'Servicios básicos', 2),
(1, 11, 'Otros gastos', 2),
(2, 1, 'Alimentación', 2),
(2, 2, 'Transporte', 2),
(2, 3, 'Salud', 2),
(2, 4, 'Educación', 2),
(2, 5, 'Ocio', 2),
(2, 6, 'Membresías y suscripciones', 2),
(2, 7, 'Ahorros programados', 2),
(2, 8, 'Pagos municipales', 2),
(2, 9, 'Tarjetas de crédito', 2),
(2, 10, 'Servicios básicos', 2),
(2, 11, 'Otros gastos', 2),
(3, 1, 'Alimentación', 2),
(3, 2, 'Transporte', 2),
(3, 3, 'Salud', 2),
(3, 4, 'Educación', 2),
(3, 5, 'Ocio', 2),
(3, 6, 'Membresías y suscripciones', 2),
(3, 7, 'Ahorros programados', 2),
(3, 8, 'Pagos municipales', 2),
(3, 9, 'Tarjetas de crédito', 2),
(3, 10, 'Servicios básicos', 2),
(3, 11, 'Otros gastos', 2),
(4, 1, 'Alimentación', 2),
(4, 2, 'Transporte', 2),
(4, 3, 'Salud', 2),
(4, 4, 'Educación', 2),
(4, 5, 'Ocio', 2),
(4, 6, 'Membresías y suscripciones', 2),
(4, 7, 'Ahorros programados', 2),
(4, 8, 'Pagos municipales', 2),
(4, 9, 'Tarjetas de crédito', 2),
(4, 10, 'Servicios básicos', 2),
(4, 11, 'Otros gastos', 2),
(5, 1, 'Alimentación', 2),
(5, 2, 'Transporte', 2),
(5, 3, 'Salud', 2),
(5, 4, 'Educación', 2),
(5, 5, 'Ocio', 2),
(5, 6, 'Membresías y suscripciones', 2),
(5, 7, 'Ahorros programados', 2),
(5, 8, 'Pagos municipales', 2),
(5, 9, 'Tarjetas de crédito', 2),
(5, 10, 'Servicios básicos', 2),
(5, 11, 'Otros gastos', 2);

INSERT INTO gastos(id_usuario, id_gasto, nombre, monto, fecha, descripcion, id_categoria, id_estado)
VALUES
(1, 1, 'Supermercado mensual', 85000.00, '2026-01-20', 'Compra de alimentos y productos de limpieza', 1, 2),
(2, 1, 'Pasajes transporte', 15000.00, '2026-01-10', 'Gastos en bus', 2, 2),
(3, 1, 'Suscripción plataforma', 12000.00, '2026-01-05', 'Membresía de música en línea', 6, 2),
(4, 1, 'Ahorro programado', 50000.00, '2026-01-01', 'Ahorro automático mensual', 7, 2),
(5, 1, 'Pago municipal trimestral', 78000.00, '2026-03-15', 'Pago de impuesto municipal trimestral', 8, 2),
(5, 2, 'Pago tarjeta de crédito', 250000.00, '2026-01-25', 'Pago mensual de tarjeta de crédito', 9, 2);

INSERT INTO recordatorios_pago(id_usuario, id_recordatorio, nombre, monto, fecha_pago, descripcion, id_categoria, id_estado)
VALUES
(1, 1, 'Suscripción Netflix', 15000.00, '2026-02-05', 'Renovación mensual de membresía Netflix', 6, 2),
(2, 1, 'Pago de luz', 35000.00, '2026-02-15', 'Servicio de energía eléctrica mensual', 10, 2),
(2, 2, 'Pago de agua', 25000.00, '2026-02-15', 'Servicio de suministro de agua potable mensual', 10, 2),
(2, 3, 'Pago de internet', 25000.00, '2026-02-15', 'Servicio de internet residencial mensual', 10, 2),
(3, 1, 'Cuota de seguro', 120000.00, '2026-02-20', 'Pago mensual de seguro del automóvil', 11, 2),
(4, 1, 'Pago de impuestos municipales', 78000.00, '2026-03-15', 'Impuesto municipal trimestral', 8, 2);

INSERT INTO metas_ahorro(id_usuario, id_meta, nombre, monto_actual, monto_objetivo, fecha_inicio, fecha_cumplimiento, descripcion, id_estado)
VALUES
(1, 1, 'Viaje a Costa Rica', 250000.00, 1500000.00, '2025-06-01', '2026-12-31', 'Ahorrar para un viaje vacacional a playas costarricenses', 2),
(2, 1, 'Fondo de emergencia', 500000.00, 2000000.00, '2025-01-01', '2026-06-30', 'Crear un fondo de emergencia para gastos inesperados', 2),
(3, 1, 'Compra de laptop', 800000.00, 2500000.00, '2025-03-15', '2026-09-30', 'Ahorrar para comprar una computadora portátil', 2),
(4, 1, 'Educación continua', 150000.00, 1000000.00, '2025-09-01', '2027-06-30', 'Inversión en cursos y certificaciones profesionales', 2),
(5, 1, 'Ahorro a largo plazo', 1200000.00, 5000000.00, '2024-01-01', '2028-12-31', 'Ahorro general para objetivos futuros', 2);


INSERT INTO tipos_notificacion(nombre, id_estado) VALUES
('Recordatorio de pago', 2),
('Meta de ahorro alcanzada', 2),
('Límite de gasto alcanzado', 2),
('Reporte mensual', 2),
('Progreso de meta de ahorro', 2),
('Seguridad de cuenta', 2),
('Otros', 2);

INSERT INTO configuraciones_notificaciones(id_usuario, id_tipo, notificacion_app, notificacion_correo, id_estado)
VALUES
(1, 1, true, true, 2),
(1, 2, true, true, 2),
(1, 3, true, true, 2),
(1, 4, true, true, 2),
(1, 5, true, true, 2),
(1, 6, true, true, 2),
(1, 7, true, true, 2),
(2, 1, true, false, 2),
(2, 2, true, false, 2),
(2, 3, true, false, 2),
(2, 4, true, false, 2),
(2, 5, true, false, 2),
(2, 6, true, false, 2),
(2, 7, true, false, 2),
(3, 1, false, true, 2),
(3, 2, false, true, 2),
(3, 3, false, true, 2),
(3, 4, false, true, 2),
(3, 5, false, true, 2),
(3, 6, false, true, 2),
(3, 7, false, true, 2),
(4, 1, true, true, 2),
(4, 2, true, true, 2),
(4, 3, true, true, 2),
(4, 4, true, true, 2),
(4, 5, true, true, 2),
(4, 6, true, true, 2),
(4, 7, true, true, 2),
(5, 1, false, false, 2),
(5, 2, false, false, 2),
(5, 3, false, false, 2),
(5, 4, false, false, 2),
(5, 5, false, false, 2),
(5, 6, false, false, 2),
(5, 7, false, false, 2);

INSERT INTO notificaciones(id_usuario, id_notificacion, id_tipo, titulo, mensaje, fecha_creacion, leida, enviada_correo, id_estado)
VALUES
(1, 1, 1, 'Recordatorio de pago', 'Recuerda que tienes un pago pendiente de tu suscripción a Netflix.', '2026-02-01', false, false, 2),
(1, 2, 2, 'Meta de ahorro alcanzada', '¡Felicidades! Has alcanzado tu meta de ahorro para tu viaje a Costa Rica.', '2026-12-31', false, false, 2),
(2, 1, 6, 'Seguridad de cuenta', 'Se ha detectado un inicio de sesión sospechoso en tu cuenta. Por favor, verifica tu actividad reciente.', '2026-02-10', false, false, 2),
(3, 1, 5, 'Progreso de meta de ahorro', 'Has alcanzado el 50% de tu meta de ahorro para la compra de tu laptop.', '2026-06-15', false, false, 2),
(3, 2, 4, 'Notificación de ejemplo 2', 'Ejemplo de segunda notificación del usuario 3.', '2026-06-16', false, false, 2),
(3, 3, 1, 'Notificación de ejemplo 3', 'Ejemplo de tercera notificación del usuario 3.', '2026-06-17', false, false, 2);

INSERT INTO presupuestos_categorias(id_usuario, id_categoria, mes, anio, monto_limite, id_estado)
VALUES
(1, 1, 1, 2026, 100000.00, 2),
(2, 2, 1, 2026, 50000.00, 2),
(3, 3, 1, 2026, 200000.00, 2),
(4, 4, 1, 2026, 150000.00, 2),
(5, 5, 1, 2026, 80000.00, 2);
