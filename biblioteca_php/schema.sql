-- ============================================================
-- SISTEMA DE BIBLIOTECA MUNICIPAL
-- Script de inicialización de base de datos MySQL
-- ============================================================

CREATE DATABASE IF NOT EXISTS bd_bibliotecamunicipal
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE bd_bibliotecamunicipal;

-- ===================== TABLA: libros =====================
CREATE TABLE IF NOT EXISTS libros (
  id          VARCHAR(20)  NOT NULL PRIMARY KEY,
  titulo      VARCHAR(255) NOT NULL,
  autor       VARCHAR(255) NOT NULL,
  isbn        VARCHAR(30)  DEFAULT NULL,
  disponible  TINYINT(1)   NOT NULL DEFAULT 1,
  creado_en   TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_disponible (disponible)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ===================== TABLA: usuarios =====================
CREATE TABLE IF NOT EXISTS usuarios (
  id                VARCHAR(20)  NOT NULL PRIMARY KEY,
  nombre            VARCHAR(255) NOT NULL,
  email             VARCHAR(255) DEFAULT NULL,
  telefono          VARCHAR(20)  DEFAULT NULL,
  prestamos_activos JSON         DEFAULT NULL COMMENT 'Array JSON con IDs de libros prestados activos',
  creado_en         TIMESTAMP    DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ===================== TABLA: prestamos =====================
CREATE TABLE IF NOT EXISTS prestamos (
  id                VARCHAR(30)  NOT NULL PRIMARY KEY,
  libro_id          VARCHAR(20)  NOT NULL,
  usuario_id        VARCHAR(20)  NOT NULL,
  fecha_prestamo    DATETIME     NOT NULL,
  fecha_devolucion  DATETIME     DEFAULT NULL,
  estado            ENUM('activo','devuelto','reservado') NOT NULL DEFAULT 'activo',
  creado_en         TIMESTAMP    DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_prestamo_libro   FOREIGN KEY (libro_id)   REFERENCES libros(id),
  CONSTRAINT fk_prestamo_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
  INDEX idx_estado     (estado),
  INDEX idx_usuario_id (usuario_id),
  INDEX idx_libro_id   (libro_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ===================== TABLA: reservas =====================
CREATE TABLE IF NOT EXISTS reservas (
  id            INT          NOT NULL AUTO_INCREMENT PRIMARY KEY,
  libro_id      VARCHAR(20)  NOT NULL,
  usuario_id    VARCHAR(20)  NOT NULL,
  fecha_reserva DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  estado        ENUM('pendiente','atendida','cancelada') NOT NULL DEFAULT 'pendiente',
  CONSTRAINT fk_reserva_libro   FOREIGN KEY (libro_id)   REFERENCES libros(id),
  CONSTRAINT fk_reserva_usuario FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
  INDEX idx_reserva_estado (estado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ===================== DATOS INICIALES =====================
INSERT IGNORE INTO libros (id, titulo, autor, isbn, disponible) VALUES
  ('L001', 'Cien Años de Soledad',   'Gabriel García Márquez', '978-0-06-088328-7', 1),
  ('L002', 'El Olimpo',              'Nancy Samaniego',        '978-0-15-601219-5', 1),
  ('L003', 'Quimica Analitica',      'Carlos Ramirez',         '978-0-45-228285-3', 1),
  ('L004', 'Geometria Plana',        'Nicol Miranda',          '978-1-4028-9462-6', 1),
  ('L005', 'Algebra Lineal',         'Jeferson Avelino',       '979-1-2345-6789-0', 1);

INSERT IGNORE INTO usuarios (id, nombre, email, telefono, prestamos_activos) VALUES
  ('U001', 'Luz Robles',          'lurobles@gmail.com',     '987654321', '[]'),
  ('U002', 'Luis Ramos',          'luis@gmail.com',         '912345678', '[]'),
  ('U003', 'Maria Llanos',        'maria@gmail.com',        '976541945', '[]'),
  ('U004', 'Aurelio Gergurevich', 'aurgergurevich@gmail.com','942923394', '[]');

SELECT 'Base de datos inicializada correctamente.' AS mensaje;
