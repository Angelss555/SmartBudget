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
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255),
    id_estado INT DEFAULT 2,
    CONSTRAINT usuarios_id_estado_fk FOREIGN KEY(id_estado) REFERENCES estados(id_estado)
);

SELECT * FROM usuarios;

CREATE TABLE categorias_ingreso(
	id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    id_estado INT NOT NULL,

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

    CONSTRAINT ingresos_id_categoria_fk FOREIGN KEY(id_categoria) REFERENCES categorias_ingreso(id_categoria),
    CONSTRAINT ingresos_id_usuario_fk FOREIGN KEY(id_usuario) REFERENCES usuarios(id_usuario),
    CONSTRAINT ingresos_id_estado_fk FOREIGN KEY(id_estado) REFERENCES estados(id_estado)
);

CREATE TABLE categorias_gasto(
	id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    id_estado INT NOT NULL,

    CONSTRAINT categorias_gasto_id_estado_fk FOREIGN KEY(id_estado) REFERENCES estados(id_estado)
);

CREATE TABLE gastos(
    id_usuario INT NOT NULL,
    id_gasto INT NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    monto DECIMAL(12,2) NOT NULL,
    fecha DATE NOT NULL,
    descripcion TEXT,
    id_categoria INT,
    id_estado INT,

    CONSTRAINT gastos_id_usuario_id_gasto_pk PRIMARY KEY(id_usuario, id_gasto),

    CONSTRAINT gastos_id_categoria_fk FOREIGN KEY(id_categoria) REFERENCES categorias_gasto(id_categoria),
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

    CONSTRAINT recordatorios_pago_id_categoria_fk FOREIGN KEY(id_categoria) REFERENCES categorias_gasto(id_categoria),
    CONSTRAINT recordatorios_pago_id_usuario_fk FOREIGN KEY(id_usuario) REFERENCES usuarios(id_usuario),
    CONSTRAINT recordatorios_pago_id_estado_fk FOREIGN KEY(id_estado) REFERENCES estados(id_estado)
);

CREATE TABLE metas_ahorro(
    id_usuario INT NOT NULL,
    id_meta INT NOT NULL,
    nombre VARCHAR(100),
    monto_inicial DECIMAL(12,2),
    monto_objetivo DECIMAL(12,2),
    cuota DECIMAL(12,2) DEFAULT 0,
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

    CONSTRAINT presupuestos_categorias_id_usuario_fk FOREIGN KEY(id_usuario) REFERENCES usuarios(id_usuario),
    CONSTRAINT presupuestos_categorias_id_categoria_fk FOREIGN KEY(id_categoria) REFERENCES categorias_gasto(id_categoria),
    CONSTRAINT presupuestos_categorias_id_estado_fk FOREIGN KEY(id_estado) REFERENCES estados(id_estado)
);

-- Datos de prueba precargados
INSERT INTO estados (nombre) VALUES
('inactivo'),
('activo');

-- Creación de 5 usuarios de prueba
INSERT INTO usuarios(nombre, primer_apellido, segundo_apellido, email, password, id_estado)
VALUES
('Admin', 'PrimerApellido', 'SegundoApellido', 'admin@correo.com', '$2y$10$DjJasykFWOMX4OdOBXLXaeKlwigwrOjZv7tj9qg/HesoycL5/cn6C', 2),
('Donovan', 'Aguilar', 'Cárdenas', 'donovan@correo.com', '$2y$10$DjJasykFWOMX4OdOBXLXaeKlwigwrOjZv7tj9qg/HesoycL5/cn6C', 2),
('Jenny', 'Liang', 'Jiang', 'jenny@correo.com', '$2y$10$DjJasykFWOMX4OdOBXLXaeKlwigwrOjZv7tj9qg/HesoycL5/cn6C', 2),
('Ángel', 'Rodríguez', 'Vargas', 'angel@correo.com', '$2y$10$DjJasykFWOMX4OdOBXLXaeKlwigwrOjZv7tj9qg/HesoycL5/cn6C', 2),
('Xavier', 'Marín', 'Araya', 'xavier@correo.com', '$2y$10$DjJasykFWOMX4OdOBXLXaeKlwigwrOjZv7tj9qg/HesoycL5/cn6C', 2);

