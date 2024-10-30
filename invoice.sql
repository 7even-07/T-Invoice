-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 30, 2024 at 07:12 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `invoice`
--

-- --------------------------------------------------------

--
-- Table structure for table `product_hsn_mapping`
--

CREATE TABLE `product_hsn_mapping` (
  `id` int(11) NOT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `hsn_code` varchar(10) DEFAULT NULL,
  `gst_rate` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_hsn_mapping`
--

INSERT INTO `product_hsn_mapping` (`id`, `product_name`, `hsn_code`, `gst_rate`) VALUES
(1, 'Live Animals', '01', 0.00),
(2, 'LIVE HORSES, ASSES, MULES AND HINNIES - Horses:', '0101', 0.00),
(3, 'Fresh or chilled lamb carcases and half-carcases	', '020410', 5.00),
(4, 'natural honey', '0409', 5.00),
(5, 'mehendi paste in cones', '1404', 5.00),
(6, 'ultra high temperature (uht) milk', '0401', 5.00),
(7, 'milk  and  cream', '0402', 5.00),
(8, 'skimmed   milk powder', '0402', 5.00),
(9, 'milk food for babie', '0402', 5.00),
(10, 'yoghurt', '0403', 5.00),
(11, 'cream', '0403', 5.00),
(12, 'acidified milk', '0403', 5.00),
(13, 'curd', '0403', 5.00),
(14, 'lassi', '0403', 5.00),
(15, 'butter milk', '0403', 5.00),
(16, 'whey', '0404', 5.00),
(17, 'chena', '0406', 5.00),
(18, 'paneer', '0406', 5.00),
(19, 'eggs', '0408', 5.00),
(20, 'insects  ', '0410', 5.00),
(21, 'pig\'s hair', '0502', 5.00),
(22, 'bird\'s feathers', '0505', 5.00),
(23, 'ivory', '0507', 5.00),
(24, 'tortoise-shell', '0507', 5.00),
(25, 'whalebone   ', '0507', 5.00),
(26, 'whalebone   hair', '0507', 5.00),
(27, 'horns', '0507', 5.00),
(28, 'coral ', '0508', 5.00),
(29, 'shells of molluscs', '0508', 5.00),
(30, 'cuttle-bone', '0508', 5.00),
(31, 'ambergris', '0510', 5.00),
(32, 'castoreum', '0510', 5.00),
(33, 'civet  ', '0510', 5.00),
(34, 'musk', '0510', 5.00),
(35, 'cantharides', '0510', 5.00),
(36, 'bile', '0510', 5.00),
(37, 'glands  ', '0510', 5.00),
(38, 'herb', '7', 5.00),
(39, 'bark', '7', 5.00),
(40, 'dry plant', '7', 5.00),
(41, 'dry root', '7', 5.00),
(42, 'jaribooti ', '7', 5.00),
(43, 'dry flowers', '7', 5.00),
(44, 'manioc', '0714', 5.00),
(45, 'arrowroot', '0714', 5.00),
(46, 'salep', '0714', 5.00),
(47, 'jerusalem  artichokes', '0714', 5.00),
(48, 'sweet potatoes', '0714', 5.00),
(49, 'cashew nuts', '0801', 5.00),
(50, 'desiccated coconut', '0801', 5.00),
(51, 'dried areca nuts', '0802', 5.00),
(52, 'dried chestnuts', '0802', 5.00),
(53, 'walnuts', '0802', 5.00),
(54, 'dried makhana', '0802', 5.00),
(55, 'mangoes', '0804', 5.00),
(56, 'grapes', '0806', 5.00),
(57, 'raisins', '0806', 5.00),
(58, 'fruit and nuts', '0811', 5.00),
(59, 'peel  of  citrus  fruit', '0814', 5.00),
(60, 'peel of watermelons', '0814', 5.00),
(61, 'coffee  roasted', '0901', 5.00),
(62, 'coffee husks', '0901', 5.00),
(63, 'tea', '0902', 5.00),
(64, 'maté', '0903', 5.00),
(65, 'piper', '0904', 5.00),
(66, 'pepper of the genus piper', '0904', 5.00),
(67, 'vanilla', '0905', 5.00),
(68, 'cinnamon', '0906', 5.00),
(69, 'cloves', '0907', 5.00),
(70, 'nutmeg', '0908', 5.00),
(71, 'mace ', '0908', 5.00),
(72, 'cardamoms', '0908', 5.00),
(73, 'seeds of anise', '0909', 5.00),
(74, 'seeds of badian', '0909', 5.00),
(75, 'seeds of fennel', '0909', 5.00),
(76, 'seeds of coriander', '0909', 5.00),
(77, 'seeds of cummin', '0909', 5.00),
(78, 'ginger', '0910', 5.00),
(79, 'turmeric ', '0910', 5.00),
(80, 'thyme', '0910', 5.00),
(81, 'bay leaves', '0910', 5.00),
(82, 'curry', '0910', 5.00),
(83, 'wheat ', '1001', 5.00),
(84, 'oats', '1004', 5.00),
(85, 'corn', '1005', 5.00),
(86, 'rice', '1006', 5.00),
(87, 'grain sorghum', '1007', 5.00),
(88, 'buckwheat', '1008', 5.00),
(89, 'millet  seed', '1008', 5.00),
(90, 'canary seed', '1008', 5.00),
(91, 'jawar', '1008', 5.00),
(92, 'bajra', '1008', 5.00);

-- --------------------------------------------------------

--
-- Table structure for table `tax_invoice`
--

CREATE TABLE `tax_invoice` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `company_name` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `product_name` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `gst_rate` decimal(5,2) DEFAULT NULL,
  `cgst` decimal(5,2) DEFAULT NULL,
  `sgst` decimal(5,2) DEFAULT NULL,
  `igst` decimal(5,2) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `hsn_code` varchar(100) DEFAULT NULL,
  `gstin` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tax_invoice`
--

INSERT INTO `tax_invoice` (`id`, `user_id`, `name`, `company_name`, `address`, `state`, `product_name`, `price`, `quantity`, `gst_rate`, `cgst`, `sgst`, `igst`, `total`, `date`, `hsn_code`, `gstin`) VALUES
(5, 1, 'Shreenivas ', 'xyz limited', 'unr', NULL, 'natural honey', 2000.00, 5, NULL, 0.00, 0.00, 0.00, 10000.00, '2024-10-26', '0409', NULL),
(6, 1, 'Shreenivas ', 'xyz limited', 'unr', 'maharashtra', 'furniture', 500.00, 5, NULL, 5.00, 5.00, 0.00, 2750.00, NULL, NULL, NULL),
(7, 1, 'json', 'whatever limited', 'somewhere in earth', 'maharashtra', 'towel', 200.00, 500, NULL, 5.00, 5.00, 0.00, 110000.00, '2024-10-18', NULL, NULL),
(8, 1, 'shreenivas', 'locker base', 'Ulhasnagar, India', 'Maharashtra', 'furniture', 15000.00, 5, NULL, 5.00, 5.00, 0.00, 82500.00, '2024-10-04', NULL, NULL),
(9, 1, 'sdfds', 'boom', 'solapur', 'Maharashtra', 'chilli flakes', 2.00, 5000, NULL, 5.00, 5.00, 0.00, 11000.00, '2024-10-25', NULL, NULL),
(10, 2, 'hahah', 'locker base', 'ojdjs', 'maharashtra', 'furniture', 50.00, 60, NULL, 5.00, 5.00, 0.00, 3300.00, '2024-10-09', NULL, NULL),
(11, 1, 'three', 'one piece', 'somewhere', 'mystery', 'NATURAL HONEY', 5000.00, 50, NULL, 0.00, 0.00, 0.00, 262500.00, '2024-10-03', '0409', NULL),
(12, 1, 'first', 'whatever limited', '€asd', 'Maharashtra', 'Fresh or chilled lamb carcases and half-carcases	', 50.00, 50, NULL, 0.00, 0.00, 0.00, 2625.00, '2024-10-17', '020410', NULL),
(13, 1, 'testdb', 'locker base', 'zsd', 'Maharashtra', 'Fresh or chilled lamb carcases and half-carcases	', 5.00, 5, NULL, 0.00, 0.00, 0.00, 26.25, '2024-10-26', '020410', NULL),
(14, 1, 'sdfds', 'boom', 'cfghf', 'Maharashtra', 'NATURAL HONEY', 50.00, 5, NULL, 2.50, 2.50, 0.00, 262.50, '2024-10-19', '0409', NULL),
(15, 1, 'kedar ', 'whatever limited', 'abc pune', 'maharashtra', 'NATURAL HONEY', 1500.00, 12, NULL, 2.50, 2.50, 0.00, 18900.00, '2024-12-05', '0409', NULL),
(16, 1, 'hahah', 'locker base', 'dsfg', 'maharashtra', 'natural honey', 500.00, 5, NULL, 2.50, 2.50, 0.00, 2625.00, '2024-10-19', '0409', NULL),
(17, 1, 'Shreenivas ', 'whatever limited', 'thane', 'Maharashtra', 'NATURAL HONEY', 500.00, 20, NULL, 2.50, 2.50, 0.00, 10500.00, '2024-10-15', '0409', NULL),
(18, 1, 'something', 'whatever limited', 'ulhasnagar', NULL, 'bajra', 20.00, 5, NULL, 0.00, 0.00, 0.00, 100.00, '2024-10-18', '1008', 'asdasdasd22'),
(19, 1, 'something', 'whatever limited', 'awdas', 'maharashtra', 'bajra', 5.00, 5, NULL, 2.50, 2.50, 0.00, 26.25, '2024-10-04', '1008', '22Asdsfsdfsdfsd'),
(20, 1, 'shloak', 'ollama', 'mumbai', 'maharashtra', 'jawar', 5.00, 50, NULL, 2.50, 2.50, 0.00, 262.50, '2006-06-25', '1008', 'asdasdasd22'),
(21, 1, 'shloak', 'ollama', 'mumbai', 'maharashtra', 'natural honey', 2.00, 5, NULL, 2.50, 2.50, 0.00, 10.50, '2006-06-25', '0409', 'asdasdasd22');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`) VALUES
(1, 'shreenu', 'shreenu@gmail.com', '$2y$10$iDsjHM58yLEYVZEG6Cvu5eJ6/awMhYAGgSg4rl/GMX2zOeTwJxody'),
(2, 'seven', 'seven@gmail.com', '$2y$10$JU1CO8bo3FRTI3dwxkbUN.bDUNr.r2OirVL5ghsnbXNDsSjNlZg6.');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `product_hsn_mapping`
--
ALTER TABLE `product_hsn_mapping`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_name` (`product_name`);

--
-- Indexes for table `tax_invoice`
--
ALTER TABLE `tax_invoice`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `product_hsn_mapping`
--
ALTER TABLE `product_hsn_mapping`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT for table `tax_invoice`
--
ALTER TABLE `tax_invoice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tax_invoice`
--
ALTER TABLE `tax_invoice`
  ADD CONSTRAINT `tax_invoice_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
