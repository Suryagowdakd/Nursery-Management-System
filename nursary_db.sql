-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 24, 2023 at 11:55 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nursary_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `pid` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` int(100) NOT NULL,
  `quantity` int(100) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `number` varchar(12) NOT NULL,
  `message` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`id`, `user_id`, `name`, `email`, `number`, `message`) VALUES
(14, 26, 'hello world', 'user1@gmail.com', '987989488', 'hello'),
(15, 26, 'hello world', 'user1@gmail.com', '787977576', 'hello');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `pid` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `number` varchar(12) NOT NULL,
  `email` varchar(100) NOT NULL,
  `method` varchar(50) NOT NULL,
  `address` varchar(500) NOT NULL,
  `city` varchar(100) NOT NULL,
  `products_name` varchar(1000) NOT NULL,
  `total_price` int(100) NOT NULL,
  `placed_on` varchar(6) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `payment_status` varchar(20) NOT NULL DEFAULT 'pending',
  `product_image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `pid`, `name`, `number`, `email`, `method`, `address`, `city`, `products_name`, `total_price`, `placed_on`, `payment_status`, `product_image`) VALUES
(57, 26, 0, 'user1', '2147483647', 'user1@gmail.com', 'cash on delivery', 'bhaktapur', '', 'Palm Tree (4)', 8000, '23-Aug', 'completed', ''),
(60, 26, 0, 'user1', '2147483647', 'user1@gmail.com', 'cash on delivery', 'bhaktapur', '', 'Palm Tree (1)', 2000, '24-Aug', 'pending', '');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(100) NOT NULL,
  `order_id` int(100) NOT NULL,
  `pid` int(100) NOT NULL,
  `productsname` varchar(100) NOT NULL,
  `quantity` int(100) NOT NULL,
  `price` int(100) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `pid`, `productsname`, `quantity`, `price`, `image`) VALUES
(15, 57, 26, 'Palm Tree', 4, 2000, 'palmtree.jpg'),
(18, 60, 26, 'Palm Tree', 1, 2000, 'palmtree.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `category` varchar(100) NOT NULL,
  `details` varchar(500) NOT NULL,
  `price` int(100) NOT NULL,
  `stock_quantity` int(255) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `category`, `details`, `price`, `stock_quantity`, `image`) VALUES