INSERT INTO categorias_ingreso (id_categoria, nombre, id_estado)
VALUES
(1, 'Salario', 2),
(2, 'Freelance / Trabajo independiente', 2),
(3, 'Negocio propio', 2),
(4, 'Bonos y comisiones', 2),
(5, 'Inversiones', 2),
(6, 'Alquileres', 2),
(7, 'Reembolsos', 2),
(8, 'Regalos', 2),
(9, 'Ayuda familiar', 2),
(10, 'Becas', 2),
(11, 'Ventas ocasionales', 2),
(12, 'Otros ingresos', 2);


INSERT INTO ingresos(id_usuario, id_ingreso, nombre, monto, fecha, descripcion, id_categoria, id_estado)
VALUES
(1, 1, 'Salario mensual', 250000.00, '2026-01-15', 'Salario recibido por trabajo en la empresa XYZ', 1, 2),
(1, 2, 'Reembolso de transporte', 12000.00, '2026-01-28', 'Reembolso por gastos de traslado laboral', 7, 2),
(1, 3, 'Salario marzo 2026', 480000.00, '2026-03-15', 'Salario mensual correspondiente a marzo de 2026', 1, 2),
(1, 4, 'Salario abril 2026', 480000.00, '2026-04-15', 'Salario mensual correspondiente a abril de 2026', 1, 2),
(1, 5, 'Salario mayo 2026', 480000.00, '2026-05-15', 'Salario mensual correspondiente a mayo de 2026', 1, 2),
(1, 6, 'Salario junio 2026', 480000.00, '2026-06-15', 'Salario mensual correspondiente a junio de 2026', 1, 2),
(1, 7, 'Salario julio 2026', 480000.00, '2026-07-15', 'Salario mensual correspondiente a julio de 2026', 1, 2),
(1, 8, 'Salario agosto 2026', 480000.00, '2026-08-15', 'Salario mensual correspondiente a agosto de 2026', 1, 2),
(2, 1, 'Proyecto freelance', 100000.00, '2026-02-10', 'Pago por proyecto de diseño web', 2, 2),
(2, 2, 'Venta de ilustraciones', 65000.00, '2026-02-22', 'Ingreso por venta de trabajos digitales', 11, 2),
(2, 3, 'Salario marzo 2026', 525000.00, '2026-03-15', 'Salario mensual correspondiente a marzo de 2026', 1, 2),
(2, 4, 'Salario abril 2026', 525000.00, '2026-04-15', 'Salario mensual correspondiente a abril de 2026', 1, 2),
(2, 5, 'Salario mayo 2026', 525000.00, '2026-05-15', 'Salario mensual correspondiente a mayo de 2026', 1, 2),
(2, 6, 'Salario junio 2026', 525000.00, '2026-06-15', 'Salario mensual correspondiente a junio de 2026', 1, 2),
(2, 7, 'Salario julio 2026', 525000.00, '2026-07-15', 'Salario mensual correspondiente a julio de 2026', 1, 2),
(2, 8, 'Salario agosto 2026', 525000.00, '2026-08-15', 'Salario mensual correspondiente a agosto de 2026', 1, 2),
(3, 1, 'Venta de productos', 50000.00, '2026-03-05', 'Ingresos por venta de productos en línea', 11, 2),
(3, 2, 'Bono por referidos', 30000.00, '2026-03-18', 'Bono recibido por recomendar nuevos clientes', 4, 2),
(3, 3, 'Regalo de aniversario', 45000.00, '2026-03-28', 'Dinero recibido como regalo familiar', 8, 2),
(3, 4, 'Salario marzo 2026', 560000.00, '2026-03-15', 'Salario mensual correspondiente a marzo de 2026', 1, 2),
(3, 5, 'Salario abril 2026', 560000.00, '2026-04-15', 'Salario mensual correspondiente a abril de 2026', 1, 2),
(3, 6, 'Salario mayo 2026', 560000.00, '2026-05-15', 'Salario mensual correspondiente a mayo de 2026', 1, 2),
(3, 7, 'Salario junio 2026', 560000.00, '2026-06-15', 'Salario mensual correspondiente a junio de 2026', 1, 2),
(3, 8, 'Salario julio 2026', 560000.00, '2026-07-15', 'Salario mensual correspondiente a julio de 2026', 1, 2),
(3, 9, 'Salario agosto 2026', 560000.00, '2026-08-15', 'Salario mensual correspondiente a agosto de 2026', 1, 2),
(4, 1, 'Alquiler de propiedad', 975000.00, '2026-04-01', 'Ingreso mensual por alquiler de propiedad', 6, 2),
(4, 2, 'Intereses de ahorro', 18500.00, '2026-04-20', 'Intereses generados por cuenta de ahorro', 5, 2),
(4, 3, 'Salario marzo 2026', 610000.00, '2026-03-15', 'Salario mensual correspondiente a marzo de 2026', 1, 2),
(4, 4, 'Salario abril 2026', 610000.00, '2026-04-15', 'Salario mensual correspondiente a abril de 2026', 1, 2),
(4, 5, 'Salario mayo 2026', 610000.00, '2026-05-15', 'Salario mensual correspondiente a mayo de 2026', 1, 2),
(4, 6, 'Salario junio 2026', 610000.00, '2026-06-15', 'Salario mensual correspondiente a junio de 2026', 1, 2),
(4, 7, 'Salario julio 2026', 610000.00, '2026-07-15', 'Salario mensual correspondiente a julio de 2026', 1, 2),
(4, 8, 'Salario agosto 2026', 610000.00, '2026-08-15', 'Salario mensual correspondiente a agosto de 2026', 1, 2),
(5, 1, 'Bono de desempeño', 18000.00, '2026-05-20', 'Bono recibido por buen desempeño laboral', 4, 2),
(5, 2, 'Ayuda familiar', 50000.00, '2026-05-30', 'Ayuda económica recibida para cubrir gastos del mes', 9, 2),
(5, 3, 'Salario marzo 2026', 675000.00, '2026-03-15', 'Salario mensual correspondiente a marzo de 2026', 1, 2),
(5, 4, 'Salario abril 2026', 675000.00, '2026-04-15', 'Salario mensual correspondiente a abril de 2026', 1, 2),
(5, 5, 'Salario mayo 2026', 675000.00, '2026-05-15', 'Salario mensual correspondiente a mayo de 2026', 1, 2),
(5, 6, 'Salario junio 2026', 675000.00, '2026-06-15', 'Salario mensual correspondiente a junio de 2026', 1, 2),
(5, 7, 'Salario julio 2026', 675000.00, '2026-07-15', 'Salario mensual correspondiente a julio de 2026', 1, 2),
(5, 8, 'Salario agosto 2026', 675000.00, '2026-08-15', 'Salario mensual correspondiente a agosto de 2026', 1, 2);

