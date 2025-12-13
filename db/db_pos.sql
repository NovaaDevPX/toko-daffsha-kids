-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 10 Des 2025 pada 16.55
-- Versi server: 10.4.17-MariaDB
-- Versi PHP: 8.0.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_pos`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `price` int(11) NOT NULL,
  `stock` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `is_deleted` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `stock`, `image`, `is_deleted`, `created_at`, `updated_at`) VALUES
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

-- --------------------------------------------------------

--
-- Struktur dari tabel `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  `payment` int(11) NOT NULL,
  `change_money` int(11) NOT NULL,
  `method` enum('cash','qris','transfer') DEFAULT 'cash',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `total`, `payment`, `change_money`, `method`, `created_at`) VALUES
(1, 1, 25000, 50000, 25000, 'cash', '2025-12-10 19:17:48'),
(2, 1, 35000, 250000, 215000, 'cash', '2025-12-10 22:46:18');

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaction_items`
--

CREATE TABLE `transaction_items` (
  `id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `subtotal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `transaction_items`
--

INSERT INTO `transaction_items` (`id`, `transaction_id`, `product_id`, `qty`, `price`, `subtotal`) VALUES
(1, 1, 1, 5, 5000, 25000),
(2, 2, 3, 5, 7000, 35000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','kasir') DEFAULT 'kasir',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `users`
--

-- sandi admin: admin123 
-- sandi kasir: kasir123

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'Admin Toko', 'admin@toko.com', '0192023a7bbd73250516f069df18b500', 'admin', '2025-12-10 19:17:15'),
(2, 'Kasir Satu', 'kasir1@toko.com', 'de28f8f7998f23ab4194b51a6029416f', 'kasir', '2025-12-10 19:17:15'),
(3, 'Kasir Dua', 'kasir2@toko.com', 'de28f8f7998f23ab4194b51a6029416f', 'kasir', '2025-12-10 19:17:15');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `transaction_items`
--
ALTER TABLE `transaction_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaction_id` (`transaction_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `transaction_items`
--
ALTER TABLE `transaction_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `transaction_items`
--
ALTER TABLE `transaction_items`
  ADD CONSTRAINT `transaction_items_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`),
  ADD CONSTRAINT `transaction_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

  ALTER TABLE transactions 
ADD COLUMN status ENUM('completed','cancelled') DEFAULT 'completed';

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
