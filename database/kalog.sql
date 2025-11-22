-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 21, 2025 at 12:33 AM
-- Server version: 8.0.30
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kalog`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `account_id` int NOT NULL,
  `account_name` varchar(20) DEFAULT NULL,
  `account_username` varchar(12) DEFAULT NULL,
  `account_email` varchar(250) DEFAULT NULL,
  `account_password` varchar(250) DEFAULT NULL,
  `account_isactive` tinyint(1) DEFAULT NULL,
  `account_created` timestamp NULL DEFAULT NULL,
  `account_modified` timestamp NULL DEFAULT NULL,
  `account_level` enum('root','admin','user','') DEFAULT NULL,
  `account_image` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`account_id`, `account_name`, `account_username`, `account_email`, `account_password`, `account_isactive`, `account_created`, `account_modified`, `account_level`, `account_image`) VALUES
(1, 'Super User', 'root', 'root@gmail.com', '$2y$08$OUvUAjIObQFUIWZkT3.mqeH0.jNTOLkyFjATVFsmgwQrJMIgKGWwe', 1, '2021-03-31 17:00:00', '2021-03-31 17:00:00', 'root', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `areas`
--

CREATE TABLE `areas` (
  `id` int NOT NULL,
  `kode_area` varchar(10) NOT NULL,
  `nama_area` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `areas`
--

INSERT INTO `areas` (`id`, `kode_area`, `nama_area`, `created_at`, `updated_at`) VALUES
(1, 'KPT', 'Kertapati', '2025-11-20 14:19:13', '2025-11-20 14:19:13'),
(2, 'BJI', 'Banjarsari', '2025-11-20 14:19:13', '2025-11-20 14:19:13'),
(3, 'BMSS', 'Sukacinta', '2025-11-20 14:19:13', '2025-11-20 14:19:13'),
(4, 'BAU', 'Sukacinta', '2025-11-20 14:19:13', '2025-11-20 14:19:13'),
(5, 'MLI', 'Muaralawai', '2025-11-20 14:19:13', '2025-11-20 14:19:13'),
(6, 'MRP', 'Merapi', '2025-11-20 14:19:13', '2025-11-20 14:19:13'),
(7, 'PL', 'Palembang', '2025-11-20 14:19:13', '2025-11-20 14:19:13'),
(8, 'LM', 'Lampung', '2025-11-20 14:19:13', '2025-11-20 14:19:13'),
(9, 'ARJ', 'Arjawinangun', '2025-11-20 14:19:13', '2025-11-20 14:19:13'),
(10, 'BBT', 'Babat', '2025-11-20 14:19:13', '2025-11-20 14:19:13'),
(11, 'BDG', 'Bandung', '2025-11-20 14:19:13', '2025-11-20 14:19:13'),
(12, 'BKS', 'Bekasi', '2025-11-20 14:19:13', '2025-11-20 14:19:13'),
(13, 'BRM', 'Brambanan', '2025-11-20 14:19:13', '2025-11-20 14:19:13'),
(14, 'CKP', 'Cikampek', '2025-11-20 14:19:13', '2025-11-20 14:19:13'),
(15, 'CRB', 'Cirebon', '2025-11-20 14:19:13', '2025-11-20 14:19:13'),
(16, 'DPS', 'Denpasar', '2025-11-20 14:19:13', '2025-11-20 14:19:13'),
(17, 'JKT-G', 'Jakarta Gudang', '2025-11-20 14:19:13', '2025-11-20 14:19:13'),
(18, 'JICT', 'JICT', '2025-11-20 14:19:13', '2025-11-20 14:19:13'),
(19, 'JTG', 'Jatinegara', '2025-11-20 14:19:13', '2025-11-20 14:19:13'),
(20, 'KLM', 'Kalimas', '2025-11-20 14:19:13', '2025-11-20 14:19:13'),
(21, 'KTP', 'Ketapang', '2025-11-20 14:19:13', '2025-11-20 14:19:13'),
(22, 'KLAR', 'Klari', '2025-11-20 14:19:13', '2025-11-20 14:19:13'),
(23, 'MANGR', 'Manggarai', '2025-11-20 14:19:13', '2025-11-20 14:19:13'),
(24, 'PS', 'Pasar Senen', '2025-11-20 14:19:13', '2025-11-20 14:19:13'),
(25, 'PWK', 'Purwokerto', '2025-11-20 14:19:13', '2025-11-20 14:19:13'),
(26, 'SWJ', 'Sarwajala', '2025-11-20 14:19:13', '2025-11-20 14:19:13'),
(27, 'SGLA', 'Sungai Lagoa', '2025-11-20 14:19:13', '2025-11-20 14:19:13'),
(28, 'SBY', 'Surabaya', '2025-11-20 14:19:13', '2025-11-20 14:19:13'),
(29, 'SMG', 'Semarang', '2025-11-20 14:19:13', '2025-11-20 14:19:13'),
(30, 'OSLO', 'Solo', '2025-11-20 14:19:13', '2025-11-20 14:19:13'),
(31, 'NAM', 'Nambo', '2025-11-20 14:19:13', '2025-11-20 14:19:13'),
(32, 'YGY', 'Yogyakarta', '2025-11-20 14:19:13', '2025-11-20 14:19:13');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_id` int NOT NULL,
  `category_name` varchar(250) DEFAULT NULL,
  `category_description` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `category_name`, `category_description`) VALUES