INSERT INTO categorias_gasto (id_categoria, nombre, id_estado)
VALUES
(1, 'Alimentación', 2),
(2, 'Transporte', 2),
(3, 'Salud', 2),
(4, 'Educación', 2),
(5, 'Ocio', 2),
(6, 'Membresías y suscripciones', 2),
(7, 'Ahorros programados', 2),
(8, 'Pagos municipales', 2),
(9, 'Tarjetas de crédito', 2),
(10, 'Servicios básicos', 2),
(11, 'Otros gastos', 2);

INSERT INTO gastos(id_usuario, id_gasto, nombre, monto, fecha, descripcion, id_categoria, id_estado)
VALUES
(1, 1, 'Supermercado mensual', 85000.00, '2026-01-20', 'Compra de alimentos y productos de limpieza', 1, 2),
(1, 2, 'Almuerzo familiar', 15000.00, '2026-08-01', 'Almuerzo fuera de casa', 1, 2),
(1, 3, 'Recarga de transporte', 11500.00, '2026-07-24', 'Recarga de transporte público', 2, 2),
(1, 4, 'Viaje en taxi', 7500.00, '2026-08-01', 'Traslado en taxi', 2, 2),
(1, 5, 'Compra de medicamentos', 16500.00, '2026-07-28', 'Medicamentos de farmacia', 3, 2),
(1, 6, 'Consulta médica', 32500.00, '2026-08-02', 'Consulta médica general', 3, 2),
(1, 7, 'Supermercado marzo 2026', 53000.00, '2026-03-05', 'Compra mensual de alimentos de marzo', 1, 2),
(1, 8, 'Transporte marzo 2026', 18333.00, '2026-03-12', 'Gastos mensuales de transporte de marzo', 2, 2),
(1, 9, 'Servicios básicos marzo 2026', 42500.00, '2026-03-20', 'Pago de servicios básicos de marzo', 10, 2),
(1, 10, 'Supermercado abril 2026', 53750.00, '2026-04-05', 'Compra mensual de alimentos de abril', 1, 2),
(1, 11, 'Transporte abril 2026', 18583.00, '2026-04-12', 'Gastos mensuales de transporte de abril', 2, 2),
(1, 12, 'Servicios básicos abril 2026', 42875.00, '2026-04-20', 'Pago de servicios básicos de abril', 10, 2),
(1, 13, 'Supermercado mayo 2026', 54500.00, '2026-05-05', 'Compra mensual de alimentos de mayo', 1, 2),
(1, 14, 'Transporte mayo 2026', 18833.00, '2026-05-12', 'Gastos mensuales de transporte de mayo', 2, 2),
(1, 15, 'Servicios básicos mayo 2026', 43250.00, '2026-05-20', 'Pago de servicios básicos de mayo', 10, 2),
(1, 16, 'Supermercado junio 2026', 55250.00, '2026-06-05', 'Compra mensual de alimentos de junio', 1, 2),
(1, 17, 'Transporte junio 2026', 19083.00, '2026-06-12', 'Gastos mensuales de transporte de junio', 2, 2),
(1, 18, 'Servicios básicos junio 2026', 43625.00, '2026-06-20', 'Pago de servicios básicos de junio', 10, 2),
(1, 19, 'Supermercado julio 2026', 56000.00, '2026-07-05', 'Compra mensual de alimentos de julio', 1, 2),
(1, 20, 'Transporte julio 2026', 19333.00, '2026-07-12', 'Gastos mensuales de transporte de julio', 2, 2),
(1, 21, 'Servicios básicos julio 2026', 44000.00, '2026-07-20', 'Pago de servicios básicos de julio', 10, 2),
(1, 22, 'Supermercado agosto 2026', 56750.00, '2026-08-05', 'Compra mensual de alimentos de agosto', 1, 2),
(1, 23, 'Transporte agosto 2026', 19583.00, '2026-08-12', 'Gastos mensuales de transporte de agosto', 2, 2),
(1, 24, 'Servicios básicos agosto 2026', 44375.00, '2026-08-20', 'Pago de servicios básicos de agosto', 10, 2),
(1, 25, 'Reparación de vehículo marzo 2026', 65000.00, '2026-03-25', 'Reparación extraordinaria del vehículo', 2, 2),
(1, 26, 'Compra de electrodoméstico mayo 2026', 85000.00, '2026-05-25', 'Compra extraordinaria para el hogar', 11, 2),
(1, 27, 'Actividad recreativa julio 2026', 40000.00, '2026-07-25', 'Salida recreativa familiar', 5, 2),
(2, 1, 'Pasajes transporte', 15000.00, '2026-01-10', 'Gastos en bus', 2, 2),
(2, 2, 'Compra de supermercado', 43000.00, '2026-07-26', 'Compra de alimentos', 1, 2),
(2, 3, 'Almuerzo familiar', 15500.00, '2026-08-01', 'Almuerzo fuera de casa', 1, 2),
(2, 4, 'Viaje en taxi', 8000.00, '2026-08-01', 'Traslado en taxi', 2, 2),
(2, 5, 'Compra de medicamentos', 17000.00, '2026-07-28', 'Medicamentos de farmacia', 3, 2),
(2, 6, 'Consulta médica', 33000.00, '2026-08-02', 'Consulta médica general', 3, 2),
(2, 7, 'Supermercado marzo 2026', 54000.00, '2026-03-05', 'Compra mensual de alimentos de marzo', 1, 2),
(2, 8, 'Transporte marzo 2026', 18667.00, '2026-03-12', 'Gastos mensuales de transporte de marzo', 2, 2),
(2, 9, 'Servicios básicos marzo 2026', 43000.00, '2026-03-20', 'Pago de servicios básicos de marzo', 10, 2),
(2, 10, 'Supermercado abril 2026', 54750.00, '2026-04-05', 'Compra mensual de alimentos de abril', 1, 2),
(2, 11, 'Transporte abril 2026', 18917.00, '2026-04-12', 'Gastos mensuales de transporte de abril', 2, 2),
(2, 12, 'Servicios básicos abril 2026', 43375.00, '2026-04-20', 'Pago de servicios básicos de abril', 10, 2),
(2, 13, 'Supermercado mayo 2026', 55500.00, '2026-05-05', 'Compra mensual de alimentos de mayo', 1, 2),
(2, 14, 'Transporte mayo 2026', 19167.00, '2026-05-12', 'Gastos mensuales de transporte de mayo', 2, 2),
(2, 15, 'Servicios básicos mayo 2026', 43750.00, '2026-05-20', 'Pago de servicios básicos de mayo', 10, 2),
(2, 16, 'Supermercado junio 2026', 56250.00, '2026-06-05', 'Compra mensual de alimentos de junio', 1, 2),
(2, 17, 'Transporte junio 2026', 19417.00, '2026-06-12', 'Gastos mensuales de transporte de junio', 2, 2),
(2, 18, 'Servicios básicos junio 2026', 44125.00, '2026-06-20', 'Pago de servicios básicos de junio', 10, 2),
(2, 19, 'Supermercado julio 2026', 57000.00, '2026-07-05', 'Compra mensual de alimentos de julio', 1, 2),
(2, 20, 'Transporte julio 2026', 19667.00, '2026-07-12', 'Gastos mensuales de transporte de julio', 2, 2),
(2, 21, 'Servicios básicos julio 2026', 44500.00, '2026-07-20', 'Pago de servicios básicos de julio', 10, 2),
(2, 22, 'Supermercado agosto 2026', 57750.00, '2026-08-05', 'Compra mensual de alimentos de agosto', 1, 2),
(2, 23, 'Transporte agosto 2026', 19917.00, '2026-08-12', 'Gastos mensuales de transporte de agosto', 2, 2),
(2, 24, 'Servicios básicos agosto 2026', 44875.00, '2026-08-20', 'Pago de servicios básicos de agosto', 10, 2),
(3, 1, 'Suscripción plataforma', 12000.00, '2026-01-05', 'Membresía de música en línea', 6, 2),
(3, 2, 'Compra de supermercado', 43500.00, '2026-07-26', 'Compra de alimentos', 1, 2),
(3, 3, 'Almuerzo familiar', 16000.00, '2026-08-01', 'Almuerzo fuera de casa', 1, 2),
(3, 4, 'Recarga de transporte', 12500.00, '2026-07-24', 'Recarga de transporte público', 2, 2),
(3, 5, 'Viaje en taxi', 8500.00, '2026-08-01', 'Traslado en taxi', 2, 2),
(3, 6, 'Compra de medicamentos', 17500.00, '2026-07-28', 'Medicamentos de farmacia', 3, 2),
(3, 7, 'Consulta médica', 33500.00, '2026-08-02', 'Consulta médica general', 3, 2),
(3, 8, 'Supermercado marzo 2026', 55000.00, '2026-03-05', 'Compra mensual de alimentos de marzo', 1, 2),
(3, 9, 'Transporte marzo 2026', 19000.00, '2026-03-12', 'Gastos mensuales de transporte de marzo', 2, 2),
(3, 10, 'Servicios básicos marzo 2026', 43500.00, '2026-03-20', 'Pago de servicios básicos de marzo', 10, 2),
(3, 11, 'Supermercado abril 2026', 55750.00, '2026-04-05', 'Compra mensual de alimentos de abril', 1, 2),
(3, 12, 'Transporte abril 2026', 19250.00, '2026-04-12', 'Gastos mensuales de transporte de abril', 2, 2),
(3, 13, 'Servicios básicos abril 2026', 43875.00, '2026-04-20', 'Pago de servicios básicos de abril', 10, 2),
(3, 14, 'Supermercado mayo 2026', 56500.00, '2026-05-05', 'Compra mensual de alimentos de mayo', 1, 2),
(3, 15, 'Transporte mayo 2026', 19500.00, '2026-05-12', 'Gastos mensuales de transporte de mayo', 2, 2),
(3, 16, 'Servicios básicos mayo 2026', 44250.00, '2026-05-20', 'Pago de servicios básicos de mayo', 10, 2),
(3, 17, 'Supermercado junio 2026', 57250.00, '2026-06-05', 'Compra mensual de alimentos de junio', 1, 2),
(3, 18, 'Transporte junio 2026', 19750.00, '2026-06-12', 'Gastos mensuales de transporte de junio', 2, 2),
(3, 19, 'Servicios básicos junio 2026', 44625.00, '2026-06-20', 'Pago de servicios básicos de junio', 10, 2),
(3, 20, 'Supermercado julio 2026', 58000.00, '2026-07-05', 'Compra mensual de alimentos de julio', 1, 2),
(3, 21, 'Transporte julio 2026', 20000.00, '2026-07-12', 'Gastos mensuales de transporte de julio', 2, 2),
(3, 22, 'Servicios básicos julio 2026', 45000.00, '2026-07-20', 'Pago de servicios básicos de julio', 10, 2),
(3, 23, 'Supermercado agosto 2026', 58750.00, '2026-08-05', 'Compra mensual de alimentos de agosto', 1, 2),
(3, 24, 'Transporte agosto 2026', 20250.00, '2026-08-12', 'Gastos mensuales de transporte de agosto', 2, 2),
(3, 25, 'Servicios básicos agosto 2026', 45375.00, '2026-08-20', 'Pago de servicios básicos de agosto', 10, 2),
(4, 1, 'Ahorro programado', 50000.00, '2026-01-01', 'Ahorro automático mensual', 7, 2),
(4, 2, 'Compra de supermercado', 44000.00, '2026-07-26', 'Compra de alimentos', 1, 2),
(4, 3, 'Almuerzo familiar', 16500.00, '2026-08-01', 'Almuerzo fuera de casa', 1, 2),
(4, 4, 'Recarga de transporte', 13000.00, '2026-07-24', 'Recarga de transporte público', 2, 2),
(4, 5, 'Viaje en taxi', 9000.00, '2026-08-01', 'Traslado en taxi', 2, 2),
(4, 6, 'Compra de medicamentos', 18000.00, '2026-07-28', 'Medicamentos de farmacia', 3, 2),
(4, 7, 'Consulta médica', 34000.00, '2026-08-02', 'Consulta médica general', 3, 2),
(4, 8, 'Supermercado marzo 2026', 56000.00, '2026-03-05', 'Compra mensual de alimentos de marzo', 1, 2),
(4, 9, 'Transporte marzo 2026', 19333.00, '2026-03-12', 'Gastos mensuales de transporte de marzo', 2, 2),
(4, 10, 'Servicios básicos marzo 2026', 44000.00, '2026-03-20', 'Pago de servicios básicos de marzo', 10, 2),
(4, 11, 'Supermercado abril 2026', 56750.00, '2026-04-05', 'Compra mensual de alimentos de abril', 1, 2),
(4, 12, 'Transporte abril 2026', 19583.00, '2026-04-12', 'Gastos mensuales de transporte de abril', 2, 2),
(4, 13, 'Servicios básicos abril 2026', 44375.00, '2026-04-20', 'Pago de servicios básicos de abril', 10, 2),
(4, 14, 'Supermercado mayo 2026', 57500.00, '2026-05-05', 'Compra mensual de alimentos de mayo', 1, 2),
(4, 15, 'Transporte mayo 2026', 19833.00, '2026-05-12', 'Gastos mensuales de transporte de mayo', 2, 2),
(4, 16, 'Servicios básicos mayo 2026', 44750.00, '2026-05-20', 'Pago de servicios básicos de mayo', 10, 2),
(4, 17, 'Supermercado junio 2026', 58250.00, '2026-06-05', 'Compra mensual de alimentos de junio', 1, 2),
(4, 18, 'Transporte junio 2026', 20083.00, '2026-06-12', 'Gastos mensuales de transporte de junio', 2, 2),
(4, 19, 'Servicios básicos junio 2026', 45125.00, '2026-06-20', 'Pago de servicios básicos de junio', 10, 2),
(4, 20, 'Supermercado julio 2026', 59000.00, '2026-07-05', 'Compra mensual de alimentos de julio', 1, 2),
(4, 21, 'Transporte julio 2026', 20333.00, '2026-07-12', 'Gastos mensuales de transporte de julio', 2, 2),
(4, 22, 'Servicios básicos julio 2026', 45500.00, '2026-07-20', 'Pago de servicios básicos de julio', 10, 2),
(4, 23, 'Supermercado agosto 2026', 59750.00, '2026-08-05', 'Compra mensual de alimentos de agosto', 1, 2),
(4, 24, 'Transporte agosto 2026', 20583.00, '2026-08-12', 'Gastos mensuales de transporte de agosto', 2, 2),
(4, 25, 'Servicios básicos agosto 2026', 45875.00, '2026-08-20', 'Pago de servicios básicos de agosto', 10, 2),
(5, 1, 'Pago municipal trimestral', 78000.00, '2026-03-15', 'Pago de impuesto municipal trimestral', 8, 2),
(5, 2, 'Pago tarjeta de crédito', 250000.00, '2026-01-25', 'Pago mensual de tarjeta de crédito', 9, 2),
(5, 3, 'Compra de supermercado', 48500.00, '2026-07-27', 'Compra semanal de alimentos', 1, 2),
(5, 4, 'Almuerzo', 8500.00, '2026-08-01', 'Almuerzo fuera de casa', 1, 2),
(5, 5, 'Recarga de transporte', 12000.00, '2026-07-25', 'Recarga mensual de tarjeta de transporte', 2, 2),
(5, 6, 'Viaje en taxi', 6500.00, '2026-08-01', 'Traslado en taxi', 2, 2),
(5, 7, 'Compra de medicamentos', 18750.00, '2026-07-29', 'Medicamentos de farmacia', 3, 2),
(5, 8, 'Consulta médica', 35000.00, '2026-08-02', 'Consulta médica general', 3, 2),
(5, 9, 'Supermercado marzo 2026', 57000.00, '2026-03-05', 'Compra mensual de alimentos de marzo', 1, 2),
(5, 10, 'Transporte marzo 2026', 19667.00, '2026-03-12', 'Gastos mensuales de transporte de marzo', 2, 2),
(5, 11, 'Servicios básicos marzo 2026', 44500.00, '2026-03-20', 'Pago de servicios básicos de marzo', 10, 2),
(5, 12, 'Supermercado abril 2026', 57750.00, '2026-04-05', 'Compra mensual de alimentos de abril', 1, 2),
(5, 13, 'Transporte abril 2026', 19917.00, '2026-04-12', 'Gastos mensuales de transporte de abril', 2, 2),
(5, 14, 'Servicios básicos abril 2026', 44875.00, '2026-04-20', 'Pago de servicios básicos de abril', 10, 2),
(5, 15, 'Supermercado mayo 2026', 58500.00, '2026-05-05', 'Compra mensual de alimentos de mayo', 1, 2),
(5, 16, 'Transporte mayo 2026', 20167.00, '2026-05-12', 'Gastos mensuales de transporte de mayo', 2, 2),
(5, 17, 'Servicios básicos mayo 2026', 45250.00, '2026-05-20', 'Pago de servicios básicos de mayo', 10, 2),
(5, 18, 'Supermercado junio 2026', 59250.00, '2026-06-05', 'Compra mensual de alimentos de junio', 1, 2),
(5, 19, 'Transporte junio 2026', 20417.00, '2026-06-12', 'Gastos mensuales de transporte de junio', 2, 2),
(5, 20, 'Servicios básicos junio 2026', 45625.00, '2026-06-20', 'Pago de servicios básicos de junio', 10, 2),
(5, 21, 'Supermercado julio 2026', 60000.00, '2026-07-05', 'Compra mensual de alimentos de julio', 1, 2),
(5, 22, 'Transporte julio 2026', 20667.00, '2026-07-12', 'Gastos mensuales de transporte de julio', 2, 2),
(5, 23, 'Servicios básicos julio 2026', 46000.00, '2026-07-20', 'Pago de servicios básicos de julio', 10, 2),
(5, 24, 'Supermercado agosto 2026', 60750.00, '2026-08-05', 'Compra mensual de alimentos de agosto', 1, 2),
(5, 25, 'Transporte agosto 2026', 20917.00, '2026-08-12', 'Gastos mensuales de transporte de agosto', 2, 2),
(5, 26, 'Servicios básicos agosto 2026', 46375.00, '2026-08-20', 'Pago de servicios básicos de agosto', 10, 2);

