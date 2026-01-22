-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 22, 2026 at 03:31 AM
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
-- Database: `tool share application`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` varchar(36) NOT NULL,
  `name` varchar(100) NOT NULL,
  `status` enum('ACTIVE','INACTIVE') DEFAULT 'ACTIVE',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `status`, `created_at`) VALUES
('cat_697130402d5ad4.13313761', 'ELECTRONICS', 'ACTIVE', '2026-01-22 02:00:00'),
('cat_697130495f5519.91957699', 'CLOTHS', 'ACTIVE', '2026-01-22 02:00:09'),
('cat_69715e1f3cf7d8.70552961', 'HOME APPLIANCE', 'ACTIVE', '2026-01-22 05:15:43'),
('cat_69715e28cde182.94607711', 'HOBBIES & SPORTS', 'ACTIVE', '2026-01-22 05:15:52'),
('cat_69715e300fa572.42110161', 'FURNITURE', 'ACTIVE', '2026-01-22 05:16:00'),
('cat_69715e3b67c977.83470690', 'SPORTS', 'ACTIVE', '2026-01-22 05:16:11');

-- --------------------------------------------------------

--
-- Table structure for table `rental_logs`
--

CREATE TABLE `rental_logs` (
  `id` varchar(50) NOT NULL,
  `rent_id` varchar(50) DEFAULT NULL,
  `tool_id` varchar(50) DEFAULT NULL,
  `tool_name` varchar(255) DEFAULT NULL,
  `owner_id` varchar(50) DEFAULT NULL,
  `owner_name` varchar(255) DEFAULT NULL,
  `renter_id` varchar(50) DEFAULT NULL,
  `renter_name` varchar(255) DEFAULT NULL,
  `rent_start_date` date DEFAULT NULL,
  `rent_end_date` date DEFAULT NULL,
  `return_confirmed_date` datetime DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `rental_logs`
--

INSERT INTO `rental_logs` (`id`, `rent_id`, `tool_id`, `tool_name`, `owner_id`, `owner_name`, `renter_id`, `renter_name`, `rent_start_date`, `rent_end_date`, `return_confirmed_date`, `total_amount`, `created_at`) VALUES
('log_69715440e0e2b', 'rent_6971540ba9b245.24689426', 'tool_697153e11a1c92.62002976', NULL, 'usr_697131c65e14d1.84995622', NULL, 'usr_69701123ead0f0.44968511', NULL, '2026-01-07', '2026-01-23', '2026-01-21 00:00:00', 340.00, '2026-01-22 04:33:36'),
('log_6971651e93b32', 'rent_697162e79671f9.06291743', 'tool_697153e11a1c92.62002976', NULL, 'usr_697131c65e14d1.84995622', NULL, 'usr_6971624a34e6a8.03028863', NULL, '2026-01-21', '2026-01-23', '2026-01-22 00:00:00', 60.00, '2026-01-22 05:45:34');

-- --------------------------------------------------------

--
-- Table structure for table `rent_requests`
--

CREATE TABLE `rent_requests` (
  `id` varchar(36) NOT NULL,
  `tool_id` varchar(36) NOT NULL,
  `owner_id` varchar(36) NOT NULL,
  `renter_id` varchar(36) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` enum('REQUESTED','ACCEPTED','REJECTED','RETURN_REQUESTED','RETURNED') DEFAULT 'REQUESTED',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `rent_requests`
--

