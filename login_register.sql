-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 15, 2025 at 02:06 PM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 7.3.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `login_register`
--

-- --------------------------------------------------------

--
-- Table structure for table `myadmin`
--

CREATE TABLE `myadmin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID`, `username`, `email`, `password`) VALUES
(8, 'Afrina Raman', 'afrinan916@gmail.com', 'b95d7863eb1fa378ba72958cb78415f8'),
(10, 'nuaqilah', 'afrinan916@gmail.com', '784d5710c7d45f1d4c73055946493e61'),
(11, 'AIN ATHIRAH', 'ain@gmail.com', 'edf559915c178c96ff6cb994708c634a');

--
-- Indexes for dumped tables
--

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
-- AUTO_INCREMENT for table `myadmin`
--
ALTER TABLE `myadmin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
