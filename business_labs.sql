-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 05, 2023 at 07:06 PM
-- Server version: 8.0.31
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `business_labs`
--

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `vendor_id` int NOT NULL,
  `Is_published` tinyint(1) NOT NULL,
  `commission_percent` int NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ammount` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `vendor_id`, `Is_published`, `commission_percent`, `title`, `ammount`) VALUES
(1, 1, 1, 10, 'product 1', 10),
(2, 1, 1, 15, 'product two', 20),
(3, 2, 1, 5, 'product 3', 30),
(4, 2, 1, 30, 'product 4', 40);

-- --------------------------------------------------------

--
-- Table structure for table `return_history`
--

DROP TABLE IF EXISTS `return_history`;
CREATE TABLE IF NOT EXISTS `return_history` (
  `id` int NOT NULL AUTO_INCREMENT,
  `sale_history_id` int NOT NULL,
  `refund_ammount` int NOT NULL,
  `refund_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sale_history`
--

DROP TABLE IF EXISTS `sale_history`;
CREATE TABLE IF NOT EXISTS `sale_history` (
  `id` int NOT NULL AUTO_INCREMENT,
  `product_id` int NOT NULL,
  `qty` int NOT NULL,
  `orignal_amount` float NOT NULL,
  `total_amount` float NOT NULL,
  `commison_ammount` float NOT NULL,
  `commision_percentage` float NOT NULL,
  `date_of_sale` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `vendor_id` int NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `order_status` enum('Pending','Processing','Shipped','Delivered','Cancelled','On Hold','Refunded','Returned') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'Pending',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

DROP TABLE IF EXISTS `vendors`;
CREATE TABLE IF NOT EXISTS `vendors` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `email` varchar(191) NOT NULL,
  `shop_name` varchar(191) NOT NULL,
  `current_year_sale` int NOT NULL DEFAULT '0',
  `commision_discount` int NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vendors`
--

INSERT INTO `vendors` (`id`, `name`, `email`, `shop_name`, `current_year_sale`, `commision_discount`, `status`) VALUES
(1, 'vendor 1', 'vendor1@gmail.com', 'vender one', 0, 0, 1),
(2, 'vendor 2', 'vendor2@gmail.com', 'vender two', 0, 0, 1);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
