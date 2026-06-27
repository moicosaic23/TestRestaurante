CREATE DATABASE IF NOT EXISTS restaurant_mvc CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE restaurant_mvc;

DROP TABLE IF EXISTS order_items;
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS disable_reasons;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(100) DEFAULT NULL,
    role ENUM('admin','waiter','cook') DEFAULT NULL,
    approved TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL DEFAULT 0,
    created_by INT NULL,
    enabled TINYINT(1) DEFAULT 0,
    disabled_reason VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    waiter_id INT NOT NULL,
    status ENUM('pending','preparing','completed','cancelled','delivered') DEFAULT 'pending',
    total DECIMAL(10,2) DEFAULT 0,
    comment TEXT DEFAULT NULL,
    paid TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (waiter_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    qty INT NOT NULL DEFAULT 1,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE RESTRICT
);

CREATE TABLE disable_reasons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reason VARCHAR(255) NOT NULL
);

INSERT INTO disable_reasons (id, reason) VALUES
(1,'Falta de insumos'),
(2,'Mantenimiento'),
(3,'Temporalmente no disponible');