INSERT INTO `rent_requests` (`id`, `tool_id`, `owner_id`, `renter_id`, `start_date`, `end_date`, `status`, `created_at`, `updated_at`) VALUES
('rent_697137c962a003.97144393', 'tool_6971320699aae8.87442537', 'usr_697131c65e14d1.84995622', 'usr_69701123ead0f0.44968511', '2026-01-23', '2026-01-23', 'RETURNED', '2026-01-22 02:32:09', '2026-01-22 03:03:15'),
('rent_69715249c30f62.88162263', 'tool_6971320699aae8.87442537', 'usr_697131c65e14d1.84995622', 'usr_69701123ead0f0.44968511', '2026-01-22', '2026-01-23', 'RETURNED', '2026-01-22 04:25:13', '2026-01-22 04:26:14'),
('rent_697152bc9e6c62.51478937', 'tool_6971320699aae8.87442537', 'usr_697131c65e14d1.84995622', 'usr_69701123ead0f0.44968511', '2026-01-22', '2026-01-23', 'RETURNED', '2026-01-22 04:27:08', '2026-01-22 04:28:15'),
('rent_6971540ba9b245.24689426', 'tool_697153e11a1c92.62002976', 'usr_697131c65e14d1.84995622', 'usr_69701123ead0f0.44968511', '2026-01-07', '2026-01-23', 'RETURNED', '2026-01-22 04:32:43', '2026-01-22 04:33:36'),
('rent_697162ca571235.69813204', 'tool_6971620dc7f2f5.23875747', 'usr_69715f9f9c2dd8.81487621', 'usr_6971624a34e6a8.03028863', '2026-01-20', '2026-01-23', 'REQUESTED', '2026-01-22 05:35:38', NULL),
('rent_697162e79671f9.06291743', 'tool_697153e11a1c92.62002976', 'usr_697131c65e14d1.84995622', 'usr_6971624a34e6a8.03028863', '2026-01-21', '2026-01-23', 'RETURNED', '2026-01-22 05:36:07', '2026-01-22 05:45:34');

-- --------------------------------------------------------

--
-- Table structure for table `tools`
--

