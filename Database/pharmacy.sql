-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 27, 2024 at 07:34 PM
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
(13, 'HOME COOKED', 'rokib2064@gmail.com', '01635485720', '2024-05-27 16:25:11');

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
(1, 'Tahira', 176548526, 'chaity@gmail.com', '$2y$10$8d4sVVLZb.zyG1g12YASE.4GeFjcZOK5wZdpBlDtkpYjCyQDUnC22', 1, '2024-03-29 17:16:26');

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
(1, 1, 13, 18.00, 3.00, 17.46, 5, '2024-05-27 16:26:06');

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
(187, 1, 15, 6, 18.00);

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
(14, 1, 'test1', 1, 1, '2024-04-19 18:53:31'),
(15, 7, 'Napa', 3, 7, '2024-05-27 15:23:05'),
(16, 7, 'Sergel 20Mg', 3, 8, '2024-05-27 15:23:12');

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
(3, 'SMC', '2024-05-27 15:20:01');

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
(6, 'Syrup', '2024-05-27 15:04:50'),
(7, 'Tablet', '2024-05-27 15:04:54'),
(8, 'Capsule', '2024-05-27 15:05:02');

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
(7, 'Paracetamol', '2024-05-27 15:20:15'),
(8, 'Esoprazol', '2024-05-27 15:20:18');

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
(38, 15, 3, 9994, '2024-05-30', 2, 3, '2024-05-27 16:24:48', 1),
(39, 16, 3, 45, '2024-05-31', 5, 7, '2024-05-27 17:15:05', 1);

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
(80, 15, 10000, 2, 3, '2024-05-27', 1),
(81, 16, 45, 5, 7, '2024-05-27', 1);

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
(3, 'Shaifuddin Ahammed Rokib', '01635485720', 'shaifuddin70@gmail.com', '2024-05-27 22:23:40');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customer_id`);

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
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT COMMENT 'ID', AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `invoice_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=123;

--
-- AUTO_INCREMENT for table `invoice_items`
--
ALTER TABLE `invoice_items`
  MODIFY `invoice_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=188;

--
-- AUTO_INCREMENT for table `medicine`
--
ALTER TABLE `medicine`
  MODIFY `medicine_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `medicine_brand`
--
ALTER TABLE `medicine_brand`
  MODIFY `brand_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `medicine_catagory`
--
ALTER TABLE `medicine_catagory`
  MODIFY `catagory_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `medicine_generic`
--
ALTER TABLE `medicine_generic`
  MODIFY `generic_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `medicine_stock`
--
ALTER TABLE `medicine_stock`
  MODIFY `stock_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `purchase_table`
--
ALTER TABLE `purchase_table`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

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
  MODIFY `supplier_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
