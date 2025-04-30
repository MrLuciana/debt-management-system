-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 30, 2025 at 09:52 AM
-- Server version: 11.5.2-MariaDB
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db-debt`
--

-- --------------------------------------------------------

--
-- Table structure for table `debts`
--

DROP TABLE IF EXISTS `debts`;
CREATE TABLE IF NOT EXISTS `debts` (
  `debt_id` int(11) NOT NULL AUTO_INCREMENT,
  `debtor_name` varchar(100) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `interest_rate` decimal(5,2) DEFAULT 0.00,
  `start_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`debt_id`),
  KEY `idx_debtor_name` (`debtor_name`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `debts`
--

INSERT INTO `debts` (`debt_id`, `debtor_name`, `total_amount`, `interest_rate`, `start_date`, `created_at`, `updated_at`) VALUES
(5, 'A', 7000.00, 15.00, '2025-03-30', '2025-04-30 09:44:59', '2025-04-30 09:52:25'),
(6, 'B', 1000.00, 15.00, '2025-03-30', '2025-04-30 09:46:00', '2025-04-30 09:52:30');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
CREATE TABLE IF NOT EXISTS `payments` (
  `payment_id` int(11) NOT NULL AUTO_INCREMENT,
  `debt_id` int(11) NOT NULL,
  `payment_amount` decimal(10,2) NOT NULL,
  `principal_amount` decimal(10,2) DEFAULT NULL,
  `interest_amount` decimal(10,2) DEFAULT NULL,
  `payment_date` date NOT NULL,
  `payment_type` enum('principal','interest','both') NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`payment_id`),
  KEY `idx_debt_id` (`debt_id`),
  KEY `idx_payment_date` (`payment_date`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
