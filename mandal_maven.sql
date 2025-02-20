-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 11, 2025 at 02:21 PM
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
-- Database: `mandal_maven`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_form`
--

CREATE TABLE `admin_form` (
  `id` int(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_form`
--

INSERT INTO `admin_form` (`id`, `username`, `password`, `email`) VALUES
(2, 'qweqw', '$2y$10$0nCAJ2aWPZ828zOqGMP7WOem.0XQfdCKulgCYwcwuy6zb0pq9.qKm', 'wqewq@gmail.com'),
(3, 'bishal', '$2y$10$59kpH.57t6OlKcp6xaquj.F9gL9rQPIBcxp4V3Ic5BwacAXYakKC2', 'tbishal0088@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `quantity` int(255) NOT NULL,
  `username` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `name`, `price`, `image`, `quantity`, `username`) VALUES
(151, 'check', '4123', 'download.jpeg', 1, 'bishal'),
(153, 'mandala', '654', 'jersey.jpg', 1, 'bishal');

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `street` varchar(255) NOT NULL,
  `landmark` varchar(255) NOT NULL,
  `total_product` varchar(255) NOT NULL,
  `total_price` int(255) NOT NULL,
  `status` enum('Pending','Delivered','Cancelled') NOT NULL,
  `username` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `expected_delivery_date` date DEFAULT NULL,
  `delivery_time` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`id`, `name`, `phone`, `email`, `city`, `street`, `landmark`, `total_product`, `total_price`, `status`, `username`, `created_at`, `expected_delivery_date`, `delivery_time`) VALUES
(46, 'bishal', '9876543210', 'tbishal0088@gmail.com', 'kathmandu', 'boudha', 'complex', 'mandala (Nrs. 123)', 123, 'Pending', 'bishal', '2025-01-18 17:39:54', '2025-01-21', '2025-01-18');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` int(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `image`, `description`) VALUES
(18, 'mandala', 123, 'download.jpeg', '213'),
(19, 'check', 4123, 'download.jpeg', 'Porro, ex! Veniam, ducimus nobis. A molestiae beatae autem voluptates, laborum, soluta ducimus repellat nihil temporibus vitae dolor porro officiis rerum ab similique! Quas consequuntur eaque dignissimos ad reiciendis quos.\r\nAd, omnis asperiores, '),
(20, 'mandala', 654, 'jersey.jpg', 'Porro, ex! Veniam, ducimus nobis. A molestiae beatae autem voluptates, laborum, soluta ducimus repellat nihil temporibus vitae dolor porro officiis rerum ab similique! Quas consequuntur eaque dignissimos ad reiciendis quos.\r\nAd, omnis asperiores, tempore '),
(21, 'mandala', 654, 'jersey.jpg', 'Porro, ex! Veniam, ducimus nobis. A molestiae beatae autem voluptates, laborum, soluta ducimus repellat nihil temporibus vitae dolor porro officiis rerum ab similique! Quas consequuntur eaque dignissimos ad reiciendis quos.\r\nAd, omnis asperiores, tempore '),
(22, 'mandala', 654, 'jersey.jpg', 'Porro, ex! Veniam, ducimus nobis. A molestiae beatae autem voluptates, laborum, soluta ducimus repellat nihil temporibus vitae dolor porro officiis rerum ab similique! Quas consequuntur eaque dignissimos ad reiciendis quos.\r\nAd, omnis asperiores, tempore '),
(23, 'mandala', 654, 'jersey.jpg', 'Porro, ex! Veniam, ducimus nobis. A molestiae beatae autem voluptates, laborum, soluta ducimus repellat nihil temporibus vitae dolor porro officiis rerum ab similique! Quas consequuntur eaque dignissimos ad reiciendis quos.\r\nAd, omnis asperiores, tempore '),
(24, 'mandala', 654, 'jersey.jpg', 'Porro, ex! Veniam, ducimus nobis. A molestiae beatae autem voluptates, laborum, soluta ducimus repellat nihil temporibus vitae dolor porro officiis rerum ab similique! Quas consequuntur eaque dignissimos ad reiciendis quos.\r\nAd, omnis asperiores, tempore '),
(25, 'mandala', 654, 'jersey.jpg', 'Porro, ex! Veniam, ducimus nobis. A molestiae beatae autem voluptates, laborum, soluta ducimus repellat nihil temporibus vitae dolor porro officiis rerum ab similique! Quas consequuntur eaque dignissimos ad reiciendis quos.\r\nAd, omnis asperiores, tempore '),
(26, 'mandala', 654, 'jersey.jpg', 'Porro, ex! Veniam, ducimus nobis. A molestiae beatae autem voluptates, laborum, soluta ducimus repellat nihil temporibus vitae dolor porro officiis rerum ab similique! Quas consequuntur eaque dignissimos ad reiciendis quos.\r\nAd, omnis asperiores, tempore '),
(27, 'mandala', 654, 'jersey.jpg', 'Porro, ex! Veniam, ducimus nobis. A molestiae beatae autem voluptates, laborum, soluta ducimus repellat nihil temporibus vitae dolor porro officiis rerum ab similique! Quas consequuntur eaque dignissimos ad reiciendis quos.\r\nAd, omnis asperiores, tempore '),
(28, 'mandala', 654, 'jersey.jpg', 'Porro, ex! Veniam, ducimus nobis. A molestiae beatae autem voluptates, laborum, soluta ducimus repellat nihil temporibus vitae dolor porro officiis rerum ab similique! Quas consequuntur eaque dignissimos ad reiciendis quos.\r\nAd, omnis asperiores, tempore '),
(29, 'mandala', 654, 'jersey.jpg', 'Porro, ex! Veniam, ducimus nobis. A molestiae beatae autem voluptates, laborum, soluta ducimus repellat nihil temporibus vitae dolor porro officiis rerum ab similique! Quas consequuntur eaque dignissimos ad reiciendis quos.\r\nAd, omnis asperiores, tempore '),
(30, 'mandala', 654, 'jersey.jpg', 'Porro, ex! Veniam, ducimus nobis. A molestiae beatae autem voluptates, laborum, soluta ducimus repellat nihil temporibus vitae dolor porro officiis rerum ab similique! Quas consequuntur eaque dignissimos ad reiciendis quos.\r\nAd, omnis asperiores, tempore ');

-- --------------------------------------------------------

--
-- Table structure for table `user_form`
--

CREATE TABLE `user_form` (
  `id` int(255) NOT NULL,
  `username` varchar(25) NOT NULL,
  `password` char(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `street` varchar(255) NOT NULL,
  `landmark` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_form`
--

INSERT INTO `user_form` (`id`, `username`, `password`, `phone`, `email`, `city`, `street`, `landmark`) VALUES
(15, 'bishal', '$2y$10$4xsQTx5PXJsh.kpgTX3i7eKON8LR5W73BreNxlvfmEuUynNmWYXDi', '9876543210', 'tbishal0088@gmail.com', 'kathmandu', 'boudha', 'complex');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_form`
--
ALTER TABLE `admin_form`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_form`
--
ALTER TABLE `user_form`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_form`
--
ALTER TABLE `admin_form`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=154;

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `user_form`
--
ALTER TABLE `user_form`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
