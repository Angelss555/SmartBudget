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
    apellido_materno VARCHAR(50),
    apellido_paterno VARCHAR(50),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    id_estado INT,
    CONSTRAINT usuarios_id_estado_fk FOREIGN KEY(id_estado) REFERENCES estados(id_estado)
);

CREATE TABLE categorias_ingreso(
	id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    id_estado INT,
    CONSTRAINT categorias_ingreso_id_estado_fk FOREIGN KEY(id_estado) REFERENCES estados(id_estado)
);

CREATE TABLE ingresos(
	id_ingreso INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    monto DECIMAL(12,2),
    fecha DATE,
    descripcion TEXT,
    id_categoria INT,
    id_usuario INT,
    id_estado INT,
    CONSTRAINT ingresos_id_categoria_fk FOREIGN KEY(id_categoria) REFERENCES categorias_ingreso(id_categoria),
    CONSTRAINT ingresos_id_usuario_fk FOREIGN KEY(id_usuario) REFERENCES usuarios(id_usuario),
    CONSTRAINT ingresos_id_estado_fk FOREIGN KEY(id_estado) REFERENCES estados(id_estado)
);

CREATE TABLE categorias_gasto(
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    id_estado INT,
    CONSTRAINT categorias_gasto_id_estado_fk FOREIGN KEY(id_estado) REFERENCES estados(id_estado)
);

CREATE TABLE gastos(
    id_gasto INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    monto DECIMAL(12,2),
    fecha DATE,
    descripcion TEXT,
    id_categoria INT,
    id_usuario INT,
    id_estado INT,
    CONSTRAINT gastos_id_categoria_fk FOREIGN KEY(id_categoria) REFERENCES categorias_gasto(id_categoria),
    CONSTRAINT gastos_id_usuario_fk FOREIGN KEY(id_usuario) REFERENCES usuarios(id_usuario),
    CONSTRAINT gastos_id_estado_fk FOREIGN KEY(id_estado) REFERENCES estados(id_estado)
);

CREATE TABLE recordatorios_pago(
    id_recordatorio INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    monto DECIMAL(12,2),
    fecha_pago DATE,
    descripcion TEXT,
    id_categoria INT,
    id_usuario INT,
    id_estado INT,
    CONSTRAINT recordatorios_pago_id_categoria_fk FOREIGN KEY(id_categoria) REFERENCES categorias_gasto(id_categoria),
    CONSTRAINT recordatorios_pago_id_usuario_fk FOREIGN KEY(id_usuario) REFERENCES usuarios(id_usuario),
    CONSTRAINT recordatorios_pago_id_estado_fk FOREIGN KEY(id_estado) REFERENCES estados(id_estado)
);

CREATE TABLE metas_ahorro(
    id_meta INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    monto_actual DECIMAL(12,2),
    monto_objetivo DECIMAL(12,2),
    fecha_inicio DATE,
    fecha_cumplimiento DATE,    
    descripcion TEXT,
    id_usuario INT,
    id_estado INT,
    CONSTRAINT metas_ahorro_id_usuario_fk FOREIGN KEY(id_usuario) REFERENCES usuarios(id_usuario),
    CONSTRAINT metas_ahorro_id_estado_fk FOREIGN KEY(id_estado) REFERENCES estados(id_estado)
);

CREATE TABLE TIPO_NOTIFICACION(
    id_tipo INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    id_estado INT,
    CONSTRAINT tipo_notificacion_id_estado_fk FOREIGN KEY(id_estado) REFERENCES estados(id_estado)
);

CREATE TABLE NOTIFICACIONES(
    id_notificacion INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(100),
    mensaje TEXT,
    fecha_creacion DATE,
    leida BOOLEAN DEFAULT FALSE,
    enviada_correo BOOLEAN DEFAULT FALSE,
    id_tipo INT,
    id_usuario INT,
    id_estado INT,
    CONSTRAINT notificaciones_id_tipo_fk FOREIGN KEY(id_tipo) REFERENCES TIPO_NOTIFICACION(id_tipo),
    CONSTRAINT notificaciones_id_usuario_fk FOREIGN KEY(id_usuario) REFERENCES usuarios(id_usuario),
    CONSTRAINT notificaciones_id_estado_fk FOREIGN KEY(id_estado) REFERENCES estados(id_estado)
);

CREATE TABLE CONFIGURACIONES_NOTIF(
    id_configuracion INT AUTO_INCREMENT PRIMARY KEY,
    notif_app BOOLEAN DEFAULT TRUE,
    notif_correo BOOLEAN DEFAULT TRUE,
    id_tipo INT,
    id_usuario INT,
    id_estado INT,
    CONSTRAINT configuraciones_notif_id_tipo_fk FOREIGN KEY(id_tipo) REFERENCES TIPO_NOTIFICACION(id_tipo),
    CONSTRAINT configuraciones_notif_id_usuario_fk FOREIGN KEY(id_usuario) REFERENCES usuarios(id_usuario),
    CONSTRAINT configuraciones_notif_id_estado_fk FOREIGN KEY(id_estado) REFERENCES estados(id_estado)
);

