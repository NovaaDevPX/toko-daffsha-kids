-- phpMyAdmin SQL Dump (Clean Version)
-- Database: db_pos

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
SET NAMES utf8mb4;

-- ============================
-- TABLE: users
-- ============================
CREATE TABLE `users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('admin','kasir') DEFAULT 'kasir',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'Admin Toko', 'admin@toko.com', '0192023a7bbd73250516f069df18b500', 'admin', '2025-12-10 19:17:15'),
(2, 'Kasir Satu', 'kasir1@toko.com', 'de28f8f7998f23ab4194b51a6029416f', 'kasir', '2025-12-10 19:17:15'),
(3, 'Kasir Dua', 'kasir2@toko.com', 'de28f8f7998f23ab4194b51a6029416f', 'kasir', '2025-12-10 19:17:15');

-- ============================
-- TABLE: products
-- ============================
CREATE TABLE `products` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(150) NOT NULL,
  `price` INT(11) NOT NULL,
  `stock` INT(11) NOT NULL,
  `image` VARCHAR(255) DEFAULT NULL,
  `is_deleted` TINYINT(1) DEFAULT 0,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `products` VALUES
(1, 'Celana Anak 1', 5000, 45, 'uploads/products/69397846a06f4_celana_anak_1.jpg', 0, '2025-12-10 19:17:15', '2025-12-10 20:40:22'),
(2, 'Celana Anak 4', 8000, 30, 'uploads/products/693978adb6faf_celana_anak_4.jpg', 0, '2025-12-10 19:17:15', '2025-12-10 20:42:05'),
(3, 'Jas Dewasa', 7000, 35, 'uploads/products/693978ff32836_wmremove-transformed.jpeg', 0, '2025-12-10 19:17:15', '2025-12-10 22:46:18'),
(4, 'Jas Dewasa 2', 3500, 100, 'uploads/products/69397937248f4_jas_dewasa.jpg', 0, '2025-12-10 19:17:15', '2025-12-10 20:44:23'),
(5, 'Baju Anak 1', 15000, 3, 'uploads/products/6939779cc4aa4_baju_anak_1.jpg', 1, '2025-12-10 20:04:34', '2025-12-10 22:48:47'),
(6, 'Baju Anak 2', 25000, 10, 'uploads/products/693977b49000d_baju_anak_2.jpg', 0, '2025-12-10 20:04:54', '2025-12-10 20:37:56'),
(7, 'Baju Anak 3', 50000, 30, 'uploads/products/693977d6e561f_baju_anak_13jpg.jpg', 0, '2025-12-10 20:05:34', '2025-12-10 20:38:30'),
(8, 'Baju Anak 4', 250000, 1, 'uploads/products/693977ecb7220_baju_anak_4.jpg', 0, '2025-12-10 20:05:46', '2025-12-10 20:38:52'),
(9, 'Baju Anak 5', 25000, 5, 'uploads/products/69397804e2358_baju_anak_5.jpg', 0, '2025-12-10 20:06:04', '2025-12-10 20:39:16'),
(10, 'Celana Anak 3', 50000, 5, 'uploads/products/6939788b29c56_celana_anak_3.jpg', 0, '2025-12-10 20:06:24', '2025-12-10 20:41:31'),
(11, 'Baju Anak 6', 20000, 20, 'uploads/products/6939782ab3ef2_baju_anak_6.jpg', 0, '2025-12-10 20:06:42', '2025-12-10 20:39:54'),
(12, 'Celana Anak 2', 25000, 10, 'uploads/products/6939786b3fdf5.jpg', 0, '2025-12-10 20:40:59', '2025-12-10 20:40:59');

-- ============================
-- TABLE: transactions
-- ============================
CREATE TABLE `transactions` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `total` INT(11) NOT NULL,
  `payment` INT(11) NOT NULL,
  `change_money` INT(11) NOT NULL,
  `method` ENUM('cash','qris','transfer') DEFAULT 'cash',
  `status` ENUM('completed','cancelled') DEFAULT 'completed',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `fk_transactions_user`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `transactions` VALUES
(1, 1, 25000, 50000, 25000, 'cash', 'completed', '2025-12-10 19:17:48'),
(2, 1, 35000, 250000, 215000, 'cash', 'completed', '2025-12-10 22:46:18');

-- ============================
-- TABLE: transaction_items
-- ============================
CREATE TABLE `transaction_items` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `transaction_id` INT(11) NOT NULL,
  `product_id` INT(11) NOT NULL,
  `qty` INT(11) NOT NULL,
  `price` INT(11) NOT NULL,
  `subtotal` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `transaction_id` (`transaction_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `fk_items_transaction`
    FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`),
  CONSTRAINT `fk_items_product`
    FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `transaction_items` VALUES
(1, 1, 1, 5, 5000, 25000),
(2, 2, 3, 5, 7000, 35000);

COMMIT;
