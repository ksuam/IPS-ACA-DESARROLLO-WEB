-- ============================================================
--  IPS ALMA VIDA - Sistema de Gestión de Pacientes
--  Base de datos MySQL
-- ============================================================

CREATE DATABASE IF NOT EXISTS alma_vida CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE alma_vida;

-- ------------------------------------------------------------
-- Tabla: usuarios (autenticación)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
    id            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name          VARCHAR(100)  NOT NULL,
    email         VARCHAR(150)  NOT NULL UNIQUE,
    password      VARCHAR(255)  NOT NULL,
    remember_token VARCHAR(100) NULL,
    created_at    TIMESTAMP     DEFAULT CURRENT_TIMESTAMP,
    updated_at    TIMESTAMP     DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- Tabla: empresas
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS empresas (
    id         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre     VARCHAR(150) NOT NULL,
    nit        VARCHAR(30)  NOT NULL UNIQUE,
    telefono   VARCHAR(20)  NULL,
    direccion  VARCHAR(200) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- Tabla: tipos_examen
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS tipos_examen (
    id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre      VARCHAR(100) NOT NULL,
    descripcion TEXT         NULL,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- Tabla: pacientes  (módulo principal CRUD)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS pacientes (
    id                  BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    -- Datos personales
    nombre_completo     VARCHAR(150) NOT NULL,
    tipo_documento      ENUM('CC','TI','CE','PA','RC') NOT NULL,
    numero_documento    VARCHAR(30)  NOT NULL UNIQUE,
    fecha_nacimiento    DATE         NOT NULL,
    edad                TINYINT UNSIGNED NOT NULL,
    direccion           VARCHAR(200) NOT NULL,
    telefono            VARCHAR(20)  NULL,
    celular             VARCHAR(20)  NOT NULL,
    eps                 VARCHAR(100) NOT NULL,

    -- Contacto adicional
    contacto_nombre     VARCHAR(150) NOT NULL,
    contacto_parentesco VARCHAR(60)  NOT NULL,
    contacto_telefono   VARCHAR(20)  NOT NULL,

    -- Examen y empresa
    empresa_id          BIGINT UNSIGNED NOT NULL,
    tipo_examen_id      BIGINT UNSIGNED NOT NULL,
    fecha_examen        DATE         NOT NULL,

    -- Estado del registro
    estado              ENUM('activo','cancelado','completado') DEFAULT 'activo',

    created_at          TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at          TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_paciente_empresa     FOREIGN KEY (empresa_id)     REFERENCES empresas(id)     ON DELETE RESTRICT,
    CONSTRAINT fk_paciente_tipo_examen FOREIGN KEY (tipo_examen_id) REFERENCES tipos_examen(id) ON DELETE RESTRICT,

    INDEX idx_paciente_documento (numero_documento),
    INDEX idx_paciente_fecha_examen (fecha_examen),
    INDEX idx_paciente_empresa (empresa_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- Datos de prueba
-- ------------------------------------------------------------
INSERT INTO users (name, email, password) VALUES
('Administrador', 'admin@almavida.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'); -- password: password

INSERT INTO empresas (nombre, nit, telefono, direccion) VALUES
('Constructora Horizonte SAS',  '900123456-1', '6014567890', 'Cra 15 # 80-20, Bogotá'),
('Logística del Norte Ltda',    '800987654-2', '6015678901', 'Av. El Dorado # 68B-35, Bogotá'),
('Distribuidora Central SA',    '700456789-3', '6016789012', 'Calle 13 # 37-12, Bogotá'),
('Servicios Técnicos JM SAS',   '600321654-4', '6017890123', 'Carrera 7 # 32-45, Bogotá');

INSERT INTO tipos_examen (nombre, descripcion) VALUES
('Ingreso',          'Examen médico ocupacional de ingreso a empresa'),
('Periódico',        'Control médico periódico anual'),
('Egreso',           'Examen médico al finalizar contrato laboral'),
('Post-incapacidad', 'Evaluación médica post-incapacidad superior a 30 días'),
('Altura',           'Certificación para trabajo en alturas'),
('Confinados',       'Certificación para espacios confinados');

INSERT INTO pacientes (nombre_completo, tipo_documento, numero_documento, fecha_nacimiento, edad,
                       direccion, telefono, celular, eps,
                       contacto_nombre, contacto_parentesco, contacto_telefono,
                       empresa_id, tipo_examen_id, fecha_examen, estado) VALUES
('Carlos Andrés Martínez López', 'CC', '1020304050', '1990-05-12', 34,
 'Calle 45 # 23-10, Bogotá', '6012345678', '3101234567', 'Sanitas',
 'María López', 'Madre', '3209876543', 1, 1, '2026-03-20', 'activo'),

('Laura Sofía Gómez Ramos', 'CC', '1030405060', '1995-08-22', 30,
 'Carrera 20 # 50-30, Bogotá', NULL, '3152345678', 'Compensar',
 'Pedro Gómez', 'Padre', '3163456789', 2, 2, '2026-03-22', 'activo'),

('Jhon Sebastián Torres Vargas', 'CC', '1040506070', '1988-11-03', 37,
 'Av. Boyacá # 12-45, Bogotá', '6013456789', '3183456789', 'Nueva EPS',
 'Ana Vargas', 'Esposa', '3004567890', 3, 5, '2026-03-25', 'activo');