CREATE TABLE PRESUPUESTOS_CATEGORIAS(
    id_presupuesto INT AUTO_INCREMENT PRIMARY KEY,
    monto_limite DECIMAL(12,2),
    mes INT,
    anio INT,
    id_categoria INT,
    id_usuario INT,
    id_estado INT,
    CONSTRAINT presupuestos_categorias_id_categoria_fk FOREIGN KEY(id_categoria) REFERENCES categorias_gasto(id_categoria),
    CONSTRAINT presupuestos_categorias_id_usuario_fk FOREIGN KEY(id_usuario) REFERENCES usuarios(id_usuario),
    CONSTRAINT presupuestos_categorias_id_estado_fk FOREIGN KEY(id_estado) REFERENCES estados(id_estado)
);

-- Datos de prueba precargados
INSERT INTO estados (nombre) VALUES
('inactivo'),
('activo');

-- Creación de 14 usuarios de prueba
INSERT INTO usuarios(nombre, apellido_materno, apellido_paterno, email, password, id_estado)
VALUES
('Donovan', 'Aguilar', 'Cárdenas', 'donovan@prueba.com', '123', 2),
('Jenny', 'Liang', 'Jiang', 'jenny@prueba.com', '123', 2),
('Ángel', 'Rodríguez', 'Vargas', 'angel@prueba.com', '123', 2),
('Xavier', 'Marín', 'Araya', 'xavier@prueba.com', '123', 2),
('María', 'González', 'Pérez', 'maria@prueba.com', '123', 2),
('Carlos', 'Ramírez', 'Sánchez', 'carlos@prueba.com', '123', 2),
('Lucía', 'Torres', 'Hernández', 'lucia@prueba.com', '123', 2),
('Diego', 'Fernández', 'López', 'diego@prueba.com', '123', 2),
('Sofía', 'Gómez', 'Martínez', 'sofia@prueba.com', '123', 2),
('Miguel', 'Hernández', 'García', 'miguel@prueba.com', '123', 2),
('Ana', 'Ruiz', 'Díaz', 'ana@prueba.com', '123', 2),
('Jorge', 'Sánchez', 'Jiménez', 'jorge@prueba.com', '123', 2),
('Carmen', 'Vargas', 'Silva', 'carmen@prueba.com', '123', 1),
('Ricardo', 'Morales', 'Castro', 'ricardo@prueba.com', '123', 1);

INSERT INTO categorias_ingreso (nombre, id_estado) VALUES
('Salario', 2),
('Freelance / Trabajo independiente', 2),
('Negocio propio', 2),
('Bonos y comisiones', 2),
('Inversiones', 2),
('Alquileres', 2),
('Reembolsos', 2),
('Regalos', 2),
('Ayuda familiar', 2),
('Becas', 2),
('Ventas ocasionales', 2),
('Otros ingresos', 2);


INSERT INTO ingresos(nombre, monto, fecha, descripcion, id_categoria, id_usuario, id_estado)
VALUES
('Salario mensual', 250000.00, '2026-01-15', 'Salario recibido por trabajo en la empresa XYZ', 1, 1, 2),
('Proyecto freelance', 100000.00, '2026-02-10', 'Pago por proyecto de diseño web', 2, 2, 2),
('Venta de productos', 50000.00, '2026-03-05', 'Ingresos por venta de productos en línea', 11, 3, 2),
('Alquiler de propiedad', 975000.00, '2026-04-01', 'Ingreso mensual por alquiler de propiedad', 6, 4, 2),
('Bono de desempeño', 18000.00, '2026-05-20', 'Bono recibido por buen desempeño laboral', 4, 5, 2),
('Inversión en acciones', 52000.00, '2026-06-15', 'Ganancia obtenida por inversión en acciones', 5, 6, 2),
('Reembolso de gastos médicos', 104000.00, '2026-07-10', 'Reembolso recibido por gastos médicos cubiertos por seguro', 7, 7, 2),
('Regalo de cumpleaños', 25000.00, '2026-08-25', 'Dinero recibido como regalo de cumpleaños de un amigo cercano', 8, 8, 2),
('Ayuda familiar', 50000.00, '2026-09-05', 'Ayuda económica recibida de un familiar para cubrir gastos imprevistos', 9, 9, 2),
('Beca académica', 920000.00, '2026-10-15', 'Beca otorgada por desempeño académico sobresaliente en la universidad', 10, 10, 2);

INSERT INTO categorias_gasto (nombre, id_estado) VALUES
('Alimentación', 2),
('Transporte', 2),
('Salud', 2),
('Educación', 2),
('Ocio', 2),
('Membresías y suscripciones', 2),
('Ahorros programados', 2),
('Pagos municipales', 2),
('Tarjetas de crédito', 2),
('Servicios básicos', 2),
('Otros gastos', 2);

