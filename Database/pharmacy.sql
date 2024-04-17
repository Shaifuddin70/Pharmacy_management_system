-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 17, 2024 at 01:44 PM
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
-- Database: `pharmacy`
--

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customer_id` int(11) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `customer_email` varchar(100) NOT NULL,
  `customer_number` varchar(100) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`customer_id`, `customer_name`, `customer_email`, `customer_number`, `date`) VALUES
(1, 'Rokib', 'rokib@gmail', '01635485721', '2024-03-29 17:18:30'),
(2, 'scot', 'wada', 'adw', '2024-03-29 17:30:28'),
(6, 'Shaifuddin Ahammed Rokib', 'shaifuddin70@gmail.com', '+8801635485720', '2024-04-09 20:04:10'),
(10, 'Shaifuddin Ahammed', 'shaifuddin70@gmail.com', '01635485720', '2024-04-09 20:05:34');

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`id`, `name`) VALUES
(3, 'Administation'),
(5, 'RND'),
(6, 'Web Development'),
(7, 'Software Development'),
(8, 'Research'),
(12, 'Stuff'),
(13, 'Store Executive');

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `id` int(100) NOT NULL COMMENT 'ID',
  `name` varchar(100) NOT NULL COMMENT 'Name',
  `number` int(100) NOT NULL COMMENT 'Number',
  `email` varchar(100) NOT NULL COMMENT 'Email',
  `password` varchar(100) NOT NULL,
  `role` int(10) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`id`, `name`, `number`, `email`, `password`, `role`, `date`) VALUES
(1, 'Shaifuddin', 1635485720, 'shaifuddin70@gmail.com', '$2y$10$m2MtgD9GRtGSrIKTNslkCuiu8AFvqTO8AnPwcaJnmz0/oVhSeB4ju', 1, '2024-03-29 17:16:26'),
(32, 'Scott T.', 2147483647, 'rohim@gmail.com', '$2y$10$bxVgaFOWYZp02gL0TspeauLunbHwlRTvHySniJFPW3/LqeCIdIzjK', 2, '2024-03-29 17:16:26'),
(34, 'Karim', 13654855, 'karim@gmail.com', '$2y$10$SzwaUDkCpdYybzuIQrg6/.3wrFG/SltqMBtZPMEbz3mlFuFklSDB2', 2, '2024-03-29 17:16:26'),
(35, 'Scott T.', 2147483647, 'dardentimothy3@gmail.com', '$2y$10$8T0/7/SB8pxkpSLjqNNvvOjdzB/KgTKmGNfc2H.cBAGSv2ekd9Rvq', 2, '2024-03-29 17:16:26'),
(36, 'HOME COOKED', 2147483647, 'awdawd@gmail.com', '$2y$10$NC9tPOUDQhKEztoqW/rmyudBxi4oAD1o2bqigvBaseObv0kKeKWRW', 2, '2024-03-30 11:42:51'),
(37, 'Seller', 165846946, 'sell@gmail.com', '$2y$10$68b2FlNX9NOaNlOKeajJpek5An4vlCDngl43dwu2kb663dZLVCXSe', 2, '2024-04-17 08:12:49');

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `invoice_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) DEFAULT 0.00,
  `subtotal` decimal(10,2) NOT NULL,
  `profit` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`invoice_id`, `employee_id`, `customer_id`, `total`, `discount`, `subtotal`, `profit`, `created_at`) VALUES
(94, 1, 1, 1710.00, 5.00, 1624.50, 115, '2024-04-17 11:07:19'),
(95, 1, 6, 2250.00, 7.00, 2092.50, 93, '2024-04-17 11:07:43');

-- --------------------------------------------------------

--
-- Table structure for table `invoice_items`
--

