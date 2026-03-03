-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 27, 2026 at 05:10 AM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mobile-store`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` varchar(100) NOT NULL,
  `image` varchar(100) NOT NULL,
  `quantity` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `name`, `price`, `image`, `quantity`) VALUES
(7, 4, 'Nokia C210', '110', 'https://fdn2.gsmarena.com/vv/bigpic/nokia-c210.jpg', 6),
(12, 13, 'Xiaomi 13T', '422', 'https://fdn2.gsmarena.com/vv/bigpic/xiaomi-13t.jpg', 7),
(13, 13, 'Vivo x100', '660', 'https://fdn2.gsmarena.com/vv/bigpic/vivo-x100.jpg', 5),
(14, 13, 'Samsung Galaxy S23 FE', '400', 'https://fdn2.gsmarena.com/vv/bigpic/samsung-galaxy-s23-fe.jpg', 6),
(22, 11, 'vivov60', '250', 'https://m.media-amazon.com/images/I/71BiI-RQ+-L._SX679_.jpg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `number` varchar(12) NOT NULL,
  `email` varchar(100) NOT NULL,
  `method` varchar(50) NOT NULL,
  `address` varchar(500) NOT NULL,
  `total_products` varchar(1000) NOT NULL,
  `total_price` int(100) NOT NULL,
  `placed_on` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `name`, `number`, `email`, `method`, `address`, `total_products`, `total_price`, `placed_on`) VALUES
