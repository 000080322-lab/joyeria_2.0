CREATE DATABASE IF NOT EXISTS joyeria_db;
USE joyeria_db;

CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(180) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin','user') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS sales (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    sale_date DATE NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    description VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Datos de ejemplo
INSERT INTO users (name, email, password_hash, role) VALUES
('Administrador', 'admin@brillojuvenil.com', '$2y$10$8t7/3b0/4Mjuv0NbeKlyU.v/M1rL3J9v4ZuE6jTn.kM6zya6h2QgC', 'admin'),
('Usuario Demo', 'user@brillojuvenil.com', '$2y$10$8t7/3b0/4Mjuv0NbeKlyU.v/M1rL3J9v4ZuE6jTn.kM6zya6h2QgC', 'user')
ON DUPLICATE KEY UPDATE email = VALUES(email);

INSERT INTO sales (sale_date, total_amount, description) VALUES
('2026-07-09', 350.00, 'Venta de collar y anillo'),
('2026-07-08', 210.50, 'Pulsera premium'),
('2026-07-07', 480.00, 'Set de fiesta');