INSERT INTO recordatorios_pago(id_usuario, id_recordatorio, nombre, monto, fecha_pago, descripcion, id_categoria, id_estado)
VALUES
(1, 1, 'Suscripción Netflix', 15000.00, '2026-02-05', 'Renovación mensual de membresía Netflix', 6, 2),
(2, 1, 'Pago de luz', 35000.00, '2026-02-15', 'Servicio de energía eléctrica mensual', 10, 2),
(2, 2, 'Pago de agua', 25000.00, '2026-02-15', 'Servicio de suministro de agua potable mensual', 10, 2),
(2, 3, 'Pago de internet', 25000.00, '2026-02-15', 'Servicio de internet residencial mensual', 10, 2),
(3, 1, 'Cuota de seguro', 120000.00, '2026-02-20', 'Pago mensual de seguro del automóvil', 11, 2),
(4, 1, 'Pago de impuestos municipales', 78000.00, '2026-03-15', 'Impuesto municipal trimestral', 8, 2);

INSERT INTO metas_ahorro(id_usuario, id_meta, nombre, monto_inicial, monto_objetivo, cuota, fecha_inicio, fecha_cumplimiento, descripcion, id_estado)
VALUES
(1, 1, 'Viaje a Costa Rica', 50000.00, 1500000.00, 50000.00, '2025-06-01', '2027-11-30', 'Ubicación: Cuenta del Banco Nacional', 2),
(1, 2, 'Fondo para vehículo', 40000.00, 1200000.00, 40000.00, '2025-07-01', '2027-12-31', 'Ubicación: cuenta del Banco Popular', 2),
(1, 3, 'Hogar propio', 75000.00, 3000000.00, 75000.00, '2025-02-01', '2028-05-31', 'Ubicación: Cuenta de ahorro para vivienda en Banco de Costa Rica', 2),
(1, 4, 'Vacaciones familiares', 30000.00, 600000.00, 30000.00, '2025-08-15', '2027-03-31', 'Ubicación: Cuenta de ahorro para vacaciones en Banco Popular', 2),
(2, 1, 'Fondo de emergencia', 60000.00, 2000000.00, 60000.00, '2025-01-01', '2027-10-31', 'Ubicación: Cuenta del Banco Nacional', 2),
(2, 2, 'Universidad de los hijos', 50000.00, 1800000.00, 50000.00, '2025-04-01', '2028-03-31', 'Ubicación: cuenta del Banco Popular', 2),
(2, 3, 'Renovación de electrodomésticos', 25000.00, 700000.00, 25000.00, '2025-10-01', '2028-01-31', 'Ubicación: sobre del Banco de Costa Rica', 2),
(2, 4, 'Viaje de aniversario', 30000.00, 500000.00, 30000.00, '2025-05-10', '2026-12-15', 'Ubicación: Cuenta de ahorro para viajes en Banco Popular', 2),
(3, 1, 'Compra de laptop', 60000.00, 2500000.00, 60000.00, '2025-03-15', '2028-08-31', 'Ubicación: Cuenta del Banco Nacional', 2),
(3, 2, 'Equipo de trabajo', 40000.00, 1200000.00, 40000.00, '2025-06-20', '2027-11-30', 'Ubicación: cuenta del Banco Popular', 2),
(3, 3, 'Curso avanzado', 20000.00, 400000.00, 20000.00, '2025-03-05', '2026-10-31', 'Ubicación: sobre del Banco de Costa Rica', 2),
(3, 4, 'Fondo de inversión', 50000.00, 2500000.00, 50000.00, '2025-01-15', '2029-02-28', 'Ubicación: Pensión Complementaria en Banco Popular', 2),
(4, 1, 'Educación continua', 35000.00, 1000000.00, 35000.00, '2025-09-01', '2028-01-31', 'Ubicación: Cuenta del Banco Nacional', 2),
(4, 2, 'Emergencias médicas', 30000.00, 1000000.00, 30000.00, '2025-08-01', '2028-05-31', 'Ubicación: cuenta del Banco Popular', 2),
(4, 3, 'Viaje internacional', 45000.00, 2500000.00, 45000.00, '2025-09-10', '2030-04-30', 'Ubicación: sobre del Banco de Costa Rica', 2),
(4, 4, 'Automóvil nuevo', 60000.00, 4000000.00, 60000.00, '2025-04-20', '2030-10-31', 'Ubicación: Cuenta de ahorro para vehículo en Banco Popular', 2),
(5, 1, 'Ahorro a largo plazo', 50000.00, 5000000.00, 50000.00, '2024-01-01', '2032-04-30', 'Ubicación: Cuenta del Banco Nacional', 2),
(5, 2, 'Fondo para negocio', 40000.00, 3000000.00, 40000.00, '2025-02-01', '2031-04-30', 'Ubicación: cuenta del Banco Popular', 2),
(5, 3, 'Equipo de oficina', 25000.00, 900000.00, 25000.00, '2025-07-10', '2028-06-30', 'Ubicación: sobre del Banco de Costa Rica', 2),
(5, 4, 'Reserva de retiro', 60000.00, 6000000.00, 60000.00, '2024-11-01', '2033-02-28', 'Ubicación: Pensión Complementaria en Banco Popular', 2);


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

SELECT
    i.id_ingreso,
    i.nombre AS ingreso,
    i.monto,
    i.fecha,
    i.descripcion,
    ci.nombre AS categoria,
    i.id_estado
FROM ingresos AS i
INNER JOIN categorias_ingreso AS ci
    ON i.id_categoria = ci.id_categoria
WHERE i.id_usuario = 5;
