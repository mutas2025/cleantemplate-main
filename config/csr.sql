-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 27, 2026 at 02:03 AM
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
-- Database: `csr`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `user_id` int(11) NOT NULL,
  `id_number` varchar(50) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `department` varchar(100) DEFAULT NULL,
  `user_type` enum('admin','manager','cashier','staff') NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `terms_agreed` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`user_id`, `id_number`, `full_name`, `department`, `user_type`, `username`, `email`, `contact_number`, `password_hash`, `terms_agreed`, `created_at`, `updated_at`) VALUES
(1, '1997022201', 'Jomar Mutas', 'Sales', 'admin', 'joms', 'jom@gmail.com', '09101882719', '$2y$10$DTopEk350bbaiPFKFpEPw.QSE3eE48qKFCu6AprSp./UidX7QOrw.', 0, '2026-02-27 01:00:57', '2026-02-27 01:03:40');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `middlename` varchar(50) DEFAULT NULL,
  `age` int(11) NOT NULL,
  `year_level` varchar(20) NOT NULL,
  `course` varchar(100) NOT NULL,
  `section` varchar(20) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `contactno` varchar(20) NOT NULL,
  `account_type` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `middlename`, `age`, `year_level`, `course`, `section`, `username`, `password`, `contactno`, `account_type`, `created_at`, `updated_at`) VALUES
(1, 'Jomar', 'Mutas', 'Mangao', 29, '3rd Year', 'BSIT', 'A', 'joms', '$2y$10$bmr.3xP0c3j1EROfLmfi4.v/Jm0HYl7.vgU/sO/OzOezuOfLIZ0vO', '09101882719', 'Teacher', '2026-02-23 00:33:03', '2026-02-26 10:52:50');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `id_number` (`id_number`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_username` (`username`),
  ADD KEY `idx_email` (`email`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