(1, 'Telkomsel', 'Telkomsel'),
(2, 'XL Axiata', 'XL Axiata'),
(3, 'AXIS', 'AXIS'),
(4, 'Indosat', 'Indosat'),
(5, 'Smartfren', 'Smartfren'),
(6, '3 Indonesia', '3 Indonesia');

-- --------------------------------------------------------

--
-- Table structure for table `facilities`
--

CREATE TABLE `facilities` (
  `id` int NOT NULL,
  `area_id` int NOT NULL,
  `facility_type_id` int NOT NULL,
  `vendor_id` int DEFAULT NULL,
  `tipe` varchar(200) DEFAULT NULL,
  `kapasitas` varchar(100) DEFAULT NULL,
  `jumlah` int NOT NULL DEFAULT '1',
  `tahun_unit` year DEFAULT NULL,
  `awal_sewa` date DEFAULT NULL,
  `akhir_sewa` date DEFAULT NULL,
  `total_harga_sewa` decimal(20,2) DEFAULT NULL,
  `no_perjanjian` varchar(100) DEFAULT NULL,
  `status` enum('active','inactive','maintenance') NOT NULL DEFAULT 'active',
  `keterangan` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `facility_maintenance`
--

CREATE TABLE `facility_maintenance` (
  `id` int NOT NULL,
  `facility_id` int NOT NULL,
  `tanggal_maintenance` date NOT NULL,
  `jenis_maintenance` enum('routine','emergency','upgrade') NOT NULL,
  `keterangan` text,
  `biaya` decimal(15,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `facility_types`
--

CREATE TABLE `facility_types` (
  `id` int NOT NULL,
  `nama_tipe` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `facility_types`
--

INSERT INTO `facility_types` (`id`, `nama_tipe`, `created_at`, `updated_at`) VALUES
(1, 'Belt Conveyor', '2025-11-20 14:19:16', '2025-11-20 14:19:16'),
(2, 'Bulldozer', '2025-11-20 14:19:16', '2025-11-20 14:19:16'),
(3, 'Coal Container', '2025-11-20 14:19:16', '2025-11-20 14:19:16'),
(4, 'Dump Truck', '2025-11-20 14:19:16', '2025-11-20 14:19:16'),
(5, 'Excavator', '2025-11-20 14:19:16', '2025-11-20 14:19:16'),
(6, 'Excavator Long Arm', '2025-11-20 14:19:16', '2025-11-20 14:19:16'),
(7, 'Excavator Mini', '2025-11-20 14:19:16', '2025-11-20 14:19:16'),
(8, 'Forklift', '2025-11-20 14:19:16', '2025-11-20 14:19:16'),
(9, 'Genset', '2025-11-20 14:19:16', '2025-11-20 14:19:16'),
(10, 'Gantry Crane', '2025-11-20 14:19:16', '2025-11-20 14:19:16'),
(11, 'Mobil Box', '2025-11-20 14:19:16', '2025-11-20 14:19:16'),
(12, 'Motor Diesel', '2025-11-20 14:19:16', '2025-11-20 14:19:16'),
(13, 'Reach Stacker', '2025-11-20 14:19:16', '2025-11-20 14:19:16'),
(14, 'Truck Box', '2025-11-20 14:19:16', '2025-11-20 14:19:16'),
(15, 'Truck Trailer', '2025-11-20 14:19:16', '2025-11-20 14:19:16'),
(16, 'Travelling Hopper', '2025-11-20 14:19:16', '2025-11-20 14:19:16'),
(17, 'Tangki Timbun', '2025-11-20 14:19:16', '2025-11-20 14:19:16'),
(18, 'Wheel Loader', '2025-11-20 14:19:16', '2025-11-20 14:19:16');

-- --------------------------------------------------------

--
-- Table structure for table `phone`
--

CREATE TABLE `phone` (
  `phone_id` int NOT NULL,
  `category_id` int DEFAULT NULL,
  `phone_number` varchar(15) DEFAULT NULL,
  `phone_created` datetime DEFAULT NULL,
  `phone_modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `phone`
--

INSERT INTO `phone` (`phone_id`, `category_id`, `phone_number`, `phone_created`, `phone_modified`) VALUES
(1, 1, '082182461928', '2021-04-05 16:05:02', '2021-04-05 16:05:02'),
(2, 1, '082171536937', '2021-04-05 16:04:59', '2021-04-05 16:04:59'),
(3, 1, '082127399912', '2021-04-05 16:04:50', '2021-04-05 16:04:50'),
(4, 2, '087765938015', '2021-04-05 16:04:41', '2021-04-05 16:04:41'),
(5, 2, '087733668219', '2021-04-05 16:05:27', '2021-04-05 16:05:27'),
(6, 2, '087791238765', '2021-04-05 16:05:41', '2021-04-05 16:05:41'),
(7, 3, '083287347193', '2021-04-05 16:06:10', '2021-04-05 16:06:10'),
(8, 3, '083292771927', '2021-04-05 16:06:21', '2021-04-05 16:06:21'),
(9, 3, '083295922295', '2021-04-05 16:06:32', '2021-04-05 16:06:32'),
(10, 4, '085687124819', '2021-04-06 21:20:05', '2021-04-06 21:20:05'),
(11, 4, '085633729821', '2021-04-06 21:20:39', '2021-04-06 21:20:39'),
(12, 4, '085688921846', '2021-04-06 21:20:50', '2021-04-06 21:20:50'),
(13, 5, '088171937294', '2021-04-06 21:21:19', '2021-04-06 21:21:19'),
(14, 5, '088181937183', '2021-04-06 21:21:30', '2021-04-06 21:21:30'),
(15, 5, '088188162871', '2021-04-06 21:21:41', '2021-04-06 21:21:41'),
(16, 6, '089877181681', '2021-04-06 21:22:31', '2021-04-06 21:22:31'),
(17, 6, '089892749102', '2021-04-06 21:22:49', '2021-04-06 21:22:49'),
(18, 1, '089838174232', '2021-04-06 21:23:25', '2021-04-06 21:23:25'),
(19, 6, '081381739634', '2021-04-07 08:00:41', '2021-04-07 08:00:41'),
(20, 6, '081384627195', '2021-04-07 07:59:36', '2021-04-07 07:59:36'),
(21, 6, '081377634568', '2021-04-07 07:59:50', '2021-04-07 07:59:50'),
(22, 6, '081387214567', '2021-04-07 08:52:47', '2021-04-07 08:52:47');

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `id` int NOT NULL,
  `nama_vendor` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `vendors`
--

INSERT INTO `vendors` (`id`, `nama_vendor`, `created_at`, `updated_at`) VALUES
(1, 'PT EMITRACO INV. MANDIRI', '2025-11-20 14:19:15', '2025-11-20 14:19:15'),
(2, 'PT TAS', '2025-11-20 14:19:15', '2025-11-20 14:19:15'),
(3, 'PT SONS', '2025-11-20 14:19:15', '2025-11-20 14:19:15'),
(4, 'PT PKP', '2025-11-20 14:19:15', '2025-11-20 14:19:15'),
(5, 'PT TCI', '2025-11-20 14:19:15', '2025-11-20 14:19:15'),
(6, 'PT IMS', '2025-11-20 14:19:15', '2025-11-20 14:19:15'),
(7, 'PT RMK', '2025-11-20 14:19:15', '2025-11-20 14:19:15'),
(8, 'PT KALOG', '2025-11-20 14:19:15', '2025-11-20 14:19:15'),
(9, 'PT LEMO', '2025-11-20 14:19:15', '2025-11-20 14:19:15'),
(10, 'PT CSM', '2025-11-20 14:19:15', '2025-11-20 14:19:15'),
(11, 'PT Harjatama Sentosa', '2025-11-20 14:19:15', '2025-11-20 14:19:15'),
(12, 'PT PDT', '2025-11-20 14:19:15', '2025-11-20 14:19:15'),
(13, 'PT BHM', '2025-11-20 14:19:15', '2025-11-20 14:19:15'),
(14, 'PT BLP', '2025-11-20 14:19:15', '2025-11-20 14:19:15'),
(15, 'PT Batavia Prosperiondo', '2025-11-20 14:19:15', '2025-11-20 14:19:15');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`account_id`);

--
-- Indexes for table `areas`
--
ALTER TABLE `areas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_area` (`kode_area`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `facilities`
--
ALTER TABLE `facilities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `area_id` (`area_id`),
  ADD KEY `facility_type_id` (`facility_type_id`),
  ADD KEY `vendor_id` (`vendor_id`);

--
-- Indexes for table `facility_maintenance`
--
ALTER TABLE `facility_maintenance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `facility_id` (`facility_id`);

--
-- Indexes for table `facility_types`
--
ALTER TABLE `facility_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nama_tipe` (`nama_tipe`);

--
-- Indexes for table `phone`
--
ALTER TABLE `phone`
  ADD PRIMARY KEY (`phone_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nama_vendor` (`nama_vendor`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account`
--
ALTER TABLE `account`
  MODIFY `account_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `areas`
--
ALTER TABLE `areas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `facilities`
--
ALTER TABLE `facilities`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `facility_maintenance`
--
ALTER TABLE `facility_maintenance`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `facility_types`
--
ALTER TABLE `facility_types`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `phone`
--
ALTER TABLE `phone`
  MODIFY `phone_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `facilities`
--
ALTER TABLE `facilities`
  ADD CONSTRAINT `facilities_ibfk_1` FOREIGN KEY (`area_id`) REFERENCES `areas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `facilities_ibfk_2` FOREIGN KEY (`facility_type_id`) REFERENCES `facility_types` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `facilities_ibfk_3` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `facility_maintenance`
--
ALTER TABLE `facility_maintenance`
  ADD CONSTRAINT `facility_maintenance_ibfk_1` FOREIGN KEY (`facility_id`) REFERENCES `facilities` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `phone`
--
ALTER TABLE `phone`
  ADD CONSTRAINT `phone_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
