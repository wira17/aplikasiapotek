-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Sep 10, 2025 at 03:04 PM
-- Server version: 10.4.20-MariaDB
-- PHP Version: 8.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `apotek`
--

-- --------------------------------------------------------

--
-- Table structure for table `biaya_operasional`
--

CREATE TABLE `biaya_operasional` (
  `id` int(11) NOT NULL,
  `kode_operasional` varchar(20) NOT NULL,
  `tanggal` date NOT NULL,
  `keterangan` varchar(255) NOT NULL,
  `jumlah` decimal(15,2) NOT NULL,
  `nota` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `biaya_operasional`
--

INSERT INTO `biaya_operasional` (`id`, `kode_operasional`, `tanggal`, `keterangan`, `jumlah`, `nota`, `created_at`) VALUES
(1, 'BO-20250909-001', '2025-09-09', 'Beli token listrik', '200000.00', '-', '2025-09-09 14:21:06'),
(2, 'BO-20250909-000', '2025-09-09', 'Makan karyawan 3 orang', '50000.00', '-', '2025-09-09 14:21:34'),
(7, 'BO-20250910-001', '2025-09-10', 'Beli plastik obat', '111.00', '-', '2025-09-10 01:56:25'),
(8, 'BO-20250910-000', '2025-09-10', 'Makan karyawan 3 orang', '50000.00', '-', '2025-09-10 01:56:50');

-- --------------------------------------------------------

--
-- Table structure for table `master_kategori`
--

CREATE TABLE `master_kategori` (
  `id` int(11) NOT NULL,
  `nama_kategori` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `master_kategori`
--

INSERT INTO `master_kategori` (`id`, `nama_kategori`) VALUES
(1, 'Analgesik'),
(2, 'Antibiotik'),
(4, 'Antihistamin'),
(5, 'Antiinflamasi'),
(3, 'Vitamin');

-- --------------------------------------------------------

--
-- Table structure for table `master_satuan`
--

CREATE TABLE `master_satuan` (
  `id` int(11) NOT NULL,
  `nama_satuan` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `master_satuan`
--

INSERT INTO `master_satuan` (`id`, `nama_satuan`) VALUES
(4, 'Ampul'),
(3, 'Botol'),
(2, 'Kapsul'),
(5, 'Sirup'),
(1, 'Tablet');

-- --------------------------------------------------------

--
-- Table structure for table `obat`
--

CREATE TABLE `obat` (
  `id` int(11) NOT NULL,
  `kode_obat` varchar(50) NOT NULL,
  `nama_obat` varchar(100) NOT NULL,
  `kategori` varchar(50) DEFAULT NULL,
  `satuan` varchar(30) DEFAULT NULL,
  `harga_beli` int(11) NOT NULL,
  `harga_jual` int(11) NOT NULL,
  `stok` int(11) NOT NULL DEFAULT 0,
  `tanggal_kadaluwarsa` date DEFAULT NULL,
  `jatuh_tempo` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `obat`
--

INSERT INTO `obat` (`id`, `kode_obat`, `nama_obat`, `kategori`, `satuan`, `harga_beli`, `harga_jual`, `stok`, `tanggal_kadaluwarsa`, `jatuh_tempo`, `created_at`) VALUES
(5, 'OBT0001', 'Paracetamol', 'Analgesik', 'Ampul', 10000, 12000, 0, '2025-09-09', '2025-09-12', '2025-09-06 03:04:07'),
(6, 'OBT0002', 'Ampicilin', 'Antibiotik', 'Ampul', 12000, 2300, 89, '2025-09-07', '2025-09-08', '2025-09-08 12:06:51'),
(7, 'OBT0003', 'Alprazolam', 'Antibiotik', 'Ampul', 14154, 120000, 48, '2025-09-23', '2025-10-11', '2025-09-08 12:19:23'),
(8, 'OBT0004', 'Ambroxol sirup 30 mg', 'Antibiotik', 'Ampul', 1000, 28000, 32, NULL, NULL, '2025-09-08 12:19:54'),
(9, 'OBT0005', 'Amilorida tablet 5 mg', 'Antibiotik', 'Ampul', 25000, 21000, 4, NULL, NULL, '2025-09-08 12:20:33'),
(10, 'OBT0006', 'Aminofilin injeksi 24 mg/ml', 'Antibiotik', 'Ampul', 118000, 125000, 0, '2025-09-26', '2025-10-09', '2025-09-08 12:21:09'),
(11, 'OBT0007', 'Amlodipin tablet 5 mg', 'Antibiotik', 'Ampul', 9000, 12000, 0, '2025-09-12', '2025-09-05', '2025-09-08 12:21:35'),
(12, 'OBT0008', 'tes', 'Analgesik', 'Ampul', 20000, 1200, 10, '2025-09-23', '2025-10-11', '2025-09-09 12:42:10');

-- --------------------------------------------------------

--
-- Table structure for table `penjualan`
--

CREATE TABLE `penjualan` (
  `id` int(11) NOT NULL,
  `tanggal` datetime NOT NULL,
  `kode_obat` varchar(50) NOT NULL,
  `nama_obat` varchar(100) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `harga_satuan` decimal(15,2) NOT NULL,
  `total` decimal(15,2) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `penjualan`
--

INSERT INTO `penjualan` (`id`, `tanggal`, `kode_obat`, `nama_obat`, `jumlah`, `harga_satuan`, `total`, `user_id`) VALUES
(1, '2025-09-01 22:20:21', 'OBT0004', 'Ampicilin', 8, '1200.00', '9600.00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `penjualan_detail`
--

CREATE TABLE `penjualan_detail` (
  `id` int(11) NOT NULL,
  `penjualan_id` int(11) NOT NULL,
  `kode_obat` varchar(50) NOT NULL,
  `nama_obat` varchar(100) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `harga_satuan` decimal(15,2) NOT NULL,
  `total` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `penjualan_detail`
--

INSERT INTO `penjualan_detail` (`id`, `penjualan_id`, `kode_obat`, `nama_obat`, `jumlah`, `harga_satuan`, `total`) VALUES
(36, 18, 'OBT0003', 'Alprazolam', 1, '120000.00', '120000.00'),
(37, 18, 'OBT0002', 'Ampicilin', 3, '2300.00', '6900.00'),
(38, 18, 'OBT0001', 'Paracetamol', 5, '12000.00', '60000.00'),
(39, 19, 'OBT0003', 'Alprazolam', 1, '120000.00', '120000.00'),
(40, 20, 'OBT0003', 'Alprazolam', 1, '120000.00', '120000.00'),
(41, 21, 'OBT0003', 'Alprazolam', 1, '120000.00', '120000.00'),
(42, 21, 'OBT0001', 'Paracetamol', 15, '12000.00', '180000.00'),
(43, 21, 'OBT0002', 'Ampicilin', 1, '2300.00', '2300.00'),
(44, 22, 'OBT0003', 'Alprazolam', 1, '120000.00', '120000.00'),
(45, 23, 'OBT0004', 'Ambroxol sirup 30 mg', 50, '28000.00', '1400000.00'),
(46, 23, 'OBT0001', 'Paracetamol', 30, '12000.00', '360000.00'),
(47, 24, 'OBT0004', 'Ambroxol sirup 30 mg', 1, '28000.00', '28000.00'),
(48, 24, 'OBT0003', 'Alprazolam', 3, '120000.00', '360000.00'),
(49, 24, 'OBT0001', 'Paracetamol', 1, '12000.00', '12000.00'),
(50, 25, 'OBT0003', 'Alprazolam', 1, '120000.00', '120000.00'),
(51, 25, 'OBT0004', 'Ambroxol sirup 30 mg', 5, '28000.00', '140000.00'),
(52, 25, 'OBT0001', 'Paracetamol', 10, '12000.00', '120000.00'),
(53, 26, 'OBT0002', 'Ampicilin', 1, '2300.00', '2300.00'),
(54, 26, 'OBT0004', 'Ambroxol sirup 30 mg', 5, '28000.00', '140000.00'),
(55, 26, 'OBT0001', 'Paracetamol', 5, '12000.00', '60000.00'),
(56, 26, 'OBT0005', 'Amilorida tablet 5 mg', 3, '21000.00', '63000.00'),
(57, 27, 'OBT0004', 'Ambroxol sirup 30 mg', 2, '28000.00', '56000.00'),
(58, 27, 'OBT0005', 'Amilorida tablet 5 mg', 1, '21000.00', '21000.00'),
(59, 27, 'OBT0002', 'Ampicilin', 10, '2300.00', '23000.00'),
(60, 27, 'OBT0001', 'Paracetamol', 3, '12000.00', '36000.00'),
(61, 28, 'OBT0004', 'Ambroxol sirup 30 mg', 5, '28000.00', '140000.00'),
(62, 28, 'OBT0005', 'Amilorida tablet 5 mg', 2, '21000.00', '42000.00'),
(63, 28, 'OBT0001', 'Paracetamol', 1, '12000.00', '12000.00');

-- --------------------------------------------------------

--
-- Table structure for table `penjualan_master`
--

CREATE TABLE `penjualan_master` (
  `id` int(11) NOT NULL,
  `kode_penjualan` varchar(20) NOT NULL,
  `tanggal` datetime NOT NULL,
  `total` decimal(15,2) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nama_petugas` varchar(100) DEFAULT NULL,
  `uang_diterima` decimal(15,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `penjualan_master`
--

INSERT INTO `penjualan_master` (`id`, `kode_penjualan`, `tanggal`, `total`, `user_id`, `nama_petugas`, `uang_diterima`) VALUES
(14, '', '2025-09-06 09:55:28', '85200.00', 1, 'M Wira Satria Buana, S. Kom', '90000.00'),
(15, '', '2025-09-06 10:06:19', '240000.00', 1, 'M Wira Satria Buana, S. Kom', '250000.00'),
(16, '', '2025-09-08 19:01:45', '12000.00', 1, 'M Wira Satria Buana, S. Kom', '15000.00'),
(17, '', '2025-09-09 20:20:16', '470300.00', 1, 'M Wira Satria Buana, S. Kom', '480000.00'),
(18, '', '2025-09-09 20:21:32', '186900.00', 1, 'M Wira Satria Buana, S. Kom', '187000.00'),
(19, 'TRX20250909202619', '2025-09-09 20:26:19', '120000.00', 1, '0', '120000.00'),
(20, 'TRX20250909202900', '2025-09-09 20:29:00', '120000.00', 1, 'M Wira Satria Buana, S. Kom', '120000.00'),
(21, 'TRX20250909202935', '2025-09-09 20:29:35', '302300.00', 1, 'M Wira Satria Buana, S. Kom', '305000.00'),
(22, 'TRX20250909203332', '2025-09-09 20:33:32', '120000.00', 1, 'M Wira Satria Buana, S. Kom', '120000.00'),
(23, 'TRX20250909211127', '2025-09-09 21:11:27', '1760000.00', 1, 'M Wira Satria Buana, S. Kom', '1770000.00'),
(24, 'TRX20250909212529', '2025-09-09 21:25:29', '400000.00', 1, 'M Wira Satria Buana, S. Kom', '400000.00'),
(25, 'TRX20250909213443', '2025-09-09 21:34:43', '380000.00', 1, 'M Wira Satria Buana, S. Kom', '400000.00'),
(26, 'TRX20250909214122', '2025-09-09 21:41:22', '265300.00', 1, 'M Wira Satria Buana, S. Kom', '270000.00'),
(27, 'TRX20250910090048', '2025-09-10 09:00:48', '136000.00', 1, 'M Wira Satria Buana, S. Kom', '150000.00'),
(28, 'TRX20250910091719', '2025-09-10 09:17:19', '194000.00', 1, 'M Wira Satria Buana, S. Kom', '200000.00');

-- --------------------------------------------------------

--
-- Table structure for table `profil_apotek`
--

CREATE TABLE `profil_apotek` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `nama_apoteker` varchar(100) DEFAULT NULL,
  `no_sip` varchar(50) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `kota` varchar(100) DEFAULT NULL,
  `provinsi` varchar(100) DEFAULT NULL,
  `kontak` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `profil_apotek`
--

INSERT INTO `profil_apotek` (`id`, `nama`, `nama_apoteker`, `no_sip`, `alamat`, `kota`, `provinsi`, `kontak`, `email`, `logo`, `created_at`) VALUES
(1, 'Fix Apotek', 'Apt. Giano, S. Farm', '123/123/123/123', 'Perumnas Blok A ', 'Bungo', 'Jambi', '082177846209', 'fixapotek@gmail.com', 'logo_1757420270.png', '2025-09-09 19:17:50');

-- --------------------------------------------------------

--
-- Table structure for table `stok_masuk`
--

CREATE TABLE `stok_masuk` (
  `id` int(11) NOT NULL,
  `tanggal_masuk` date NOT NULL,
  `id_obat` int(11) NOT NULL,
  `id_supplier` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `harga_beli` decimal(15,2) NOT NULL,
  `total` decimal(15,2) NOT NULL,
  `cara_bayar` varchar(20) NOT NULL DEFAULT 'Tunai',
  `jatuh_tempo` date DEFAULT NULL,
  `tanggal_kadaluwarsa` date DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `stok_masuk`
--

INSERT INTO `stok_masuk` (`id`, `tanggal_masuk`, `id_obat`, `id_supplier`, `jumlah`, `harga_beli`, `total`, `cara_bayar`, `jatuh_tempo`, `tanggal_kadaluwarsa`, `keterangan`) VALUES
(7, '2025-09-06', 5, 4, 100, '10000.00', '1000000.00', 'Tempo', '2025-09-13', NULL, '-'),
(9, '2025-09-09', 7, 4, 50, '10000.00', '500000.00', 'Tempo', '2025-09-23', '2025-09-16', '-'),
(10, '2025-09-09', 8, 1, 100, '1000.00', '100000.00', 'Tempo', '2025-09-26', '2025-09-16', '-'),
(11, '2025-09-01', 12, 3, 10, '20000.00', '200000.00', 'Tempo', '2025-09-06', '2025-09-30', '-'),
(12, '2025-09-09', 9, 4, 10, '25000.00', '250000.00', 'Tempo', '2025-09-23', '2025-09-16', '-'),
(13, '2025-09-09', 6, 4, 100, '12000.00', '1200000.00', 'Tempo', '2025-09-17', '2025-09-23', '-');

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `telepon` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`id`, `nama`, `alamat`, `telepon`, `email`) VALUES
(1, 'PT Kimia Farma', 'Jln. Durian', '082177846209', 'kimia@gmail.com'),
(2, 'PT Kimia Farma', 'Jln. Durian', '082177846209', 'kimia@gmail.com'),
(3, 'PT. Sanbe', 'Jl, Apel', '082177846209', 'ape@gmail.com'),
(4, 'PT Anugrah', 'Perumnas tes', '082177846209', 'anugrah@gmail.com'),
(5, 'PT TES', 'tes', 'tes', 'tes@gmail.com'),
(6, 'yes', 'tes', '98077', 'tes@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nik` varchar(20) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `no_hp` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('aktif','nonaktif') DEFAULT 'nonaktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nik`, `nama`, `no_hp`, `email`, `password`, `created_at`, `status`) VALUES
(1, '12345', 'M Wira Satria Buana, S. Kom', '082177846209', 'wiramuhammad16@gmail.com', '$2y$10$i4tKOwMxVyAM8buOQtvHgulhTrq0iL0iFL7Hax/JHNbZnKMIwSCEm', '2025-09-01 08:42:25', 'aktif'),
(3, '123123', 'M. Giano Shaquille Wiandra', '6282177846209', 'giano@gmail.com', '$2y$10$XvBgbXzelRPh5qB1P8AK1.HVRVWoe/Uj384wxZ4ns5nte1fHzF6Mq', '2025-09-01 08:57:21', 'aktif');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `biaya_operasional`
--
ALTER TABLE `biaya_operasional`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_operasional` (`kode_operasional`);

--
-- Indexes for table `master_kategori`
--
ALTER TABLE `master_kategori`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nama_kategori` (`nama_kategori`);

--
-- Indexes for table `master_satuan`
--
ALTER TABLE `master_satuan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nama_satuan` (`nama_satuan`);

--
-- Indexes for table `obat`
--
ALTER TABLE `obat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `penjualan`
--
ALTER TABLE `penjualan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `penjualan_detail`
--
ALTER TABLE `penjualan_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `penjualan_id` (`penjualan_id`);

--
-- Indexes for table `penjualan_master`
--
ALTER TABLE `penjualan_master`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `profil_apotek`
--
ALTER TABLE `profil_apotek`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stok_masuk`
--
ALTER TABLE `stok_masuk`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_obat` (`id_obat`),
  ADD KEY `id_supplier` (`id_supplier`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nik` (`nik`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `biaya_operasional`
--
ALTER TABLE `biaya_operasional`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `master_kategori`
--
ALTER TABLE `master_kategori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `master_satuan`
--
ALTER TABLE `master_satuan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `obat`
--
ALTER TABLE `obat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `penjualan`
--
ALTER TABLE `penjualan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `penjualan_detail`
--
ALTER TABLE `penjualan_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `penjualan_master`
--
ALTER TABLE `penjualan_master`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `profil_apotek`
--
ALTER TABLE `profil_apotek`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `stok_masuk`
--
ALTER TABLE `stok_masuk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `penjualan_detail`
--
ALTER TABLE `penjualan_detail`
  ADD CONSTRAINT `penjualan_detail_ibfk_1` FOREIGN KEY (`penjualan_id`) REFERENCES `penjualan_master` (`id`);

--
-- Constraints for table `stok_masuk`
--
ALTER TABLE `stok_masuk`
  ADD CONSTRAINT `stok_masuk_ibfk_1` FOREIGN KEY (`id_obat`) REFERENCES `obat` (`id`),
  ADD CONSTRAINT `stok_masuk_ibfk_2` FOREIGN KEY (`id_supplier`) REFERENCES `supplier` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
