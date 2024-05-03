-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 03, 2024 at 05:48 PM
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
(1, 'chaity', 'chaity@gmail.com', '01635485721', '2024-03-29 17:18:30'),
(2, 'scot', 'scot@gmail.com', '1326548654', '2024-03-29 17:30:28'),
(11, 'Shaifuddin Ahammed', 'majinshifu@gmail.com', '01538347152', '2024-05-03 15:27:01');

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
(1, 'Tahira', 176548526, 'chaity@gmail.com', '$2y$10$8d4sVVLZb.zyG1g12YASE.4GeFjcZOK5wZdpBlDtkpYjCyQDUnC22', 1, '2024-03-29 17:16:26'),
(32, 'Rohim', 2147483647, 'rohim@gmail.com', '$2y$10$OmDy9R0yxrJRQ/ZXyYWSo.z.jjDlKCCJNLbT.SfE5LCn0isrmyAtK', 2, '2024-03-29 17:16:26'),
(34, 'Karim', 165842563, 'karim@gmail.com', '$2y$10$CRDucZaJ4MbUnv0c1ZCqA.JNI2aBMI/v1FsxZqnrBbQH9ufe1UjXe', 2, '2024-03-29 17:16:26'),
(35, 'Salim', 168569542, 'salim@gmail.com', '$2y$10$uoTQM7kssEOjkZcjmkh.4eksOxyML9U0QMWaqLuK1k2HhTjNcMH02', 2, '2024-03-29 17:16:26');

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
(100, 1, 2, 4950.00, 5.00, 4702.50, 203, '2024-04-17 12:42:33'),
(101, 1, 1, 4135.00, 5.00, 3928.25, 419, '2024-04-17 13:11:51'),
(103, 1, 1, 825.00, 5.00, 783.75, 34, '2024-04-17 15:47:38'),
(104, 1, 1, 5400.00, 5.00, 5130.00, 630, '2024-04-17 15:49:18'),
(105, 1, 2, 1925.00, 5.00, 1828.75, -171, '2024-04-19 18:59:01'),
(106, 1, 1, 275.00, 5.00, 261.25, 11, '2024-05-01 06:51:09'),
(107, 1, 1, 300.00, 5.00, 285.00, 35, '2024-05-01 06:51:35'),
(108, 1, 1, 275.00, 5.00, 261.25, 11, '2024-05-01 06:53:59'),
(110, 1, 1, 930.00, 5.00, 883.50, 84, '2024-05-03 14:24:43'),
(111, 1, 2, 550.00, 5.00, 522.50, 23, '2024-05-03 14:27:53'),
(112, 1, 1, 300.00, 5.00, 285.00, 35, '2024-05-03 14:29:05'),
(114, 1, 1, 275.00, 5.00, 261.25, 11, '2024-05-03 14:34:47'),
(115, 34, 11, 1800.00, 5.00, 1710.00, 210, '2024-05-03 15:27:20');

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
(154, 100, 4, 90, 4950.00),
(155, 101, 4, 5, 275.00),
(156, 101, 5, 40, 2150.00),
(157, 101, 8, 1, 610.00),
(158, 101, 10, 10, 600.00),
(159, 101, 12, 5, 500.00),
(161, 103, 4, 10, 550.00),
(163, 103, 5, 5, 275.00),
(164, 104, 10, 90, 5400.00),
(165, 105, 5, 40, 1925.00),
(166, 106, 4, 5, 275.00),
(167, 107, 5, 5, 300.00),
(168, 108, 4, 5, 275.00),
(170, 110, 4, 10, 550.00),
(171, 110, 8, 5, 0.00),
(172, 110, 9, 1, 55.00),
(173, 110, 10, 5, 325.00),
(174, 111, 4, 10, 550.00),
(175, 112, 5, 5, 300.00),
(176, 114, 4, 5, 275.00),
(177, 115, 4, 10, 550.00),
(178, 115, 5, 10, 600.00),
(179, 115, 10, 10, 650.00);

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
(12, 4, 'Tasty', 2, 2, '2024-04-07 22:33:39'),
(13, 2, 'test', 1, 2, '2024-04-17 15:46:33'),
(14, 1, 'test1', 1, 1, '2024-04-19 18:53:31');

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
  `supplier_id` int(11) NOT NULL,
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

INSERT INTO `medicine_stock` (`stock_id`, `medicine_id`, `supplier_id`, `unit`, `expiry_date`, `pprice`, `sprice`, `date`, `shelf_id`) VALUES
(1, 4, 1, 4828, '2024-05-11', 50, 55, '2024-03-25 13:15:32', 6),
(33, 9, 1, 1047, '2024-05-03', 50, 55, '2024-04-07 23:07:57', 1),
(34, 5, 1, 140, '2024-05-11', 50, 60, '2024-04-07 23:24:49', 6),
(36, 10, 2, 85, '2026-11-03', 50, 65, '2024-05-02 10:25:35', 6);

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
(71, 8, 100, 510, 610, '2024-04-16', 1),
(72, 8, 100, 510, 620, '2024-04-17', 1),
(73, 10, 100, 500, 650, '2024-04-17', 1),
(74, 5, 100, 50, 60, '2024-04-20', 1),
(75, 10, 100, 50, 65, '2024-05-02', 1),
(76, 9, 56, 50, 55, '2024-05-02', 1);

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

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `supplier_id` int(11) NOT NULL,
  `supplier_name` varchar(100) NOT NULL,
  `supplier_number` varchar(20) NOT NULL,
  `supplier_email` varchar(100) NOT NULL,
  `creation_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`supplier_id`, `supplier_name`, `supplier_number`, `supplier_email`, `creation_date`) VALUES
(1, 'HOME COOKED', '01538347152', 'rokib2064@gmail.com', '2024-05-02 15:17:45'),
(2, 'Shaifuddin Ahammed Rokib', '01635485720', 'shaifuddin70@gmail.com', '2024-05-02 16:04:12');

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
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`supplier_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

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
  MODIFY `invoice_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=116;

--
-- AUTO_INCREMENT for table `invoice_items`
--
ALTER TABLE `invoice_items`
  MODIFY `invoice_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=180;

--
-- AUTO_INCREMENT for table `medicine`
--
ALTER TABLE `medicine`
  MODIFY `medicine_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

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
  MODIFY `stock_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `purchase_table`
--
ALTER TABLE `purchase_table`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

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
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `supplier_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