CREATE TABLE `invoice_items` (
  `invoice_item_id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `medicine_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoice_items`
--

INSERT INTO `invoice_items` (`invoice_item_id`, `invoice_id`, `medicine_id`, `quantity`, `total_price`) VALUES
(143, 94, 4, 10, 550.00),
(144, 94, 5, 10, 550.00),
(145, 94, 8, 1, 610.00),
(146, 95, 4, 10, 550.00),
(147, 95, 5, 10, 550.00),
(148, 95, 10, 10, 600.00),
(149, 95, 5, 10, 550.00);

-- --------------------------------------------------------

--
-- Table structure for table `medicine`
--

CREATE TABLE `medicine` (
  `medicine_id` int(11) NOT NULL,
  `catagory_id` int(11) NOT NULL,
  `medicine_name` varchar(100) NOT NULL,
  `brand_id` int(100) NOT NULL,
  `generic_id` int(100) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medicine`
--

INSERT INTO `medicine` (`medicine_id`, `catagory_id`, `medicine_name`, `brand_id`, `generic_id`, `date`) VALUES
(4, 1, 'Napa', 1, 1, '2024-03-25 09:04:49'),
(5, 1, 'Sergel', 1, 2, '2024-03-25 09:21:18'),
(8, 4, 'Adreline', 2, 2, '2024-03-29 16:17:26'),
(9, 2, 'Ace Plus', 1, 6, '2024-03-29 16:18:33'),
(10, 5, 'MG+', 2, 2, '2024-03-30 12:57:22'),
(12, 4, 'Tasty', 2, 2, '2024-04-07 22:33:39');

-- --------------------------------------------------------

--
-- Table structure for table `medicine_brand`
--

CREATE TABLE `medicine_brand` (
  `brand_id` int(11) NOT NULL,
  `brand_name` varchar(100) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medicine_brand`
--

INSERT INTO `medicine_brand` (`brand_id`, `brand_name`, `date`) VALUES
(1, 'Incepta', '2024-03-25 08:11:22'),
(2, 'Abdolax', '2024-03-25 08:12:17');

-- --------------------------------------------------------

--
-- Table structure for table `medicine_catagory`
--

CREATE TABLE `medicine_catagory` (
  `catagory_id` int(11) NOT NULL,
  `catagory_name` varchar(100) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medicine_catagory`
--

INSERT INTO `medicine_catagory` (`catagory_id`, `catagory_name`, `date`) VALUES
(1, 'Tablet', '2024-03-27 09:44:37'),
(2, 'Capsule', '2024-03-27 09:45:11'),
(4, 'Injection', '2024-03-27 09:45:26'),
(5, 'Syrup', '2024-03-29 16:50:25');

-- --------------------------------------------------------

--
-- Table structure for table `medicine_generic`
--

CREATE TABLE `medicine_generic` (
  `generic_id` int(11) NOT NULL,
  `generic_name` varchar(100) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medicine_generic`
--

INSERT INTO `medicine_generic` (`generic_id`, `generic_name`, `date`) VALUES
(1, 'Paracetamol', '2024-03-25 07:39:00'),
(2, 'esomeprazol', '2024-03-25 07:39:20'),
(6, 'Thiamine Mononitrate', '2024-03-25 08:00:36');

-- --------------------------------------------------------

--
-- Table structure for table `medicine_stock`
--

CREATE TABLE `medicine_stock` (
  `stock_id` int(11) NOT NULL,
  `medicine_id` int(10) NOT NULL,
  `unit` int(10) NOT NULL,
  `expiry_date` date NOT NULL,
  `pprice` int(11) NOT NULL,
  `sprice` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `shelf_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medicine_stock`
--

INSERT INTO `medicine_stock` (`stock_id`, `medicine_id`, `unit`, `expiry_date`, `pprice`, `sprice`, `date`, `shelf_id`) VALUES
(1, 4, 4956, '2024-05-11', 50, 55, '2024-03-25 13:15:32', 6),
(25, 10, 100, '2025-09-15', 50, 60, '2024-04-07 21:53:46', 6),
(31, 12, 500, '2024-04-08', 50, 100, '2024-04-07 22:34:34', 6),
(33, 9, 992, '2024-05-11', 50, 55, '2024-04-07 23:07:57', 6),
(34, 5, 93, '2024-04-08', 50, 55, '2024-04-07 23:24:49', 6),
(35, 8, 24, '2025-10-16', 510, 610, '2024-04-15 20:35:36', 8);

-- --------------------------------------------------------

--
-- Table structure for table `purchase_table`
--

CREATE TABLE `purchase_table` (
  `id` int(100) NOT NULL,
  `medicine_id` int(100) NOT NULL,
  `unit` int(11) NOT NULL,
  `pprice` int(11) NOT NULL,
  `sprice` int(11) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp(),
  `status` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `purchase_table`
--

INSERT INTO `purchase_table` (`id`, `medicine_id`, `unit`, `pprice`, `sprice`, `date`, `status`) VALUES
(55, 5, 100, 0, 0, '2024-04-08', 1),
(56, 9, 100, 0, 0, '2024-04-08', 1),
(57, 8, 50, 0, 0, '2024-04-08', 1),
(58, 9, 100, 40, 50, '2024-04-08', 1),
(59, 10, 10, 35, 50, '2024-04-08', 1),
(60, 9, 100, 40, 10, '2024-04-08', 1),
(61, 4, 10, 5, 6, '2024-04-08', 1),
(62, 4, 10, 10, 15, '2024-04-08', 1),
(63, 4, 50, 10, 15, '2024-04-08', 1),
(64, 4, 5000, 50, 55, '2024-04-08', 1),
(65, 12, 500, 50, 100, '2024-04-08', 1),
(66, 11, 10, 50, 60, '2024-04-08', 1),
(67, 9, 50, 50, 55, '2024-04-08', 1),
(68, 10, 50, 50, 55, '2024-04-08', 1),
(69, 5, 100, 50, 55, '2024-04-08', 1),
(70, 10, 50, 50, 60, '2024-04-13', 1),
(71, 8, 100, 510, 610, '2024-04-16', 1);

-- --------------------------------------------------------

--
-- Table structure for table `return_table`
--

CREATE TABLE `return_table` (
  `id` int(10) NOT NULL,
  `employee_id` int(100) NOT NULL,
  `item_id` int(10) NOT NULL,
  `quantity` int(10) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `return_table`
--

INSERT INTO `return_table` (`id`, `employee_id`, `item_id`, `quantity`, `date`) VALUES
(19, 32, 18, 2, '2022-12-23'),
(20, 32, 21, 2, '2022-12-23'),
(21, 32, 24, 2, '2022-12-23'),
(23, 32, 18, 2, '2022-12-23'),
(24, 1, 24, 50, '2022-12-23'),
(25, 1, 18, 1, '2022-12-23'),
(26, 1, 18, 1, '2022-12-23'),
(27, 1, 18, 1, '2022-12-23'),
(28, 1, 18, 1, '2022-12-23'),
(29, 1, 18, 1, '2022-12-23'),
(30, 1, 18, 1, '2022-12-24'),
(31, 1, 18, 65, '2022-12-24'),
(34, 32, 18, 66, '2022-12-24');

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `role_id` int(100) NOT NULL,
  `role_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`role_id`, `role_name`) VALUES
(1, 'Admin'),
(2, 'Employee');

-- --------------------------------------------------------

--
-- Table structure for table `shelf`
--

CREATE TABLE `shelf` (
  `shelf_id` int(100) NOT NULL,
  `shelf_number` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shelf`
--

INSERT INTO `shelf` (`shelf_id`, `shelf_number`) VALUES
(1, 101),
(6, 102),
(7, 103),
(8, 104);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`invoice_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD PRIMARY KEY (`invoice_item_id`),
  ADD KEY `invoice_id` (`invoice_id`),
  ADD KEY `medicine_id` (`medicine_id`);

--
-- Indexes for table `medicine`
--
ALTER TABLE `medicine`
  ADD PRIMARY KEY (`medicine_id`);

--
-- Indexes for table `medicine_brand`
--
ALTER TABLE `medicine_brand`
  ADD PRIMARY KEY (`brand_id`);

--
-- Indexes for table `medicine_catagory`
--
ALTER TABLE `medicine_catagory`
  ADD PRIMARY KEY (`catagory_id`);

--
-- Indexes for table `medicine_generic`
--
ALTER TABLE `medicine_generic`
  ADD PRIMARY KEY (`generic_id`);

--
-- Indexes for table `medicine_stock`
--
ALTER TABLE `medicine_stock`
  ADD PRIMARY KEY (`stock_id`),
  ADD KEY `catagory_id` (`medicine_id`),
  ADD KEY `order_id` (`date`),
  ADD KEY `catagory_id_2` (`medicine_id`),
  ADD KEY `rack_id` (`shelf_id`);

--
-- Indexes for table `purchase_table`
--
ALTER TABLE `purchase_table`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `return_table`
--
ALTER TABLE `return_table`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`item_id`),
  ADD KEY `item_id` (`item_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `shelf`
--
ALTER TABLE `shelf`
  ADD PRIMARY KEY (`shelf_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `invoice_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT for table `invoice_items`
--
ALTER TABLE `invoice_items`
  MODIFY `invoice_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=150;

--
-- AUTO_INCREMENT for table `medicine`
--
ALTER TABLE `medicine`
  MODIFY `medicine_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `medicine_brand`
--
ALTER TABLE `medicine_brand`
  MODIFY `brand_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `medicine_catagory`
--
ALTER TABLE `medicine_catagory`
  MODIFY `catagory_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `medicine_generic`
--
ALTER TABLE `medicine_generic`
  MODIFY `generic_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `medicine_stock`
--
ALTER TABLE `medicine_stock`
  MODIFY `stock_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `purchase_table`
--
ALTER TABLE `purchase_table`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `return_table`
--
ALTER TABLE `return_table`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `role_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `shelf`
--
ALTER TABLE `shelf`
  MODIFY `shelf_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`);

--
-- Constraints for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD CONSTRAINT `invoice_items_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`invoice_id`),
  ADD CONSTRAINT `invoice_items_ibfk_2` FOREIGN KEY (`medicine_id`) REFERENCES `medicine` (`medicine_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
