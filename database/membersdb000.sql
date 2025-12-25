-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 25, 2025 at 08:48 AM
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
-- Database: `membersdb000`
--

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `member_id` varchar(20) NOT NULL,
  `type_id` int(11) NOT NULL COMMENT 'เชื่อมโยงกับ member_types',
  `prefix` varchar(10) NOT NULL,
  `member_fname` varchar(100) NOT NULL,
  `member_lname` varchar(100) NOT NULL,
  `member_email` varchar(100) DEFAULT NULL,
  `member_phone` varchar(20) DEFAULT NULL,
  `member_address` text DEFAULT NULL,
  `member_photo` varchar(255) DEFAULT 'default-avatar.png',
  `member_status` tinyint(1) DEFAULT 1 COMMENT '1=ใช้งาน, 0=ระงับ',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`member_id`, `type_id`, `prefix`, `member_fname`, `member_lname`, `member_email`, `member_phone`, `member_address`, `member_photo`, `member_status`, `created_at`, `updated_at`) VALUES
('MEM000000001', 1, 'นาย', 'ชัยณรงค์', 'จันทร์ตูม', 'a@gmail.com', '083-5756951', '248', 'MEM000000001_1766648794.png', 1, '2025-12-25 07:46:34', '2025-12-25 07:46:34');

-- --------------------------------------------------------

--
-- Table structure for table `member_types`
--

CREATE TABLE `member_types` (
  `type_id` int(11) NOT NULL,
  `type_name` varchar(50) NOT NULL COMMENT 'ชื่อประเภทสมาชิก เช่น General, VIP',
  `discount_rate` decimal(5,2) DEFAULT 0.00 COMMENT 'ส่วนลดพิเศษสำหรับสมาชิกประเภทนี้',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `member_types`
--

INSERT INTO `member_types` (`type_id`, `type_name`, `discount_rate`, `created_at`) VALUES
(1, 'General', 5.00, '2025-12-25 04:54:16'),
(2, 'VIP', 10.00, '2025-12-25 04:54:16');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `username` varchar(20) NOT NULL,
  `member_id` varchar(20) NOT NULL COMMENT 'เชื่อมโยงกับ member_types',
  `passwd` text NOT NULL COMMENT 'เข้ารหัสแบบ MD5',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`member_id`),
  ADD UNIQUE KEY `member_email` (`member_email`),
  ADD UNIQUE KEY `member_phone` (`member_phone`),
  ADD KEY `FK_member_membertype` (`type_id`);

--
-- Indexes for table `member_types`
--
ALTER TABLE `member_types`
  ADD PRIMARY KEY (`type_id`),
  ADD UNIQUE KEY `type_name` (`type_name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`username`),
  ADD KEY `FK_users_members` (`member_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `member_types`
--
ALTER TABLE `member_types`
  MODIFY `type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `members`
--
ALTER TABLE `members`
  ADD CONSTRAINT `FK_member_membertype` FOREIGN KEY (`type_id`) REFERENCES `member_types` (`type_id`) ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `FK_users_members` FOREIGN KEY (`member_id`) REFERENCES `members` (`member_id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
