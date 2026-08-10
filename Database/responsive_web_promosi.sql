-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 10 Agu 2026 pada 06.48
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `responsive_web_promosi`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `pesan` text NOT NULL,
  `pengirim` enum('user','supplier') DEFAULT 'user',
  `waktu` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `messages`
--

INSERT INTO `messages` (`id`, `user_id`, `product_id`, `pesan`, `pengirim`, `waktu`) VALUES
(1, 1, 5, 'halo ingin nanya tentang produk ini', 'user', '2026-07-23 07:25:09'),
(2, 1, 4, 'halo kak. mau pesan 100pcs', 'user', '2026-07-23 12:24:00'),
(3, 1, 2, 'permisi saya ingin bertanya', 'user', '2026-07-23 13:35:46');

-- --------------------------------------------------------

--
-- Struktur dari tabel `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `tanggal_order` datetime NOT NULL,
  `total_harga` int(11) NOT NULL,
  `alamat_pengiriman` text NOT NULL,
  `jenis_pengiriman` varchar(100) NOT NULL,
  `status` enum('menunggu_pembayaran','diproses','dikirim','selesai') DEFAULT 'menunggu_pembayaran'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `tanggal_order`, `total_harga`, `alamat_pengiriman`, `jenis_pengiriman`, `status`) VALUES
(1, 1, '2026-07-21 22:45:02', 45000000, 'jl. mawar 1 blok c', '', 'selesai'),
(2, 1, '2026-07-21 23:00:42', 15600000, 'jl. pedagang timur nomor 3 B', 'Ekspedisi Kargo Darat (Indah/JNE Trucking)', 'selesai'),
(3, 1, '2026-07-23 00:41:29', 20700000, 'jl melati no 4', 'Kurir Internal / Armada Toko', 'diproses'),
(4, 1, '2026-07-31 18:38:03', 15600000, '.....', 'Ekspedisi Kargo Darat (Indah/JNE Trucking)', 'diproses');

-- --------------------------------------------------------

--
-- Struktur dari tabel `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `kuantitas` int(11) NOT NULL,
  `harga_satuan` int(11) NOT NULL,
  `subtotal` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `kuantitas`, `harga_satuan`, `subtotal`) VALUES
(1, 1, 1, 3000, 15000, 45000000),
(2, 2, 4, 1000, 15600, 15600000),
(3, 3, 1, 1150, 18000, 20700000),
(4, 4, 4, 1000, 15600, 15600000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `price_locks`
--

CREATE TABLE `price_locks` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `kuantitas` int(11) NOT NULL,
  `harga_pengajuan` decimal(10,2) NOT NULL,
  `status` enum('pending','approved','rejected','selesai') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `price_locks`
--

INSERT INTO `price_locks` (`id`, `user_id`, `product_id`, `kuantitas`, `harga_pengajuan`, `status`, `created_at`) VALUES
(1, 1, 2, 1208, 30000.00, 'rejected', '2026-07-21 08:45:16'),
(2, 1, 1, 3000, 15000.00, 'approved', '2026-07-21 15:15:55'),
(3, 1, 4, 1000, 15600.00, 'selesai', '2026-07-21 15:58:22'),
(4, 2, 1, 1200, 15000.00, 'rejected', '2026-07-21 16:06:07'),
(5, 1, 1, 1150, 18000.00, 'selesai', '2026-07-22 17:39:57');

-- --------------------------------------------------------

--
-- Struktur dari tabel `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `nama_produk` varchar(150) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `harga_normal` decimal(10,2) NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `stok` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `products`
--

INSERT INTO `products` (`id`, `nama_produk`, `deskripsi`, `harga_normal`, `gambar`, `stok`, `created_at`) VALUES
(1, 'Tumbler Stainless 500ml', 'Tumbler premium bisa custom logo perusahaan. Tahan panas dan dingin hingga 8 jam.', 15000.00, 'tumbler.jpg', 1000, '2026-07-21 04:28:33'),
(2, 'Buku Agenda Custom A5', 'Agenda eksklusif dengan sampul kulit sintetis. Cocok untuk seminar dan rapat.', 30000.00, 'agenda.jpg', 500, '2026-07-21 04:28:33'),
(3, 'Pulpen Besi Eksklusif', 'Pulpen metal elegan dengan grafir laser logo perusahaan Anda.', 8500.00, 'pulpen.jpg', 2000, '2026-07-21 04:28:33'),
(4, 'Tas Seminar Totebag', 'Tas kanvas tebal ramah lingkungan dengan sablon custom 2 warna.', 12000.00, 'totebag.jpg', 1500, '2026-07-21 04:28:33'),
(5, 'Gelas Mug Keramik', 'Gelas mug keramik berbahan akrilik. \r\nmodelnya yang fancy\r\nkinclong', 30000.00, 'Mug_Keramik.png', 10000, '2026-07-22 18:31:27');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama_perusahaan` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `alamat` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `nama_perusahaan`, `email`, `password`, `role`, `alamat`, `created_at`) VALUES
(1, 'Ray', 'Raymond123@gmail.com', '123456', 'user', NULL, '2026-07-21 09:35:38'),
(2, 'Admin TokoPromosi', 'adminASP@gmail.com', 'admin000', 'admin', NULL, '2026-07-21 10:20:49');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `price_locks`
--
ALTER TABLE `price_locks`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT untuk tabel `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `price_locks`
--
ALTER TABLE `price_locks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
