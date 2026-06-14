-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 12, 2026 at 01:59 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_turnamen_futsal`
--

-- --------------------------------------------------------

--
-- Table structure for table `tim_peserta`
--

CREATE TABLE `tim_peserta` (
  `id_tim` int NOT NULL,
  `nama_tim` varchar(100) NOT NULL,
  `nama_manajer` varchar(100) NOT NULL,
  `berkas_pemain` varchar(255) DEFAULT NULL,
  `ttd_digital` text,
  `status_berkas` enum('Menunggu','Lengkap','Ditolak') DEFAULT 'Menunggu',
  `waktu_daftar` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tim_peserta`
--

INSERT INTO `tim_peserta` (`id_tim`, `nama_tim`, `nama_manajer`, `berkas_pemain`, `ttd_digital`, `status_berkas`, `waktu_daftar`) VALUES
(1, 'TIF FC', 'Budi Santoso', NULL, NULL, 'Lengkap', '2026-06-12 01:40:59'),
(2, 'Hukum United', 'Andi Pratama', NULL, NULL, 'Menunggu', '2026-06-12 01:40:59');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `username`, `password`, `nama_lengkap`) VALUES
(1, 'admin', 'admin123', 'Administrator Futsal');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tim_peserta`
--
ALTER TABLE `tim_peserta`
  ADD PRIMARY KEY (`id_tim`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tim_peserta`
--
ALTER TABLE `tim_peserta`
  MODIFY `id_tim` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
