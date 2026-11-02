-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 10, 2026 at 06:59 AM
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
-- Database: `sentosa_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel-cache-afrina@gmail.com|127.0.0.1', 'i:1;', 1767296200),
('laravel-cache-afrina@gmail.com|127.0.0.1:timer', 'i:1767296200;', 1767296200),
('laravel-cache-syamimie@gmail.com|127.0.0.1', 'i:1;', 1767298481),
('laravel-cache-syamimie@gmail.com|127.0.0.1:timer', 'i:1767298481;', 1767298481);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

CREATE TABLE `complaints` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `contact_number` varchar(255) NOT NULL,
  `issue_type` enum('potholes','broken facilities','garbage collection','streetlights issue','flooding','others') NOT NULL,
  `description` text NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Pending',
  `image_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `complaints`
--

INSERT INTO `complaints` (`id`, `user_id`, `name`, `address`, `contact_number`, `issue_type`, `description`, `status`, `image_path`, `created_at`, `updated_at`) VALUES
(1, 1, 'nur amirah', 'no 100, Jalan 8, Seksyen 1, Bangi', '0123123131', 'flooding', 'banjir', 'Pending', 'complaints/X7niSXHwWMsUHYWhNtGkd24YWmxxZI5SXN7YEZbM.jpg', '2025-12-14 08:40:24', '2025-12-14 08:53:57'),
(3, 1, 'aqilah', 'johor', '0132341311', 'flooding', 'sumbat', 'Resolved', 'complaints/WqaGqFN604LbKg8z29uDtq8Z322AEGhAdjSEKBCc.jpg', '2025-12-14 08:56:36', '2025-12-22 21:41:04'),
(4, 1, 'qwertt', 'bangi', '0124567893', 'garbage collection', 'sampah', 'In Progress', 'complaints/9dofeB44NqKU8bK7xlzQeLdt1nXmyyHqgf4nVOZ3.jpg', '2025-12-22 21:44:43', '2025-12-22 21:53:52'),
(5, 4, 'aqilah', 'pasir gudang', '0123652313', 'broken facilities', 'gate rosak', 'In Progress', 'complaints/23k3q7XKfzXFaP0UDAAWkL2jqefOLOWPwMzKryyR.jpg', '2026-01-01 06:26:35', '2026-01-01 09:47:32'),
(6, 4, 'aqilah', 'muar, johor', '0123313231', 'potholes', 'potholes near traffic lights', 'In Progress', 'complaints/UtlBoYnu2y0fAQ9UtzRWI4hRGJKAJNCkXFcyycTf.jpg', '2026-01-01 07:21:39', '2026-01-01 10:30:00'),
(10, 4, 'aqilah afrina', 'melaka', '01923133231', 'broken facilities', 'rossak', 'Pending', 'complaints/ERZjWzyTuH9bfA2hmkBcuvsg87etTKtvwPeI1Hoi.jpg', '2026-01-01 11:07:10', '2026-01-01 11:07:10'),
(11, 5, 'syamimie', 'ampang', '01233131321', 'streetlights issue', 'blinking lights', 'In Progress', 'complaints/8O1DTMRq3t1gQdWzKohg6uI2dfzSN6aMyqUg2gWj.jpg', '2026-01-01 12:11:04', '2026-01-01 12:13:05'),
(12, 4, 'aqilah afrina', 'muar', '01232131311', 'broken facilities', 'pintu rosak', 'Pending', 'complaints/7vd6ffyePsHPugcCTRP6jPEaMlMraykqTElRypfH.jpg', '2026-01-09 08:56:38', '2026-01-09 08:56:38'),
(13, 4, 'aqilah afrina', 'kluang', '01232324542', 'flooding', 'otw banjir', 'In Progress', 'complaints/rcozzaZVECH8QzywO2mzmvMbtzrb57wvIAsNSRAO.jpg', '2026-01-09 09:37:04', '2026-01-09 09:38:22'),
(14, 4, 'aqilah afrina', 'Kampung Sentosa', '01235421321', 'potholes', 'Jalan Rosak', 'In Progress', 'complaints/FfyfBC6JnoTXUjvxVoHJwqQKKjZhgVQjah35dA9G.jpg', '2026-01-09 10:18:52', '2026-01-09 10:19:47'),
(15, 4, 'aqilah afrina', 'Kampung Sentosa', '01232144241', 'flooding', 'Banjir near Jalan 1', 'Pending', 'complaints/wkvm5eK34r4iFKxhtoRgsvzXFaCYohVUpi0wfbtn.jpg', '2026-01-09 11:15:33', '2026-01-09 11:15:33');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_12_14_161724_create_complaints_table', 2),
(5, '2025_12_14_164338_add_status_to_complaints_table', 3),
(6, '2025_12_14_165948_add_is_admin_to_users_table', 4),
(7, '2026_01_01_153226_create_notifications_table', 5);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `title`, `message`, `is_read`, `created_at`, `updated_at`) VALUES
(1, 4, 'Complaint #6 Updated', 'Your complaint status is now: Pending', 1, '2026-01-01 08:24:36', '2026-01-01 08:28:02'),
(2, 4, 'Complaint #6 Updated', 'Your complaint status is now: In Progress', 1, '2026-01-01 08:24:42', '2026-01-01 08:28:03'),
(3, 4, 'Complaint #6 Updated', 'Your complaint status is now: Resolved', 1, '2026-01-01 08:27:35', '2026-01-01 08:28:07'),
(4, 4, 'Complaint #5 Updated', 'Your complaint status is now: In Progress', 1, '2026-01-01 08:39:20', '2026-01-01 09:28:13'),
(5, 4, 'Complaint #5 Updated', 'Your complaint status is now: Pending', 1, '2026-01-01 09:27:37', '2026-01-01 09:28:18'),
(6, 4, 'Complaint #5 Updated', 'Your complaint status is now: In Progress', 1, '2026-01-01 09:47:32', '2026-01-01 11:42:23'),
(7, 4, 'Complaint #6 Updated', 'Your complaint status is now: In Progress', 1, '2026-01-01 09:59:40', '2026-01-01 11:42:38'),
(8, 4, 'Complaint #6 Updated', 'Your complaint status is now: Pending', 1, '2026-01-01 10:11:44', '2026-01-01 11:56:35'),
(9, 4, 'Complaint #6 Updated', 'Your complaint status is now: In Progress', 0, '2026-01-01 10:30:00', '2026-01-01 10:30:00'),
(10, 5, 'Complaint #11 Updated', 'Your complaint status is now: In Progress', 0, '2026-01-01 12:13:05', '2026-01-01 12:13:05'),
(11, 4, 'Complaint #13 Updated', 'Your complaint status is now: In Progress', 0, '2026-01-09 09:38:22', '2026-01-09 09:38:22'),
(12, 4, 'Complaint #14 Updated', 'Your complaint status is now: In Progress', 0, '2026-01-09 10:19:47', '2026-01-09 10:19:47');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('59GfX1a5Fi190ykLaEg2O987kvOhzwE5rHHR2gZD', 4, '127.0.0.1', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.5 Safari/605.1.15', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoicHJxY2o4dnRTNWo4eWpuTnhYNTFYdVp3VjNtU09SRlR4b1VtODNjOCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jb21wbGFpbnRzIjtzOjU6InJvdXRlIjtzOjE2OiJjb21wbGFpbnRzLmluZGV4Ijt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NDt9', 1767986133);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `role` varchar(255) NOT NULL DEFAULT 'resident',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `is_admin`, `role`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'amirah', 'amirah@gmail.com', 0, 'resident', NULL, '$2y$12$wu15fBpIXe6F0or/kfD69.ijtUhvNOAYASUXW7hLYCzKOYoWvnidG', NULL, '2025-12-14 08:03:53', '2025-12-14 08:03:53'),
(2, 'Test User', 'test@example.com', 0, 'resident', '2025-12-14 09:02:41', '$2y$12$Gh4gcuS8x2qSolW2LxzArOYvfx7J1WL6dZP0aIlODrfHXC7OLxR.u', 'Kxcz1wdWYr', '2025-12-14 09:02:41', '2025-12-14 09:02:41'),
(3, 'Admin User', 'admin@example.com', 1, 'resident', '2025-12-14 09:02:41', '$2y$12$tOd5DJQwP8AeGVbg4wW6EuPKZYTHSIDr57AsKhHU/QlfUyyajPpjW', '2hbwY3ftveh0Tv4lt4gyqy1isslM4N34ehiftoDEOPwoBG9ZMa2GFr0YOxf4', '2025-12-14 09:02:41', '2025-12-14 09:02:41'),
(4, 'aqilah afrina', 'aqilah@gmail.com', 0, 'resident', NULL, '$2y$12$ZaG1ckBDI3asb6yzhbOKN.ghz6JryZ3IwMuDvVrYjLvGvjemjKSFS', NULL, '2026-01-01 06:25:34', '2026-01-01 10:57:05'),
(5, 'syamimie', 'mimie@gmail.com', 0, 'resident', NULL, '$2y$12$LrYpZ9png05hF.s/D0w2z.Xc/d6bB4LhMOKCHO1VCeAafnOmf0uMW', NULL, '2026-01-01 12:10:43', '2026-01-01 12:10:43');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `complaints`
--
ALTER TABLE `complaints`
  ADD PRIMARY KEY (`id`),
  ADD KEY `complaints_user_id_foreign` (`user_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_user_id_foreign` (`user_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `complaints`
--
ALTER TABLE `complaints`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `complaints`
--
ALTER TABLE `complaints`
  ADD CONSTRAINT `complaints_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
