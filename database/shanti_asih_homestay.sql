-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 17, 2026 at 03:43 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shanti_asih_homestay`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id_booking` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `check_in` date NOT NULL,
  `check_out` date NOT NULL,
  `jumlah_tamu` int(11) NOT NULL,
  `total_harga` decimal(12,2) NOT NULL,
  `status` enum('pending','confirmed','cancelled','completed') DEFAULT 'pending',
  `catatan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id_booking`, `user_id`, `room_id`, `check_in`, `check_out`, `jumlah_tamu`, `total_harga`, `status`, `catatan`, `created_at`) VALUES
(1, 1, 1, '2026-06-20', '2026-06-22', 2, 700000.00, 'confirmed', 'Tamu meminta kamar dekat kolam renang.', '2026-06-14 09:40:47'),
(2, 1, 2, '2026-07-05', '2026-07-07', 2, 700000.00, 'cancelled', 'Menunggu konfirmasi admin.', '2026-06-14 09:40:47'),
(3, 1, 3, '2026-08-10', '2026-08-13', 1, 1050000.00, 'completed', 'Booking selesai tanpa catatan tambahan.', '2026-06-14 09:40:47'),
(4, 2, 2, '2026-06-16', '2026-06-27', 2, 3850000.00, 'confirmed', '-', '2026-06-16 13:26:02'),
(5, 1, 4, '2026-06-22', '2026-06-24', 1, 700000.00, 'cancelled', '', '2026-06-16 14:02:19'),
(6, 1, 4, '2026-06-21', '2026-06-23', 1, 700000.00, 'confirmed', '', '2026-06-16 14:03:11'),
(7, 1, 4, '2026-06-21', '2026-06-24', 1, 1050000.00, 'confirmed', '', '2026-06-16 14:06:31'),
(8, 2, 3, '2026-07-08', '2026-07-10', 2, 700000.00, 'cancelled', 'Tidak ada', '2026-06-17 08:42:31'),
(9, 1, 3, '2026-07-01', '2026-07-02', 1, 350000.00, 'pending', '', '2026-06-17 08:57:06'),
(10, 1, 1, '2026-07-31', '2026-08-01', 1, 350000.00, 'pending', '', '2026-06-17 09:54:05'),
(11, 3, 4, '2026-07-09', '2026-07-10', 2, 350000.00, 'confirmed', 'Sediakan minuman herbal', '2026-06-17 11:17:48');

-- --------------------------------------------------------

--
-- Table structure for table `facilities`
--

CREATE TABLE `facilities` (
  `id_facility` int(11) NOT NULL,
  `nama_facility` varchar(100) NOT NULL,
  `icon` varchar(100) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id_room` int(11) NOT NULL,
  `nama_room` varchar(100) NOT NULL,
  `harga` decimal(12,2) NOT NULL,
  `kapasitas` int(11) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `status` enum('available','unavailable') DEFAULT 'available',
  `main_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id_room`, `nama_room`, `harga`, `kapasitas`, `deskripsi`, `status`, `main_image`, `created_at`) VALUES
(1, 'Standard Room 1', 350000.00, 2, 'Kamar nyaman dengan AC, WiFi, kamar mandi dalam, dan akses ke kolam renang.', 'available', 'room-1.jpg', '2026-06-02 01:32:23'),
(2, 'Standard Room 2', 350000.00, 2, 'Kamar standar dengan suasana tenang, cocok untuk wisatawan yang ingin menikmati Ubud.', 'available', 'room-2.jpg', '2026-06-02 01:32:23'),
(3, 'Standard Room 3', 350000.00, 2, 'Kamar dengan akses mudah ke area Yoga Sala dan lingkungan homestay yang asri.', 'available', 'room-3.jpg', '2026-06-02 01:32:23'),
(4, 'Standard Room 4', 350000.00, 2, 'Kamar standar yang dekat dengan kolam renang dan area bersantai homestay.', 'available', 'room-4.jpg', '2026-06-02 01:32:23');

-- --------------------------------------------------------

--
-- Table structure for table `room_facilities`
--

CREATE TABLE `room_facilities` (
  `id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `facility_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `room_images`
--

CREATE TABLE `room_images` (
  `id_image` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama`, `email`, `password`, `no_hp`, `role`, `created_at`) VALUES
(1, 'Wayan', 'wayan@gmail.com', '$2y$10$rwVUK8V2t3pmX2uafvbqz.vzlX4tDWxoKyrUkDE5t7KmDm0Tej85C', '08123456789', 'user', '2026-06-13 15:01:59'),
(2, 'Radit', 'jalan@gmail.com', '$2y$10$Q08jqjN9ZUvTldnD1d7PGeuODRd2AMyLK4PKfbM6YvE6Kzte9C/hC', '123456789', 'admin', '2026-06-13 15:19:17'),
(3, 'putra', 'putra@gmail.com', '$2y$10$Vj2Huv3dDJFECSYppSoWJuVqDNXBZ/ddE8woU2fzfi449HjQQOzZS', '081456873', 'user', '2026-06-17 11:14:03');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id_booking`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `room_id` (`room_id`);

--
-- Indexes for table `facilities`
--
ALTER TABLE `facilities`
  ADD PRIMARY KEY (`id_facility`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id_room`);

--
-- Indexes for table `room_facilities`
--
ALTER TABLE `room_facilities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `room_id` (`room_id`),
  ADD KEY `facility_id` (`facility_id`);

--
-- Indexes for table `room_images`
--
ALTER TABLE `room_images`
  ADD PRIMARY KEY (`id_image`),
  ADD KEY `room_id` (`room_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id_booking` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `facilities`
--
ALTER TABLE `facilities`
  MODIFY `id_facility` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id_room` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `room_facilities`
--
ALTER TABLE `room_facilities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `room_images`
--
ALTER TABLE `room_images`
  MODIFY `id_image` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id_room`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `room_facilities`
--
ALTER TABLE `room_facilities`
  ADD CONSTRAINT `room_facilities_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id_room`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `room_facilities_ibfk_2` FOREIGN KEY (`facility_id`) REFERENCES `facilities` (`id_facility`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `room_images`
--
ALTER TABLE `room_images`
  ADD CONSTRAINT `room_images_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id_room`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
