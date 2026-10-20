-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 29, 2026 at 02:29 PM
-- Server version: 8.0.36
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kasir`
--

-- --------------------------------------------------------

--
-- Table structure for table `produk`
--

CREATE TABLE `produk` (
  `id` int NOT NULL,
  `kode` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '',
  `harga` decimal(12,2) NOT NULL DEFAULT '0.00',
  `stok` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `produk`
--

INSERT INTO `produk` (`id`, `kode`, `nama`, `kategori`, `harga`, `stok`, `created_at`, `updated_at`) VALUES
(1, 'PRD001', 'Indomie Goreng', 'Makanan', '3500.00', 84, '2026-05-26 13:48:45', '2026-05-27 10:16:55'),
(2, 'PRD002', 'teh boom', 'Minuman saset', '4000000.00', 51, '2026-05-26 13:48:45', '2026-05-27 10:52:20'),
(3, 'PRD003', 'Roti Tawar', 'Makanan', '12000.00', 8, '2026-05-26 13:48:45', '2026-05-27 10:52:20'),
(4, 'PRD004', 'Kopi Sachet', 'Minuman', '2500.00', 120, '2026-05-26 13:48:45', '2026-05-26 13:48:45'),
(5, 'PRD005', 'Sabun Mandi', 'Perawatan', '8000.00', 25, '2026-05-26 13:48:45', '2026-05-27 10:51:35'),
(7, 'PRD007', 'Beras 5kg', 'Sembako', '65000.00', 18, '2026-05-26 13:48:45', '2026-05-26 13:48:45'),
(8, 'PRD008', 'Minyak Goreng 1L', 'Sembako', '18000.00', 23, '2026-05-26 13:48:45', '2026-05-27 10:52:20'),
(9, 'PRD009', 'Gula Pasir 1kg', 'Sembako', '13000.00', 21, '2026-05-26 13:48:45', '2026-05-27 10:16:55'),
(10, 'PRD010', 'Rinso Sachet', 'Perawatan', '2000.00', 143, '2026-05-26 13:48:45', '2026-05-27 10:52:20'),
(11, 'jndfnjs', 'kjcsdn', 'makanan', '12000.00', 12, '2026-05-27 10:17:58', '2026-05-27 10:17:58');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id` int NOT NULL,
  `no_invoice` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int NOT NULL,
  `total` decimal(12,2) NOT NULL DEFAULT '0.00',
  `bayar` decimal(12,2) NOT NULL DEFAULT '0.00',
  `kembalian` decimal(12,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id`, `no_invoice`, `user_id`, `total`, `bayar`, `kembalian`, `created_at`) VALUES
(1, 'INV-20260520-001', 1, '39500.00', '40000.00', '500.00', '2026-05-20 01:30:00'),
(2, 'INV-20260520-002', 1, '109000.00', '110000.00', '1000.00', '2026-05-20 03:15:00'),
(3, 'INV-20260520-003', 1, '29500.00', '30000.00', '500.00', '2026-05-20 06:00:00'),
(4, 'INV-20260520-004', 1, '50000.00', '50000.00', '0.00', '2026-05-20 08:30:00'),
(5, 'INV-20260521-001', 1, '114000.00', '120000.00', '6000.00', '2026-05-21 02:00:00'),
(6, 'INV-20260521-002', 1, '41500.00', '50000.00', '8500.00', '2026-05-21 04:30:00'),
(7, 'INV-20260521-003', 1, '25000.00', '25000.00', '0.00', '2026-05-21 07:00:00'),
(8, 'INV-20260522-001', 1, '179000.00', '200000.00', '21000.00', '2026-05-22 01:00:00'),
(9, 'INV-20260522-002', 1, '41000.00', '50000.00', '9000.00', '2026-05-22 03:30:00'),
(10, 'INV-20260522-003', 1, '58000.00', '60000.00', '2000.00', '2026-05-22 06:00:00'),
(11, 'INV-20260522-004', 1, '33500.00', '35000.00', '1500.00', '2026-05-22 09:00:00'),
(12, 'INV-20260523-001', 1, '109000.00', '110000.00', '1000.00', '2026-05-23 02:30:00'),
(13, 'INV-20260523-002', 1, '41000.00', '50000.00', '9000.00', '2026-05-23 04:00:00'),
(14, 'INV-20260523-003', 1, '48000.00', '50000.00', '2000.00', '2026-05-23 07:30:00'),
(15, 'INV-20260524-001', 1, '114000.00', '120000.00', '6000.00', '2026-05-24 01:30:00'),
(16, 'INV-20260524-002', 1, '57000.00', '60000.00', '3000.00', '2026-05-24 03:00:00'),
(17, 'INV-20260524-003', 1, '37500.00', '40000.00', '2500.00', '2026-05-24 06:30:00'),
(18, 'INV-20260524-004', 1, '35000.00', '35000.00', '0.00', '2026-05-24 09:00:00'),
(19, 'INV-20260525-001', 1, '174000.00', '200000.00', '26000.00', '2026-05-25 01:00:00'),
(20, 'INV-20260525-002', 1, '43500.00', '50000.00', '6500.00', '2026-05-25 03:30:00'),
(21, 'INV-20260525-003', 1, '59500.00', '60000.00', '500.00', '2026-05-25 06:00:00'),
(22, 'INV-20260525-004', 1, '39000.00', '40000.00', '1000.00', '2026-05-25 08:30:00'),
(23, 'INV-20260525-005', 1, '42500.00', '50000.00', '7500.00', '2026-05-25 10:00:00'),
(24, 'INV-20260526-001', 1, '114000.00', '120000.00', '6000.00', '2026-05-26 02:00:00'),
(25, 'INV-20260526-002', 1, '49500.00', '50000.00', '500.00', '2026-05-26 04:00:00'),
(26, 'INV-20260526-003', 1, '54000.00', '60000.00', '6000.00', '2026-05-26 06:30:00'),
(27, 'INV-20260526-004', 1, '46500.00', '50000.00', '3500.00', '2026-05-26 08:00:00'),
(28, 'INV-20260526-005', 1, '91000.00', '100000.00', '9000.00', '2026-05-26 09:30:00'),
(100, 'INV-20260526-B997C', 1, '46000.00', '200000.00', '154000.00', '2026-05-26 13:49:09'),
(101, 'INV-20260527-0CBA6', 1, '40000.00', '200000.00', '160000.00', '2026-05-27 10:16:55'),
(102, 'INV-20260527-F1190', 5, '4088000.00', '5000000.00', '912000.00', '2026-05-27 10:38:22'),
(103, 'INV-20260527-68DE6', 5, '4000000.00', '4000001.00', '1.00', '2026-05-27 10:45:26'),
(104, 'INV-20260527-D58AE', 5, '4044000.00', '40440111.00', '36396111.00', '2026-05-27 10:51:35'),
(105, 'INV-20260527-CB1BF', 1, '4032000.00', '403200111.00', '399168111.00', '2026-05-27 10:52:20');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi_brilink`
--

CREATE TABLE `transaksi_brilink` (
  `id` int NOT NULL,
  `no_invoice` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int NOT NULL,
  `jenis` enum('stor_tunai','tarik_tunai','pembayaran','transfer','dompet_digital') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sub_jenis` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nominal` decimal(12,2) NOT NULL DEFAULT '0.00',
  `biaya_admin` decimal(12,2) NOT NULL DEFAULT '0.00',
  `keterangan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transaksi_brilink`
--

INSERT INTO `transaksi_brilink` (`id`, `no_invoice`, `user_id`, `jenis`, `sub_jenis`, `nominal`, `biaya_admin`, `keterangan`, `created_at`) VALUES
(1, 'BRI-20260529-001', 1, 'pembayaran', 'briva', '0.00', '5000.00', '', '2026-05-29 13:12:48'),
(2, 'BRI-20260529-002', 1, 'tarik_tunai', '', '100000.00', '2000.00', '', '2026-05-29 13:13:00'),
(3, 'BRI-20260529-003', 1, 'pembayaran', 'briva', '0.00', '5000.00', '', '2026-05-29 13:13:10'),
(4, 'BRI-20260529-004', 1, 'pembayaran', 'bpjs', '0.00', '2500.00', '', '2026-05-29 13:13:29');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi_item`
--

CREATE TABLE `transaksi_item` (
  `id` int NOT NULL,
  `transaksi_id` int NOT NULL,
  `produk_id` int NOT NULL,
  `qty` int NOT NULL DEFAULT '1',
  `harga` decimal(12,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transaksi_item`
--

INSERT INTO `transaksi_item` (`id`, `transaksi_id`, `produk_id`, `qty`, `harga`, `subtotal`) VALUES
(1, 1, 1, 5, '3500.00', '17500.00'),
(2, 1, 2, 3, '4000.00', '12000.00'),
(3, 1, 4, 4, '2500.00', '10000.00'),
(4, 2, 7, 1, '65000.00', '65000.00'),
(5, 2, 8, 1, '18000.00', '18000.00'),
(6, 2, 9, 1, '13000.00', '26000.00'),
(7, 3, 5, 1, '8000.00', '16000.00'),
(9, 3, 10, 3, '2000.00', '6000.00'),
(10, 4, 3, 1, '12000.00', '24000.00'),
(11, 4, 2, 3, '4000.00', '12000.00'),
(12, 4, 1, 4, '3500.00', '14000.00'),
(13, 5, 8, 1, '18000.00', '36000.00'),
(14, 5, 9, 1, '13000.00', '13000.00'),
(15, 5, 7, 1, '65000.00', '65000.00'),
(16, 6, 1, 6, '3500.00', '21000.00'),
(17, 6, 4, 5, '2500.00', '12500.00'),
(18, 6, 2, 1, '4000.00', '8000.00'),
(19, 7, 5, 1, '8000.00', '8000.00'),
(21, 7, 10, 4, '2000.00', '8000.00'),
(22, 8, 7, 1, '65000.00', '130000.00'),
(23, 8, 8, 1, '18000.00', '36000.00'),
(24, 8, 9, 1, '13000.00', '13000.00'),
(25, 9, 1, 4, '3500.00', '14000.00'),
(26, 9, 2, 3, '4000.00', '12000.00'),
(27, 9, 4, 6, '2500.00', '15000.00'),
(28, 10, 3, 3, '12000.00', '36000.00'),
(29, 10, 5, 1, '8000.00', '16000.00'),
(31, 11, 10, 5, '2000.00', '10000.00'),
(32, 11, 1, 3, '3500.00', '10500.00'),
(33, 11, 9, 1, '13000.00', '13000.00'),
(34, 12, 8, 1, '18000.00', '18000.00'),
(35, 12, 9, 1, '13000.00', '26000.00'),
(36, 12, 7, 1, '65000.00', '65000.00'),
(37, 13, 1, 5, '3500.00', '17500.00'),
(38, 13, 1, 4, '4000.00', '16000.00'),
(39, 13, 4, 3, '2500.00', '7500.00'),
(40, 14, 5, 3, '8000.00', '24000.00'),
(42, 14, 10, 6, '2000.00', '12000.00'),
(43, 15, 7, 1, '65000.00', '65000.00'),
(44, 15, 8, 1, '18000.00', '36000.00'),
(45, 15, 9, 1, '13000.00', '13000.00'),
(46, 16, 1, 6, '3500.00', '21000.00'),
(47, 16, 1, 3, '4000.00', '12000.00'),
(48, 16, 3, 1, '12000.00', '24000.00'),
(49, 17, 4, 5, '2500.00', '12500.00'),
(50, 17, 5, 1, '8000.00', '16000.00'),
(52, 18, 10, 4, '2000.00', '8000.00'),
(53, 18, 9, 1, '13000.00', '13000.00'),
(54, 18, 1, 4, '3500.00', '14000.00'),
(55, 19, 7, 1, '65000.00', '130000.00'),
(56, 19, 8, 1, '18000.00', '18000.00'),
(57, 19, 9, 1, '13000.00', '26000.00'),
(58, 20, 1, 5, '3500.00', '17500.00'),
(59, 20, 1, 4, '4000.00', '16000.00'),
(60, 20, 4, 4, '2500.00', '10000.00'),
(61, 21, 3, 3, '12000.00', '36000.00'),
(62, 21, 5, 1, '8000.00', '16000.00'),
(64, 22, 10, 6, '2000.00', '12000.00'),
(65, 22, 1, 4, '3500.00', '14000.00'),
(66, 22, 9, 1, '13000.00', '13000.00'),
(67, 23, 8, 1, '18000.00', '18000.00'),
(68, 23, 1, 3, '4000.00', '12000.00'),
(69, 23, 4, 5, '2500.00', '12500.00'),
(70, 24, 7, 1, '65000.00', '65000.00'),
(71, 24, 8, 1, '18000.00', '36000.00'),
(72, 24, 9, 1, '13000.00', '13000.00'),
(73, 25, 1, 6, '3500.00', '21000.00'),
(74, 25, 1, 4, '4000.00', '16000.00'),
(75, 25, 4, 5, '2500.00', '12500.00'),
(76, 26, 3, 1, '12000.00', '24000.00'),
(77, 26, 5, 3, '8000.00', '24000.00'),
(79, 27, 10, 5, '2000.00', '10000.00'),
(80, 27, 9, 1, '13000.00', '26000.00'),
(81, 27, 1, 3, '3500.00', '10500.00'),
(82, 28, 8, 1, '18000.00', '18000.00'),
(83, 28, 7, 1, '65000.00', '65000.00'),
(84, 28, 1, 2, '4000.00', '8000.00'),
(200, 100, 10, 1, '2000.00', '2000.00'),
(201, 100, 3, 3, '12000.00', '36000.00'),
(202, 100, 5, 1, '8000.00', '8000.00'),
(203, 101, 8, 1, '18000.00', '18000.00'),
(204, 101, 2, 1, '4000.00', '4000.00'),
(206, 101, 1, 1, '3500.00', '3500.00'),
(207, 101, 9, 1, '13000.00', '13000.00'),
(208, 102, 2, 1, '4000000.00', '4000000.00'),
(209, 102, 5, 11, '8000.00', '88000.00'),
(210, 103, 2, 1, '4000000.00', '4000000.00'),
(211, 104, 2, 1, '4000000.00', '4000000.00'),
(212, 104, 5, 1, '8000.00', '8000.00'),
(213, 104, 3, 3, '12000.00', '36000.00'),
(214, 105, 8, 1, '18000.00', '18000.00'),
(215, 105, 10, 1, '2000.00', '2000.00'),
(216, 105, 3, 1, '12000.00', '12000.00'),
(217, 105, 2, 1, '4000000.00', '4000000.00');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi_print`
--

CREATE TABLE `transaksi_print` (
  `id` int NOT NULL,
  `no_invoice` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int NOT NULL,
  `jenis` enum('print','fotokopi') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah_lembar` int NOT NULL DEFAULT '1',
  `harga_per_lembar` decimal(10,2) NOT NULL,
  `total` decimal(12,2) NOT NULL,
  `keterangan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transaksi_print`
--

INSERT INTO `transaksi_print` (`id`, `no_invoice`, `user_id`, `jenis`, `jumlah_lembar`, `harga_per_lembar`, `total`, `keterangan`, `created_at`) VALUES
(1, 'PRT-20260529-001', 1, 'fotokopi', 100000, '400.00', '40000000.00', '', '2026-05-29 13:11:58');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','kasir') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'kasir',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `nama`, `role`, `created_at`) VALUES
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin', '2026-05-04 06:37:03'),
(5, 'kasir', '$2y$10$59ibHDsAUNIBRdvSTyx2pup9CmMhLmLr2Tyx9nz7mIREW30u9S7rC', 'kasir asep', 'kasir', '2026-05-27 10:19:24');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode` (`kode`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `no_invoice` (`no_invoice`),
  ADD KEY `transaksi_ibfk_1` (`user_id`);

--
-- Indexes for table `transaksi_brilink`
--
ALTER TABLE `transaksi_brilink`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `no_invoice` (`no_invoice`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `transaksi_item`
--
ALTER TABLE `transaksi_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaksi_id` (`transaksi_id`),
  ADD KEY `transaksi_item_ibfk_2` (`produk_id`);

--
-- Indexes for table `transaksi_print`
--
ALTER TABLE `transaksi_print`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `no_invoice` (`no_invoice`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `produk`
--
ALTER TABLE `produk`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

--
-- AUTO_INCREMENT for table `transaksi_brilink`
--
ALTER TABLE `transaksi_brilink`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `transaksi_item`
--
ALTER TABLE `transaksi_item`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=218;

--
-- AUTO_INCREMENT for table `transaksi_print`
--
ALTER TABLE `transaksi_print`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `transaksi_brilink`
--
ALTER TABLE `transaksi_brilink`
  ADD CONSTRAINT `brilink_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `transaksi_item`
--
ALTER TABLE `transaksi_item`
  ADD CONSTRAINT `transaksi_item_ibfk_1` FOREIGN KEY (`transaksi_id`) REFERENCES `transaksi` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transaksi_item_ibfk_2` FOREIGN KEY (`produk_id`) REFERENCES `produk` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `transaksi_print`
--
ALTER TABLE `transaksi_print`
  ADD CONSTRAINT `print_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
