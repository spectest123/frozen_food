CREATE DATABASE IF NOT EXISTS db_frozen_food;
USE db_frozen_food;

DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS suppliers;

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(100) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE suppliers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    supplier_name VARCHAR(100) NOT NULL,
    contact VARCHAR(50) DEFAULT NULL
) ENGINE=InnoDB;

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_name VARCHAR(150) NOT NULL,
    price INT NOT NULL,
    stock INT NOT NULL,
    category_id INT NOT NULL,
    supplier_id INT NOT NULL,
    CONSTRAINT fk_products_category FOREIGN KEY (category_id) REFERENCES categories(id),
    CONSTRAINT fk_products_supplier FOREIGN KEY (supplier_id) REFERENCES suppliers(id)
) ENGINE=InnoDB;

INSERT INTO categories (category_name) VALUES
('Nugget & Olahan Ayam'),
('Daging & Seafood'),
('Makanan Siap Saji');

INSERT INTO suppliers (supplier_name, contact) VALUES
('PT Frozen Jaya', '021-5551001'),
('CV Sumber Dingin', '0812-3456-7890'),
('UD Pangan Beku Sejahtera', '031-778899');

INSERT INTO products (product_name, price, stock, category_id, supplier_id) VALUES
('Nugget Ayam 500g', 35000, 40, 1, 1),
('Sosis Ayam 1kg', 42000, 30, 1, 1),
('Kaki Naga 500g', 28000, 25, 1, 2),
('Daging Sapi Slice 500g', 75000, 20, 2, 2),
('Bakso Sapi 50pcs', 38000, 35, 2, 2),
('Udang Kupas 250g', 45000, 18, 2, 3),
('Ikan Fillet Dori 1kg', 52000, 22, 2, 3),
('Kentang Goreng 1kg', 30000, 50, 3, 1),
('Dimsum Ayam 20pcs', 33000, 28, 3, 3),
('Roti Maryam 5pcs', 25000, 45, 3, 1);
