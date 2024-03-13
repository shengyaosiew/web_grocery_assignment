-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 13, 2024 at 03:12 PM
-- Server version: 8.0.31
-- PHP Version: 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `webdevelopment`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

DROP TABLE IF EXISTS `cart`;
CREATE TABLE IF NOT EXISTS `cart` (
  `cart_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `price` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `quantity` varchar(2) DEFAULT NULL,
  PRIMARY KEY (`cart_id`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=140 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

DROP TABLE IF EXISTS `feedback`;
CREATE TABLE IF NOT EXISTS `feedback` (
  `feedback_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `satisfied_status` varchar(100) DEFAULT NULL,
  `comment` text,
  `feedback_date` date DEFAULT NULL,
  PRIMARY KEY (`feedback_id`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`feedback_id`, `user_id`, `product_id`, `satisfied_status`, `comment`, `feedback_date`) VALUES
(2, 124, 1, 'excellent', 'Fast delivery and the vegetable is very fresh', '2023-11-04'),
(3, 124, 3, 'okay', 'taste good', '2023-11-06');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `order_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `cart_id` int NOT NULL,
  `paymentname` varchar(255) DEFAULT NULL,
  `paymentphonenumber` varchar(255) DEFAULT NULL,
  `paymentemail` varchar(255) DEFAULT NULL,
  `paymentaddress` varchar(255) DEFAULT NULL,
  `method` varchar(50) DEFAULT NULL,
  `total_quantity` decimal(10,0) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`order_id`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`),
  KEY `cart_id` (`cart_id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `product_id`, `cart_id`, `paymentname`, `paymentphonenumber`, `paymentemail`, `paymentaddress`, `method`, `total_quantity`, `total_price`, `date`) VALUES
(30, 124, 1, 131, 'boon', '0178906754', 'boon@gmail.com', '45 Jalan Merdeka, Taman Bahagia Petaling Jaya, 46000 Selangor, Malaysia', 'e-wallet', '1', '1.99', '2023-11-04 04:15:51'),
(31, 124, 2, 132, 'boon', '0178906754', 'boon@gmail.com', '45 Jalan Merdeka, Taman Bahagia Petaling Jaya, 46000 Selangor, Malaysia', 'e-wallet', '1', '2.99', '2023-11-04 04:15:51'),
(32, 124, 2, 138, 'qwe', '99123', 'ckj@gmaill.com', 'werwerewrwe', 'e-wallet', '1', '2.99', '2023-11-06 01:47:41'),
(33, 124, 3, 139, 'qwe', '99123', 'ckj@gmaill.com', 'werwerewrwe', 'e-wallet', '1', '1.99', '2023-11-06 01:47:41');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

DROP TABLE IF EXISTS `product`;
CREATE TABLE IF NOT EXISTS `product` (
  `product_ID` int NOT NULL AUTO_INCREMENT,
  `userid` int DEFAULT NULL,
  `coverPhoto` varchar(255) DEFAULT NULL,
  `image1` varchar(255) DEFAULT NULL,
  `video_button` varchar(255) DEFAULT NULL,
  `product_name` varchar(100) DEFAULT NULL,
  `product_description` text,
  `product_price` decimal(10,2) DEFAULT NULL,
  `product_stock` int DEFAULT NULL,
  `product_weight` decimal(60,0) DEFAULT NULL,
  PRIMARY KEY (`product_ID`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`product_ID`, `userid`, `coverPhoto`, `image1`, `video_button`, `product_name`, `product_description`, `product_price`, `product_stock`, `product_weight`) VALUES
(2, 123, 'uploads/Kailan Muda.png', 'uploads/Kailan Muda 2.png', 'uploads/Kailan muda.mp4', 'Kailan Muda', 'Lowers Cholesterol and Good Source of Minerals', '2.99', 18, '200'),
(3, 123, 'uploads/Kangkung.png', 'uploads/Kangkung 2.png', 'uploads/Kangkung.mp4', 'Kangkung', 'Reduce blood pressure and boost immunity', '1.99', 29, '200'),
(4, 123, 'uploads/Lemongrass.png', 'uploads/Lemongrass 2.png', 'uploads/Lemongrass.mp4', 'Lemongrass', 'Boost digestion and reduces inflammation', '1.99', 10, '100'),
(5, 123, 'uploads/Sawi Bunga.png', 'uploads/Sawi Bunga 2.png', 'uploads/Sawi Bunga.mp4', 'Sawi Bunga', 'Rich in antioxidants and a source of Vitamin K', '2.99', 20, '200'),
(18, 123, 'uploads/potato01.jpg', 'uploads/potato02.jpeg', 'uploads/potato.mp4', 'potato', 'potato is good', '12.25', 12, '12');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `userid` int NOT NULL AUTO_INCREMENT,
  `usertype` varchar(20) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phonenumber` varchar(100) DEFAULT NULL,
  `fullname` varchar(150) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `postcode` int DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `addressline` text,
  `userpic` varchar(255) DEFAULT NULL,
  `verificationcode` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB AUTO_INCREMENT=130 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userid`, `usertype`, `username`, `password`, `email`, `phonenumber`, `fullname`, `country`, `postcode`, `state`, `city`, `addressline`, `userpic`, `verificationcode`) VALUES
(123, 'seller', 'yao yao', '$2y$12$KQn1xjgJAF4zQReoA0RkXOWCOifSOn4MGrRxzvT6CZEyjezld4kb6', 'yao@gmail.com', '018-7349856', 'yaoyao', 'malaysia', 87000, 'Kuala Lumpur', 'Bukit Jalil', '67 Jalan Raja Chulan', 'uploads/mesi.jpg', '123'),
(124, 'buyer', 'boon leon', '$2y$12$o98Izeatj6nVfonDoZI6LOUstaIBPy0KBBTH54NMi5uRNtvcU8Bh.', 'boon@gmail.com', '0178906754', 'boon', 'Malaysia', 46000, 'Selangor', 'Petaling Jaya', '45 Jalan Merdeka, Taman Bahagia', 'uploads/john.png', '123'),
(128, 'seller', 'ckj', '$2y$12$241.0DiD.HoO.RP0PzANWedWOu6wWKuydV47kyDc9Ed/6TAoA1Lrq', 'shengyao@gmail.com', '018-7349856', 'ckj', 'malaysia', 87000, 'Kuala Lumpur', 'wer', 'werwe', NULL, '123'),
(129, 'buyer', 'yao', '$2y$12$QwwvO1ffnWJKwD8Dw0FoH.seih6F8Hj2HRNxuSoSD0Meoz1noKvhO', '123@gmail.com', '016-7342255', 'yaoyao', 'malaysia', 87000, 'Kuala Lumpur', 'johor', 'no', 'uploads/photo_2024-02-06_08-36-47.jpg', '12345');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`userid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