INSERT INTO gastos(nombre, monto, fecha, descripcion, id_categoria, id_usuario, id_estado)
VALUES
('Supermercado mensual', 85000.00, '2026-01-20', 'Compra de alimentos y productos de limpieza', 1, 1, 2),
('Pasajes transporte', 15000.00, '2026-01-10', 'Gastos en bus', 2, 2, 2),
('Suscripción plataforma', 12000.00, '2026-01-05', 'Membresía de música en línea', 6, 6, 2),
('Pago tarjeta de crédito', 250000.00, '2026-01-25', 'Pago mensual de tarjeta de crédito', 9, 9, 2),
('Ahorro programado', 50000.00, '2026-01-01', 'Ahorro automático mensual', 7, 7, 2),
('Pago municipal trimestral', 78000.00, '2026-03-15', 'Pago de impuesto municipal trimestral', 8, 8, 2);

INSERT INTO recordatorios_pago(nombre, monto, fecha_pago, descripcion, id_categoria, id_usuario, id_estado)
VALUES
('Suscripción Netflix', 15000.00, '2026-02-05', 'Renovación mensual de membresía Netflix', 6, 1, 2),
('Pago de luz', 35000.00, '2026-02-15', 'Servicio de energía eléctrica mensual', 10, 2, 2),
('Pago de agua', 25000.00, '2026-02-15', 'Servicio de suministro de agua potable mensual', 10, 2, 2),
('Pago de internet', 25000.00, '2026-02-15', 'Servicio de internet residencial mensual', 10, 2, 2),
('Cuota de seguro', 120000.00, '2026-02-20', 'Pago mensual de seguro del automóvil', 11, 3, 2),
('Pago de impuestos municipales', 78000.00, '2026-03-15', 'Impuesto municipal trimestral', 8, 4, 2);

INSERT INTO metas_ahorro(nombre, monto_actual, monto_objetivo, fecha_inicio, fecha_cumplimiento, descripcion, id_usuario, id_estado)
VALUES
('Viaje a Costa Rica', 250000.00, 1500000.00, '2025-06-01', '2026-12-31', 'Ahorrar para un viaje vacacional a playas costarricenses', 1, 2),
('Fondo de emergencia', 500000.00, 2000000.00, '2025-01-01', '2026-06-30', 'Crear un fondo de emergencia para gastos inesperados', 2, 2),
('Compra de laptop', 800000.00, 2500000.00, '2025-03-15', '2026-09-30', 'Ahorrar para comprar una computadora portátil', 3, 2),
('Educación continua', 150000.00, 1000000.00, '2025-09-01', '2027-06-30', 'Inversión en cursos y certificaciones profesionales', 4, 2),
('Ahorro a largo plazo', 1200000.00, 5000000.00, '2024-01-01', '2028-12-31', 'Ahorro general para objetivos futuros', 5, 2);


INSERT INTO TIPO_NOTIFICACION(nombre, id_estado) VALUES
('Recordatorio de pago', 2),
('Meta de ahorro alcanzada', 2),
('Límite de gasto alcanzado', 2),
('Reporte mensual', 2),
('Progreso de meta de ahorro', 2),
('Seguridad de cuenta', 2);

INSERT INTO CONFIGURACIONES_NOTIF(notif_app, notif_correo, id_tipo, id_usuario, id_estado)
VALUES
(true, true, 1, 1, 2),
(true, false, 2, 2, 2),
(false, true, 3, 3, 2),
(true, true, 4, 4, 2),
(false, false, 5, 5, 2),
(true, true, 6, 6, 2);

INSERT INTO NOTIFICACIONES(titulo, mensaje, fecha_creacion, leida, enviada_correo, id_tipo, id_usuario, id_estado)
VALUES
('Recordatorio de pago', 'Recuerda que tienes un pago pendiente de tu suscripción a Netflix.', '2026-02-01', false, false, 1, 1, 2),
('Meta de ahorro alcanzada', '¡Felicidades! Has alcanzado tu meta de ahorro para tu viaje a Costa Rica.', '2026-12-31', false, false, 2, 1, 2),
('Límite de gasto alcanzado', 'Has alcanzado el límite de gasto establecido para la categoría de alimentación este mes.', '2026-01-31', false, false, 3, 1, 2),
('Reporte mensual', 'Tu reporte mensual de gastos e ingresos está listo para ser revisado.', '2026-02-01', false, false, 4, 1, 2),
('Progreso de meta de ahorro', 'Has alcanzado el 50% de tu meta de ahorro para la compra de tu laptop.', '2026-06-15', false, false, 5, 3, 2),
('Seguridad de cuenta', 'Se ha detectado un inicio de sesión sospechoso en tu cuenta. Por favor, verifica tu actividad reciente.', '2026-02-10', false, false, 6, 2, 2);

INSERT INTO PRESUPUESTOS_CATEGORIAS(monto_limite, mes, anio, id_categoria, id_usuario, id_estado)
VALUES
(100000.00, 1, 2026, 1, 1, 2),
(50000.00, 1, 2026, 2, 2, 2),
(200000.00, 1, 2026, 3, 3, 2),
(150000.00, 1, 2026, 4, 4, 2),
(80000.00, 1, 2026, 5, 5, 2),
(120000.00, 1, 2026, 6, 6, 2),
(60000.00, 1, 2026, 7, 7, 2),
(90000.00, 1, 2026, 8, 8, 2),
(70000.00, 1, 2026, 9, 9, 2),
(110000.00, 1, 2026, 10, 10, 2);