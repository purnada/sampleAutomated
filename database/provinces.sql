-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 09, 2025 at 06:06 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `niraj_booking`
--

-- --------------------------------------------------------

--
-- Table structure for table `provinces`
--

CREATE TABLE `provinces` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(191) NOT NULL,
  `name_nep` varchar(191) DEFAULT NULL,
  `code` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `provinces`
--

INSERT INTO `provinces` (`id`, `title`, `name_nep`, `code`, `created_at`, `updated_at`) VALUES
(1, 'Koshi', 'प्रदेस १', '1', NULL, '2024-02-14 04:09:53'),
(2, 'Madhesh', 'प्रदेस २', '2', NULL, '2024-02-14 04:10:04'),
(3, 'Bagmati', 'प्रदेस ३', '3', NULL, '2024-02-14 04:10:12'),
(4, 'Gandaki', 'गण्डकी प्रदेश', '4', NULL, '2024-02-14 04:10:19'),
(5, 'Lumbini', 'प्रदेस ५', '5', NULL, '2024-02-14 04:10:29'),
(6, 'Karnali', 'कर्णाली प्रदेश', '6', NULL, '2024-02-14 04:10:41'),
(7, 'Sudurpaschim', 'सुदुर पश्चिम प्रदेश', '7', NULL, '2024-02-14 04:10:53');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `provinces`
--
ALTER TABLE `provinces`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `provinces`
--
ALTER TABLE `provinces`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