(1, 11, 'vivov60e', '1234567890', 'ar@gmail.com', 'cash on delivery', 'flat no. 45, gs road, city, mp, uk - 445566', ', Huawei P60 Pro (1) ', 830, '27-Feb-2026'),
(2, 11, 'boAt Rockerz Wireless Headphones', '123455667898', 'jenish12@gmail.com', 'upi', 'flat no. 45, gs road, city, mp, uk - 565656', ', iQ00001 (1) , vivov60 (1) ', 460, '27-Feb-2026'),
(3, 11, 'vive4k', '121344335', 'jenish@gmail.com', 'credit card', 'flat no. 45, muimdsjfbhudfv h, cvbcvnbfg    , bn tfghfgc, ghfhhrth yj - 2345456', ', vivov60 (1) ', 250, '27-Feb-2026');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` varchar(100) NOT NULL,
  `image` varchar(1000) NOT NULL,
  `category` varchar(100) NOT NULL DEFAULT 'mobile'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `image`, `category`) VALUES
(1, 'Xiaomi 13T', '422', 'https://fdn2.gsmarena.com/vv/bigpic/xiaomi-13t.jpg', 'mobile'),
(2, 'Vivo x100', '660', 'https://fdn2.gsmarena.com/vv/bigpic/vivo-x100.jpg', 'mobile'),
(3, 'Samsung Galaxy S23 FE', '400', 'https://fdn2.gsmarena.com/vv/bigpic/samsung-galaxy-s23-fe.jpg', 'mobile'),
(4, 'Apple iPhone 15 Pro Max', '1200', 'https://fdn2.gsmarena.com/vv/bigpic/apple-iphone-15-pro-max.jpg', 'mobile'),
(5, 'Google Pixel 8 Pro', '800', 'https://fdn2.gsmarena.com/vv/bigpic/google-pixel-8-pro.jpg', 'mobile'),
(6, 'Xiaomi Poco M6 Pro', '110', 'https://fdn2.gsmarena.com/vv/bigpic/xiaomi-poco-m6-pro.jpg', 'mobile'),
(7, 'Realme Narzo 60x', '130', 'https://fdn2.gsmarena.com/vv/bigpic/realme-narzo-60x-5g.jpg', 'mobile'),
(8, 'Realme 11 Pro', '205', 'https://fdn2.gsmarena.com/vv/bigpic/realme-11-pro.jpg', 'mobile'),
(9, 'vivo V29e', '320', 'https://fdn2.gsmarena.com/vv/bigpic/vivo-v29e-international.jpg', 'mobile'),
(10, 'Infinix Note 30 Pro', '300', 'https://fdn2.gsmarena.com/vv/bigpic/infinix-note-30-pro.jpg', 'mobile'),
(11, 'Infinix GT 10 Pro', '320', 'https://fdn2.gsmarena.com/vv/bigpic/infinix-gt10-pro-5g.jpg', 'mobile'),
(12, 'Motorola Edge 40', '362', 'https://fdn2.gsmarena.com/vv/bigpic/motorola-edge-40.jpg', 'mobile'),
(13, 'Motorola Razr 40 Ultra', '1025', 'https://fdn2.gsmarena.com/vv/bigpic/motorola-razr-40-ultra.jpg', 'mobile'),
(14, 'Nokia 110 (2022)', '20', 'https://fdn2.gsmarena.com/vv/bigpic/nokia-110-2022.jpg', 'mobile'),
(15, 'Nokia C210', '110', 'https://fdn2.gsmarena.com/vv/bigpic/nokia-c210.jpg', 'mobile'),
(16, 'Huawei Mate 60 Pro', '890', 'https://fdn2.gsmarena.com/vv/bigpic/huawei-mate-60-pro.jpg', 'mobile'),
(17, 'Huawei P60 Pro', '830', 'https://fdn2.gsmarena.com/vv/bigpic/huawei-p60-pro.jpg', 'mobile'),
(18, 'OnePlus Open', '1700', 'https://fdn2.gsmarena.com/vv/bigpic/oneplus-open-10.jpg', 'mobile'),
(19, 'Oppo A2', '550', 'https://fdn2.gsmarena.com/vv/bigpic/oneplus-11.jpg', 'mobile'),
(23, 'iQ00001', '210', 'https://m.media-amazon.com/images/I/61gGRaXQoGL._SX679_.jpg', 'mobile'),
(24, 'vivov60', '250000', 'https://m.media-amazon.com/images/I/71BiI-RQ+-L._SX679_.jpg', 'mobile'),
(26, 'vivov60', '222', 'https://m.media-amazon.com/images/I/61gGRaXQoGL._SX679_.jpg', 'handsfree'),
(27, 'boAt Rockerz Wireless Headphones', '1000', 'https://encrypted-tbn3.gstatic.com/shopping?q=tbn:ANd9GcQ9BWiy-e45dNJDCTvYZg3kQ7su1T9vQF28FP0y4c41QRFklFKIaMA_gohXxWthXiT_oVzkC6LW0LFKQTgzqtvOD3dxCRsPmhkpwMaiP2TPo2uvDmUx7j5SmOQ', 'handsfree');

-- --------------------------------------------------------

--
-- Table structure for table `user_info`
--

CREATE TABLE `user_info` (
  `id` int(100) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_info`
--

INSERT INTO `user_info` (`id`, `firstname`, `lastname`, `email`, `password`) VALUES
(4, 'vivek', 'thummar', 'vivek1@gmail.com', '$2y$10$zmYqsZsgTDiK8he8p03VnuFQoCOTsdlAcUC5N6Bqz4LCnCg217k2C'),
(7, 'Admin', 'Admin', 'admin@gmail.com', 'admin'),
(10, 'jenish ', 'sheladiya', 'jenish@gmail.com', '1234'),
(11, 'ar', 'ar', 'ar@gmail.com', '$2y$10$NwxL9stwcQc2/aizrqJIee0/3iEWXpuONb.Tj0ca0Gn2fIKMosLby'),
(13, 'jenish ', 'sheladiya', 'jenish12@gmail.com', '$2y$10$oSOQRB3hXt9bznoroSrv0../GQxzH.tgh9gBg/44vjvVaakm/VjYe');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_info`
--
ALTER TABLE `user_info`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `user_info`
--
ALTER TABLE `user_info`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
