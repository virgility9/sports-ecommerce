-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 17, 2026 at 01:24 PM
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
-- Database: `sports_ecommerce`
--

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `status` enum('placed','processing','shipped','delivered','cancelled') NOT NULL DEFAULT 'placed',
  `recipient_name` varchar(120) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(120) NOT NULL,
  `postal_code` varchar(30) NOT NULL,
  `payment_method` varchar(40) NOT NULL DEFAULT 'Pay on delivery',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total`, `status`, `recipient_name`, `address`, `city`, `postal_code`, `payment_method`, `created_at`) VALUES
(1, 1, 376.00, 'placed', 'Virgility', 'jagobiao', 'cebu', '1111', 'Pay on delivery', '2026-11-17 10:16:52');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_name` varchar(180) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `unit_price`, `quantity`) VALUES
(1, 1, 1, 'AeroFlex Training Tee', 34.00, 1),
(2, 1, 13, 'Sprint Start 5-inch Short', 40.00, 2),
(3, 1, 15, 'CloudRide Running Shoe', 120.00, 1),
(4, 1, 31, 'Elevate Crop Top', 34.00, 1),
(5, 1, 43, 'RidgeLine Quilted Jacket', 108.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(180) NOT NULL,
  `category` varchar(80) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image_url` varchar(1000) NOT NULL DEFAULT '',
  `stock` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `featured` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `category`, `description`, `price`, `image_url`, `stock`, `featured`, `created_at`) VALUES
(1, 'AeroFlex Training Tee', 'Training', 'Lightweight, breathable performance fabric built for every rep and every run.', 34.00, 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?auto=format&fit=crop&w=900&q=85', 39, 1, '2026-11-17 08:03:04'),
(2, 'Stride 7-inch Running Short', 'Running', 'Four-way stretch with a secure zip pocket and a comfortable inner brief.', 42.00, 'https://images.unsplash.com/photo-1517836357463-d25dfeac3438?auto=format&fit=crop&w=900&q=85', 32, 1, '2026-11-17 08:03:04'),
(3, 'Summit Performance Hoodie', 'Outerwear', 'A soft, warm layer with sweat-wicking comfort for warm-ups and cool-downs.', 78.00, 'https://images.unsplash.com/photo-1556821840-3a63f95609a7?auto=format&fit=crop&w=900&q=85', 18, 1, '2026-11-17 08:03:04'),
(4, 'Court Classic Training Tank', 'Training', 'An easy-moving, quick-dry essential for court sessions and strength days.', 29.00, 'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?auto=format&fit=crop&w=900&q=85', 25, 0, '2026-11-17 08:03:04'),
(5, 'Pace High-Rise Legging', 'Running', 'Supportive high-rise fit with a smooth waistband and a stay-put feel.', 64.00, 'https://images.unsplash.com/photo-1552196563-55cd4e45efb3?auto=format&fit=crop&w=900&q=85\r\n', 22, 1, '2026-11-17 08:03:04'),
(6, 'Everyday Track Jacket', 'Outerwear', 'A clean, versatile layer with a full zip and room to move.', 86.00, 'https://images.unsplash.com/photo-1551028719-00167b16eac5?auto=format&fit=crop&w=900&q=85', 14, 0, '2026-11-17 08:03:04'),
(7, 'Velocity Long-Sleeve Top', 'Running', 'Lightweight coverage and breathable comfort for changing conditions.', 48.00, 'https://images.unsplash.com/photo-1503341504253-dff4815485f1?auto=format&fit=crop&w=900&q=85', 20, 0, '2026-11-17 08:03:04'),
(8, 'FlexForm Sports Bra', 'Training', 'Medium support and a soft, secure fit for training and studio sessions.', 45.00, 'https://images.unsplash.com/photo-1518310383802-640c2de311b2?auto=format&fit=crop&w=900&q=85', 26, 0, '2026-11-17 08:03:04'),
(9, 'SwiftKnit Running Tee', 'Running', 'Featherlight knit that moves with you on tempo days and long runs.', 36.00, 'https://images.unsplash.com/photo-1618354691373-d851c5c3a990?auto=format&fit=crop&w=900&q=85', 38, 0, '2026-11-17 09:36:43'),
(10, 'AeroLite Race Singlet', 'Running', 'A barely-there race singlet with open mesh panels for maximum airflow.', 32.00, 'https://images.unsplash.com/photo-1483721310020-03333e577078?auto=format&fit=crop&w=900&q=85', 30, 0, '2026-11-17 09:36:43'),
(11, 'Tempo Long-Sleeve Run Top', 'Running', 'Lightweight long-sleeve coverage with thumbholes for cool morning miles.', 52.00, 'https://images.unsplash.com/photo-1476480862126-209bfaa8edc8?auto=format&fit=crop&w=900&q=85', 24, 1, '2026-11-17 09:36:43'),
(12, 'Horizon Run Tee', 'Running', 'Soft everyday run tee with a relaxed fit and sweat-wicking finish.', 38.00, 'https://images.unsplash.com/photo-1552674605-db6ffd4facb5?auto=format&fit=crop&w=900&q=85', 35, 0, '2026-11-17 09:36:43'),
(13, 'Sprint Start 5-inch Short', 'Running', 'A featherlight 5-inch short built for the blocks and the final kick.', 40.00, 'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?auto=format&fit=crop&w=900&q=85', 26, 1, '2026-11-17 09:36:43'),
(14, 'MotionFlex Running Tight', 'Running', 'Full-length compressive tight with a secure pocket and stay-put waistband.', 58.00, 'https://images.unsplash.com/photo-1538805060514-97d9cc17730c?auto=format&fit=crop&w=900&q=85', 22, 0, '2026-11-17 09:36:43'),
(15, 'CloudRide Running Shoe', 'Running', 'Responsive cushioning and a breathable upper for daily road miles.', 120.00, 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=900&q=85', 15, 1, '2026-11-17 09:36:43'),
(16, 'Velocity Runner Shoe', 'Running', 'A light, springy trainer that rolls smoothly from warm-up to cool-down.', 110.00, 'https://images.unsplash.com/photo-1560769629-975ec94e6a86?auto=format&fit=crop&w=900&q=85', 18, 0, '2026-11-17 09:36:43'),
(17, 'PaceFlow Knit Shoe', 'Running', 'Sock-like knit upper with a grippy outsole for tempo runs and city miles.', 95.00, 'https://images.unsplash.com/photo-1606107557195-0e29a4b5b4aa?auto=format&fit=crop&w=900&q=85', 20, 0, '2026-11-17 09:36:43'),
(18, 'AirFlex Road Shoe', 'Running', 'Clean, cushioned road shoe with breathable mesh and all-day comfort.', 105.00, 'https://images.unsplash.com/photo-1549298916-b41d501d3772?auto=format&fit=crop&w=900&q=85', 15, 0, '2026-11-17 09:36:43'),
(19, 'UltraDry Run Long-Sleeve', 'Running', 'Quick-dry long sleeve that layers easily under a vest or jacket.', 48.00, 'https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?auto=format&fit=crop&w=900&q=85', 26, 0, '2026-11-17 09:36:43'),
(20, 'FeatherLight Run Tee', 'Running', 'An ultra-light tee that feels like nothing on easy days and long runs.', 34.00, 'https://images.unsplash.com/photo-1583743814966-8936f5b7be1a?auto=format&fit=crop&w=900&q=85', 40, 0, '2026-11-17 09:36:43'),
(21, 'NightRun Reflective Tee', 'Running', 'Reflective details that keep you visible on early morning and evening runs.', 42.00, 'https://images.unsplash.com/photo-1576566588028-4147f3842f27?auto=format&fit=crop&w=900&q=85', 25, 0, '2026-11-17 09:36:43'),
(22, 'Summit Trail Shoe', 'Running', 'Grippy trail shoe with a protective upper for dirt, gravel and roots.', 115.00, 'https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?auto=format&fit=crop&w=900&q=85', 14, 0, '2026-11-17 09:36:43'),
(23, 'StreetRun Court Shoe', 'Running', 'A clean court-inspired runner for rest days, travel and everyday wear.', 88.00, 'https://images.unsplash.com/photo-1608231387042-66d1773070a5?auto=format&fit=crop&w=900&q=85', 19, 0, '2026-11-17 09:36:43'),
(24, 'PowerFlex Training Tee', 'Training', 'A sturdy training tee with four-way stretch for lifts, circuits and classes.', 36.00, 'https://images.unsplash.com/photo-1518459031867-a89b944bffe4?auto=format&fit=crop&w=900&q=85', 34, 1, '2026-11-17 09:36:43'),
(25, 'CoreDry Gym Tank', 'Training', 'A sweat-wicking gym tank with a wide arm opening for full range of motion.', 28.00, 'https://images.unsplash.com/photo-1541534741688-6078c6bfb5c5?auto=format&fit=crop&w=900&q=85', 30, 0, '2026-11-17 09:36:43'),
(26, 'IronWorks Muscle Tank', 'Training', 'A cut-in muscle tank built for pull days and heavy sessions.', 30.00, 'https://images.unsplash.com/photo-1526506118085-60ce8714f8c5?auto=format&fit=crop&w=900&q=85', 27, 0, '2026-11-17 09:36:43'),
(27, 'GymCore Lifting Tee', 'Training', 'A durable lifting tee that holds its shape through every heavy set.', 38.00, 'https://images.unsplash.com/photo-1605296867304-46d5465a13f1?auto=format&fit=crop&w=900&q=85', 25, 0, '2026-11-17 09:36:43'),
(28, 'StudioFlow Yoga Set', 'Training', 'A soft matching yoga set with a supportive top and flexible fit.', 64.00, 'https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?auto=format&fit=crop&w=900&q=85', 20, 1, '2026-11-17 09:36:43'),
(29, 'Balance Yoga Legging', 'Training', 'Buttery-soft legging with a high waist for studio sessions and slow flows.', 56.00, 'https://images.unsplash.com/photo-1575052814086-f385e2e2ad1b?auto=format&fit=crop&w=900&q=85', 23, 0, '2026-11-17 09:36:43'),
(30, 'FlexForm 2.0 Sports Bra', 'Training', 'Medium support with a smooth band for training, studio and circuits.', 46.00, 'https://images.unsplash.com/photo-1548690312-e3b507d8c110?auto=format&fit=crop&w=900&q=85', 26, 0, '2026-11-17 09:36:43'),
(31, 'Elevate Crop Top', 'Training', 'A cropped training top with a snug fit that stays put through every rep.', 34.00, 'https://images.unsplash.com/photo-1552902865-b72c031ac5ea?auto=format&fit=crop&w=900&q=85', 28, 0, '2026-11-17 09:36:43'),
(32, 'TrainRight Jogger Pant', 'Training', 'A tapered jogger with deep pockets for warm-ups, travel and rest days.', 62.00, 'https://images.unsplash.com/photo-1506629082955-511b1aa562c8?auto=format&fit=crop&w=900&q=85', 21, 0, '2026-11-17 09:36:43'),
(33, 'StudioKnit Jogger', 'Training', 'A soft knit jogger with a relaxed taper for studio days and slow mornings.', 58.00, 'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?auto=format&fit=crop&w=900&q=85', 24, 0, '2026-11-17 09:36:43'),
(34, 'CrossTrain 9-inch Short', 'Training', 'A tough 9-inch short with stretch for sleds, boxes and barbell work.', 44.00, 'https://images.unsplash.com/photo-1533681904393-9ab6eee7e408?auto=format&fit=crop&w=900&q=85', 28, 0, '2026-11-17 09:36:43'),
(35, 'Rush Training Tee', 'Training', 'A go-to graphic training tee in soft cotton-blend performance fabric.', 35.00, 'https://images.unsplash.com/photo-1554568218-0f1715e72254?auto=format&fit=crop&w=900&q=85', 36, 0, '2026-11-17 09:36:43'),
(36, 'HeavyDuty Lifting Tee', 'Training', 'A heavyweight tee that stands up to chalk, bars and hard sessions.', 37.00, 'https://images.unsplash.com/photo-1599058917212-d750089bc07e?auto=format&fit=crop&w=900&q=85', 22, 0, '2026-11-17 09:36:43'),
(37, 'Motion Seamless Bra', 'Training', 'A seamless bra with a smooth second-skin fit for training and studio.', 44.00, 'https://images.unsplash.com/photo-1594381898411-846e7d193883?auto=format&fit=crop&w=900&q=85', 27, 0, '2026-11-17 09:36:43'),
(38, 'PrimeKnit Training Tee', 'Training', 'A premium knit training tee with a clean fit for gym and street.', 36.00, 'https://images.unsplash.com/photo-1581009146145-b5ef050c2e1e?auto=format&fit=crop&w=900&q=85', 33, 0, '2026-11-17 09:36:43'),
(39, 'StormShield Rain Jacket', 'Outerwear', 'A waterproof shell with a clean fit for rainy runs and wet commutes.', 98.00, 'https://images.unsplash.com/photo-1591047139829-d91aecb6caea?auto=format&fit=crop&w=900&q=85', 16, 1, '2026-11-17 09:36:43'),
(40, 'Trailhead Windbreaker', 'Outerwear', 'A light windbreaker with a packable hood for trailheads and breezy days.', 86.00, 'https://images.unsplash.com/photo-1544022613-e87ca75a784a?auto=format&fit=crop&w=900&q=85', 18, 0, '2026-11-17 09:36:43'),
(41, 'CloudPuffer Light Jacket', 'Outerwear', 'A light insulated puffer that packs small and warms fast.', 118.00, 'https://images.unsplash.com/photo-1564557287817-3785e38ec1f5?auto=format&fit=crop&w=900&q=85', 15, 0, '2026-11-17 09:36:43'),
(42, 'AeroCycle Wind Jacket', 'Outerwear', 'A close-fitting wind jacket with rear pockets for long rides and runs.', 92.00, 'https://images.unsplash.com/photo-1517649763962-0c623066013b?auto=format&fit=crop&w=900&q=85', 17, 0, '2026-11-17 09:36:43'),
(43, 'RidgeLine Quilted Jacket', 'Outerwear', 'A quilted layer with a warm hood for cool mornings and city miles.', 108.00, 'https://images.unsplash.com/photo-1548126032-079a0fb0099d?auto=format&fit=crop&w=900&q=85', 13, 1, '2026-11-17 09:36:43'),
(44, 'Everyday Layer Jacket', 'Outerwear', 'A versatile layering jacket that works over tees, tanks and hoodies.', 78.00, 'https://images.unsplash.com/photo-1578932750294-f5075e85f44a?auto=format&fit=crop&w=900&q=85', 20, 0, '2026-11-17 09:36:43'),
(45, 'WarmUp Track Jacket', 'Outerwear', 'A classic track jacket for warm-ups, cool-downs and sideline days.', 72.00, 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?auto=format&fit=crop&w=900&q=85', 22, 0, '2026-11-17 09:36:43'),
(46, 'Summit Training Vest', 'Outerwear', 'A light training vest that keeps your core warm without bulk.', 68.00, 'https://images.unsplash.com/photo-1583454110551-21f2fa2afe61?auto=format&fit=crop&w=900&q=85', 19, 0, '2026-11-17 09:36:43'),
(47, 'RidgeLine Training Anorak', 'Outerwear', 'A pullover anorak with a half zip for changeable training weather.', 84.00, 'https://images.unsplash.com/photo-1518611012118-696072aa579a?auto=format&fit=crop&w=900&q=85', 16, 0, '2026-11-17 09:36:43'),
(48, 'FrostGuard Thermal Hoodie', 'Outerwear', 'A brushed thermal hoodie for cold warm-ups and colder rest days.', 82.00, 'https://images.unsplash.com/photo-1595078475328-1ab05d0a6a0e?auto=format&fit=crop&w=900&q=85', 21, 0, '2026-11-17 09:36:43'),
(49, 'BreezeBreaker Training Jacket', 'Outerwear', 'A featherlight breaker that blocks wind without slowing you down.', 76.00, 'https://images.unsplash.com/photo-1571731956672-f2b94d7dd0cb?auto=format&fit=crop&w=900&q=85', 18, 0, '2026-11-17 09:36:43'),
(50, 'Alpine Warm-Up Jacket', 'Outerwear', 'A warm zip-up for alpine mornings and pre-session layers.', 88.00, 'https://images.unsplash.com/photo-1550345332-09e3ac987658?auto=format&fit=crop&w=900&q=85', 15, 0, '2026-11-17 09:36:43'),
(51, 'Metro Commuter Hoodie', 'Outerwear', 'A heavyweight hoodie with a kangaroo pocket for commutes and recovery.', 74.00, 'https://images.unsplash.com/photo-1532029837206-abbe2b7620e3?auto=format&fit=crop&w=900&q=85', 24, 0, '2026-11-17 09:36:43'),
(52, 'Sunset Fleece Pullover', 'Outerwear', 'A soft fleece pullover for golden-hour sessions and slow evenings.', 66.00, 'https://images.unsplash.com/photo-1506126613408-eca07ce68773?auto=format&fit=crop&w=900&q=85', 26, 0, '2026-11-17 09:36:43'),
(53, 'Harbor Sideline Jacket', 'Outerwear', 'A durable sideline jacket with room to layer over full kit.', 96.00, 'https://images.unsplash.com/photo-1523381210434-271e8be1f52b?auto=format&fit=crop&w=900&q=85', 15, 0, '2026-11-17 09:36:43');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(120) NOT NULL,
  `email` varchar(190) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('customer','admin') NOT NULL DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password_hash`, `role`, `created_at`) VALUES
(1, 'Virgility', 'blanknem18@gmail.com', '$2y$10$IMIiVGKfs286IZlOa0dNmehNXTO8rQzn.ihFTSeHxQgATFTTGYJQK', 'customer', '2026-11-17 09:28:21'),
(5, 'Rally Admin', 'admin@gmail.com', '$2y$10$Xl59HBrilhP5MvcEM.Yk8u3QR4hHhnThStCc/a6KlD1VpI/7bD3xG', 'admin', '2026-11-17 10:45:43');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_orders_user` (`user_id`,`created_at`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_order_items_order` (`order_id`),
  ADD KEY `fk_order_items_product` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_products_name` (`name`),
  ADD KEY `idx_products_category` (`category`),
  ADD KEY `idx_products_name` (`name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_order_items_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_order_items_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
