-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 09, 2026 at 02:36 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kg_sentosa`
--

-- --------------------------------------------------------

--
-- Table structure for table `issues`
--

CREATE TABLE `issues` (
  `id` int(11) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `contact` varchar(15) NOT NULL,
  `issue_type` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `issues`
--

INSERT INTO `issues` (`id`, `fname`, `address`, `contact`, `issue_type`, `description`, `photo`, `created_at`) VALUES
(3, 'FARZANAH', 'NO 33 JALAN MEGAH RIA 3, TAMAN RINTING', '0123456789', 'Others (Custom Entry)', 'Heavy rains often overwhelm the drainage systems, causing water to collect on the roads and in low-lying areas', 'uploads/flooded-stormwater-drain.jpg', '2025-01-13 14:45:38'),
(4, 'ABD RAMAN', 'NO 13 JALAN BERLIAN 25 TAMAN CAHAYA MASAI', '0197913144', 'Streetlights Issues', 'STREELIGHTS', '', '2025-01-14 10:14:53'),
(7, 'olivia rodrigo', 'Kampung sentosa', '0123456789', 'Potholes', 'potholes', NULL, '2026-11-09 13:33:55');

-- --------------------------------------------------------

--
-- Table structure for table `myadmin`
--

CREATE TABLE `myadmin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `myadmin`
--

INSERT INTO `myadmin` (`id`, `username`, `email`, `password`) VALUES
(1, 'Aqilah', 'aqilah.raman@s.unikl.edu.my', '$2y$10$KcSdnBDru0aRfPpPEU54veVTdz93BRcK8SR5WvuNqSVfnrqqDWQSG'),
(2, 'Afrina', 'afrina.raman@s.unikl.edu.my', '$2y$10$WsRjj38SeDIw/h2ntBHsJuKWAU/fWqsOijq3kmrkaog0qExAqkOKK'),
(4, 'puteri', 'puteri@gamil.com', '$2y$10$0AynRUWDPy2mcvfp3HJGYeutFY02SR5EXk6.T1Krf34fRn6Swth0e'),
(5, 'faiqa94', 'faiqa@gamil.com', '$2y$10$vINZL75I11Ef3NsI.ljERu55andEUkTIRC7XYe5/dpX4sBK2F2lYK'),
(7, 'Nana', 'nana1@gmail.com', '$2y$10$hsGYgHrvEoMkS2Fb5/kubuEh3egKbIgYjEyleVQMLnlaLsYnP3T/6'),
(10, 'syahadah', 'syahadah@gmail.com', '$2y$10$EFrpZuXHxjOJDPHtkByzLe86EyfVkKmFpKO0qPY9WEPe82PGgBXsW');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `ID` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID`, `username`, `email`, `password`) VALUES
(8, 'Afrina Raman', 'afrinan916@gmail.com', 'b95d7863eb1fa378ba72958cb78415f8'),
(10, 'nuaqilah', 'afrinan916@gmail.com', '784d5710c7d45f1d4c73055946493e61'),
(11, 'AIN ATHIRAH', 'ain@gmail.com', 'edf559915c178c96ff6cb994708c634a'),
(12, 'olivia', 'olivia@gmail.com', '5a6fdf1ec2d9b64595817887becd660d');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `issues`
--
ALTER TABLE `issues`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `myadmin`
--
ALTER TABLE `myadmin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `issues`
--
ALTER TABLE `issues`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `myadmin`
--
ALTER TABLE `myadmin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