CREATE TABLE `tools` (
  `id` varchar(36) NOT NULL,
  `user_id` varchar(36) NOT NULL,
  `category_id` varchar(36) NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `price_per_day` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `location` varchar(150) DEFAULT NULL,
  `status` enum('AVAILABLE','RENTED','INACTIVE') DEFAULT 'AVAILABLE',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tools`
--

INSERT INTO `tools` (`id`, `user_id`, `category_id`, `name`, `description`, `price_per_day`, `quantity`, `location`, `status`, `created_at`) VALUES
('tool_6971320699aae8.87442537', 'usr_697131c65e14d1.84995622', 'cat_697130495f5519.91957699', 'suits', 'good', 30.00, 5, 'kuril', 'AVAILABLE', '2026-01-22 02:07:34'),
('tool_697153e11a1c92.62002976', 'usr_697131c65e14d1.84995622', 'cat_697130402d5ad4.13313761', 'keyboard', 'good', 20.00, 5, 'kuril', 'AVAILABLE', '2026-01-22 04:32:01'),
('tool_697161258be899.78357555', 'usr_69715f9f9c2dd8.81487621', 'cat_69715e28cde182.94607711', 'criclet bat ', 'it new and it available in our shope', 20.00, 5, 'kuril', 'AVAILABLE', '2026-01-22 05:28:37'),
('tool_697161ab50b415.81771117', 'usr_69715f9f9c2dd8.81487621', 'cat_69715e28cde182.94607711', 'football', 'its good', 20.00, 10, 'kuril', 'AVAILABLE', '2026-01-22 05:30:51'),
('tool_6971620dc7f2f5.23875747', 'usr_69715f9f9c2dd8.81487621', 'cat_69715e28cde182.94607711', 'cricket stump', 'its quality good', 5.00, 5, 'kuril', 'AVAILABLE', '2026-01-22 05:32:29');

-- --------------------------------------------------------

--
-- Table structure for table `tool_images`
--

CREATE TABLE `tool_images` (
  `id` varchar(36) NOT NULL,
  `tool_id` varchar(36) NOT NULL,
  `image_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tool_images`
--

INSERT INTO `tool_images` (`id`, `tool_id`, `image_path`) VALUES
('img_697132069a4106.65230177', 'tool_6971320699aae8.87442537', 'uploads/tools/tool_69713206994e00.21609610.jpg'),
('img_697161258c8d81.03695812', 'tool_697161258be899.78357555', 'uploads/tools/tool_697161258b65c4.19780204.jpg'),
('img_697161ab51bd67.58780552', 'tool_697161ab50b415.81771117', 'uploads/tools/tool_697161ab4f45a8.75800370.jpg'),
('img_6971620dc930a6.16237922', 'tool_6971620dc7f2f5.23875747', 'uploads/tools/tool_6971620dc73112.69193703.jpg'),
('img_69717eec8156c5.60585730', 'tool_697153e11a1c92.62002976', 'uploads/tools/tool_69717eec80ac47.53708594.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` varchar(36) NOT NULL,
  `name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `nid_number` varchar(50) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `role` enum('USER','VENDOR','ADMIN') NOT NULL,
  `status` enum('ACTIVE','INACTIVE','BLOCKED') DEFAULT 'ACTIVE',
  `shop_number` varchar(100) DEFAULT NULL,
  `business_card_no` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `nid_number`, `password`, `profile_image`, `role`, `status`, `shop_number`, `business_card_no`, `created_at`) VALUES
('usr_69700ea7d25c48.00062302', 'kahium ahamed fahim', 'kahiumahamedfahim@gmail.com', '01841572001', '31122001', '$2y$10$/Yv5mmp.i4/.W1X1e88PXeMUhV8JBhRZCxTINJlRU50nBB/mimObG', 'uploads/profile/profile_6971732bd6fc63.84123083.png', 'ADMIN', 'ACTIVE', NULL, NULL, '2026-01-21 05:24:23'),
('usr_69701123ead0f0.44968511', 'ontar', 'ontar@gmail.com', '01786139048', '937493274982340', '$2y$10$sZOH7jA2o7hwKvlXPMzRJ./kVHmvXJ433VRbSzVthInCTtbAG3pCm', 'uploads/profile/profile_69701123e800a1.80500263.avif', 'USER', 'ACTIVE', NULL, NULL, '2026-01-21 05:35:00'),
('usr_697012e2ad1990.30045334', 'tanbir', 'tanbir@gmail.com', '01786139050', '937493274988', '$2y$10$R8s0UEF0/UxxfthODEQmU.QM7KKM4vQJDPS0dRfuR.E6XcyB9SBPa', 'uploads/profile/profile_697012e2ac3385.43045262.jpg', 'USER', 'ACTIVE', NULL, NULL, '2026-01-21 05:42:26'),
('usr_697131c65e14d1.84995622', 'Rahim enterprice', 'rahimenterprice@gmail.com', '01341572001', NULL, '$2y$10$r0dUgpvkKRogu.1oYPTeuuJVkg9zfXCOmiK6z5CwwYpYNxU6Zsds6', 'uploads/profile/profile_697131c65c6597.33592025.jpg', 'VENDOR', 'ACTIVE', '223344', '3434', '2026-01-22 02:06:30'),
('usr_69715f9f9c2dd8.81487621', 'xys sports', 'xyssports@gmail.com', '01841572002', NULL, '$2y$10$.VNKCH7Z5B6dbVEsA0V7dOt/WXrXYrHjT2lCfcBGwn1FJyE9NakKm', 'uploads/profile/profile_69715f9f9abc05.79859322.jpg', 'VENDOR', 'ACTIVE', '22334411223', '347903473', '2026-01-22 05:22:07'),
('usr_6971624a34e6a8.03028863', 'Asfaq Rayhan', 'asfaqRayhan@gmail.com', '01831572001', '3434334', '$2y$10$DGZebphkXm420kpf4mb7der.JdnVk2A2nFkvDh4KrGd4k68qyWbnK', 'uploads/profile/profile_6971624a338984.49929833.avif', 'USER', 'ACTIVE', NULL, NULL, '2026-01-22 05:33:30');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `rental_logs`
--
ALTER TABLE `rental_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rent_requests`
--
ALTER TABLE `rent_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tool_id` (`tool_id`),
  ADD KEY `owner_id` (`owner_id`),
  ADD KEY `renter_id` (`renter_id`);

--
-- Indexes for table `tools`
--
ALTER TABLE `tools`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `tool_images`
--
ALTER TABLE `tool_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tool_id` (`tool_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_email` (`email`),
  ADD UNIQUE KEY `uq_phone` (`phone`),
  ADD UNIQUE KEY `uq_nid` (`nid_number`),
  ADD UNIQUE KEY `uq_shop` (`shop_number`),
  ADD UNIQUE KEY `uq_business_card` (`business_card_no`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `rent_requests`
--
ALTER TABLE `rent_requests`
  ADD CONSTRAINT `rent_requests_ibfk_1` FOREIGN KEY (`tool_id`) REFERENCES `tools` (`id`),
  ADD CONSTRAINT `rent_requests_ibfk_2` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `rent_requests_ibfk_3` FOREIGN KEY (`renter_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `tools`
--
ALTER TABLE `tools`
  ADD CONSTRAINT `tools_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tools_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `tool_images`
--
ALTER TABLE `tool_images`
  ADD CONSTRAINT `tool_images_ibfk_1` FOREIGN KEY (`tool_id`) REFERENCES `tools` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