(24, 'Rubber Tree', 'plants', 'The Rubber Tree (Ficus elastica) is a visually striking houseplant known for its large, glossy leaves and unique growth habit. With its thick, dark green foliage and ability to purify the air, this tropical rainforest native adds a bold and dramatic element to any indoor space. Growing up to 8-10 feet tall, the Rubber Tree thrives in bright, indirect light and prefers moderately humid environments. Its low-maintenance nature and impressive ornamental value make it an ideal choice for plant enthu', 2000, 15, 'rubberplant.jpg'),
(25, 'Strelitzia Reginae', 'plants', 'Strelitzia reginae, commonly known as the crane flower, bird of paradise, or isigude in Nguni, is a species of flowering plant indigenous to South Africa. An evergreen perennial, it is widely cultivated for its dramatic flowers. In temperate areas it is a popular houseplant.', 2500, 20, 'strelitziaReginae.jpg'),
(26, 'Palm Tree', 'plants', 'Palm trees are a type of tropical or subtropical tree belonging to the family Arecaceae. They are known for their distinctive appearance, characterized by a tall, slender trunk and a crown of large, fan-shaped or feather-like leaves at the top. Palm trees are widely distributed across the world, particularly in regions with warm climates.\r\n\r\n', 2000, 10, 'palmtree.jpg'),
(27, 'Sansevieria', 'plants', 'Sansevieria is a historically recognized genus of flowering plants, native to Africa, notably Madagascar, and southern Asia, now included in the genus Dracaena on the basis of molecular phylogenetic studies.', 2500, 25, 'sansevieria.jpg'),
(31, 'Rose', '', 'this is rose', 1500, 5, 'rose.jpg'),
(32, 'crowbars', 'tools', 'faee', 1000, 50, 'crowbars.jpg'),
(33, 'scissors', 'tools', 'aaetsaf', 1500, 50, 'gardening-scissor.jpg'),
(34, 'ferti', 'fertilizers', 'njkbnjn', 1800, 10, 'ferti.jpg'),
(35, 'pesti','Pesticides', 'ABCD', 1600, 22,'pesti.jpeg'),
(36, 'pesti1','Pesticides', 'ABCD', 1500, 25,'pesti1.jpeg'),
(37, 'istock','seeds', 'abcd', 1000, 20,'istock.jpg'),
(38, 'istock1','seeds', 'abcd', 800, 15,'istock1.jpg'),
(39, 'ferti1','fertilizers', 'abcd', 1900, 8,'ferti1.jpg'),
(40, 'nursa','plants', 'abcd', 800,9,'nursa.jpeg'),
(41, 'redply','plants','abcd',899,10,'redply,jpeg'),
(42, 'berry','plants','abcd',900,7,'berry.jpeg'),
(43, 'nov','plants','abcd',995,8,'nov.jpeg'),
(44, 'sply','plants','abcd',1000,11,'sply.jpeg'),
(45, 'yk','seeds','abcd',500,8,'yk.jpeg'),
(46, 'Teak','seeds','abcd',580,9,'Teak.jpeg'),
(47, 'Neem','seeds','abcd',570,8,'Neem.jpeg'),
(48, 'pawpaw','seeds','abcd',600,9,'pawpaw.jpeg'),
(49, 'beans','seeds','abcd',800,12,'beans.jpeg'),
(50, 'calcium nitrate','fertilizers', 'abcd', 1900, 8,'calcium nitrate.jpeg'),
(51, 'triplesulphate','fertilizers', 'abcd', 1900, 8,'triplesulphate.jpg'),
(52, 'praswar','fertilizers', 'abcd', 2000, 10,'praswar.jpeg'),
(53, 'graycolor','fertilizers', 'abcd', 2100, 10,'graycolor.jpeg'),
(54, 'micronutrient','fertilizers', 'abcd', 2050, 10,'micronutrient.jpeg'),
(56, 'greengrowth','fertilizers', 'abcd', 2500, 10,'greengrowth.jpeg'),
(57, 'freight','Pesticides', 'abc', 1700, 15,'freight.jpg'),
(58, 'natural','Pesticides', 'abc', 1000, 10,'natural.jpg'),
(59, 'aero','Pesticides', 'abc', 2000, 8,'aero.jpeg'),
(60, 'tokjpeg','Pesticides', 'abc', 1700, 15,'tokjpeg.jpeg'),
(61, 'forru','tools', 'abc', 300, 15,'forru.jpeg'),
(62, 'cuttur','tools', 'abc', 400, 10,'cuttur.jpeg'),
(63, 'toolhiri','tools', 'abc', 500, 15,'toolhiri.jpeg'),
(64, 'ehow','tools', 'abc', 600, 15,'ehow.jpeg');




-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `address` varchar(255) NOT NULL,
  `number` int(50) NOT NULL,
  `user_type` varchar(20) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `address`, `number`, `user_type`) VALUES
(23, 'admin1', 'admin1@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 'bhaktapur', 2147483647, 'admin'),
(24, 'user4', 'user4@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 'kathmandu', 65657548, 'user'),
(25, 'admin3', 'admin3@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 'kathmandu', 987654321, 'admin'),
(26, 'user1', 'user1@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', 'bhaktapur', 2147483647, 'user'),
(27, 'hello', 'hello@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', '', 0, 'user'),
(28, 'admin7', 'admin7@gmail.com', '81dc9bdb52d', 'nagara', 2142789046, 'admin');
-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `pid` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` int(100) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `pid` (`pid`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `pid` (`pid`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pid` (`pid`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=271;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`pid`) REFERENCES `products` (`id`);

--
-- Constraints for table `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `message_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_3` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_4` FOREIGN KEY (`pid`) REFERENCES `products` (`id`);

--
-- Constraints for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`pid`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `wishlist_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
