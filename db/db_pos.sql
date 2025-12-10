DROP DATABASE IF EXISTS db_pos;
CREATE DATABASE db_pos;
USE db_pos;

-- ================================================
-- TABLE: users
-- ================================================
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin', 'kasir') DEFAULT 'kasir',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- ================================================
-- TABLE: products
-- ================================================
CREATE TABLE products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  price INT NOT NULL,
  stock INT NOT NULL,
  image VARCHAR(255) DEFAULT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ================================================
-- TABLE: transactions
-- ================================================
CREATE TABLE transactions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  total INT NOT NULL,
  payment INT NOT NULL,
  change_money INT NOT NULL,
  method ENUM('cash', 'qris', 'transfer') DEFAULT 'cash',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id)
);

-- ================================================
-- TABLE: transaction_items
-- ================================================
CREATE TABLE transaction_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  transaction_id INT NOT NULL,
  product_id INT NOT NULL,
  qty INT NOT NULL,
  price INT NOT NULL,
  subtotal INT NOT NULL,
  FOREIGN KEY (transaction_id) REFERENCES transactions(id),
  FOREIGN KEY (product_id) REFERENCES products(id)
);


-- ================================================
-- OPTIONAL: Dummy Data (boleh hapus kalau tidak perlu)
-- ================================================

INSERT INTO users (name, email, password, role)
VALUES
INSERT INTO users (name, email, password, role) VALUES
('Admin Toko', 'admin@toko.com', MD5('admin123'), 'admin'),
('Kasir Satu', 'kasir1@toko.com', MD5('kasir123'), 'kasir'),
('Kasir Dua', 'kasir2@toko.com', MD5('kasir123'), 'kasir');


INSERT INTO products (name, price, stock)
VALUES
('Air Mineral', 5000, 50),
('Kopi Hitam', 8000, 30),
('Roti Coklat', 7000, 40),
('Mie Instan', 3500, 100);
