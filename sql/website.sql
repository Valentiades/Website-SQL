-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 03, 2024 at 11:29 PM
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
-- Database: `website`
--

-- --------------------------------------------------------

--
-- Table structure for table `categorys`
--

CREATE TABLE `categorys` (
  `category_id` int(11) NOT NULL,
  `category_post` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categorys`
--

INSERT INTO `categorys` (`category_id`, `category_post`) VALUES
(1, 'ทั่วไป'),
(2, 'ข่าวสาร'),
(3, 'อุบัติเหตุ'),
(4, 'เทคโนโลยี'),
(5, 'สถานที่ท่องเที่ยว'),
(6, 'สุขภาพ'),
(7, 'ความรัก'),
(8, 'กีฬา'),
(9, 'ภาพถ่าย'),
(10, 'ตลาดมือสอง'),
(11, 'รถยนต์'),
(12, 'จักรยานยนต์');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `comment_text` text DEFAULT NULL,
  `date_comment` timestamp NOT NULL DEFAULT current_timestamp(),
  `post_id` int(11) NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`comment_id`, `comment_text`, `date_comment`, `post_id`, `id`) VALUES
(149, 'สวัสดีครับบบบ', '2024-10-03 20:16:45', 172, 21),
(150, 'นอยอ่าา ไม่มีเรา', '2024-10-03 20:17:18', 172, 19),
(163, 'ชายแท้เตะบอลครัฟ', '2024-10-03 20:45:46', 183, 19);

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `status` enum('pending','in_progress','resolved') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `user_id`, `subject`, `message`, `status`, `created_at`) VALUES
(13, 19, 'ระบบขัดข้อง', 'ทำไมเว็บถึงหน่วงจังเลยครับ', 'resolved', '2024-10-03 20:30:06');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `post_id` int(11) NOT NULL,
  `category_id` int(50) DEFAULT NULL,
  `text_post` text DEFAULT NULL,
  `image_post` varchar(255) DEFAULT NULL,
  `date_post` timestamp NULL DEFAULT current_timestamp(),
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`post_id`, `category_id`, `text_post`, `image_post`, `date_post`, `id`) VALUES
(172, 2, 'นี่คือโพสต์แรก', '66fefbb74a5fe_DSC_5321.jpg', '2024-10-03 19:08:05', 20),
(173, 5, 'หาแนวร่วมไปเที่ยวครับ ใครไป +1', '66fefce53cf88_66fda07605cb5_16f0a917912a436a8e5d63979e74d968[1].jpg', '2024-10-03 20:21:18', 19),
(177, 4, 'การ์ดจออยู่ตรงใหนหรอครับบบบบ', '66ff00569eb22_Screenshot 2024-06-28 151155.png', '2024-10-03 20:36:38', 21),
(183, 1, 'คนนี้ชื่ออะไรครับ', '66ff0230345aa_Screenshot 2024-05-26 131611.png', '2024-10-03 20:44:32', 21);

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `role_id` int(11) NOT NULL,
  `role` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`role_id`, `role`) VALUES
(1, 'admin'),
(2, 'user');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `firstname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lastname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` int(10) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `image_user` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `email`, `password`, `role`, `username`, `image_user`) VALUES
(19, 'Jetsada', 'Srichanet', 'jetsada.sc@rmuti.ac.th', '$2y$10$P0vqFQmzHTNwZCwXTg5AkuDghYjvLA26t0uO8Heh637b6d9sNrmYK', 2, 'หัวหน้าเผ่ากิ้งกือล้านนา', '66fefba8a4ad7_IMG_20241003_224426.jpg'),
(20, 'Pakorn', 'Singkhajan', 'pakornnz005@gmail.com', '$2y$10$/5HBIQr4/oF6lEWHHdLEee2UOn3QbkhG7qBE.YJkwS4v9P6Mv/JLe', 2, 'ปอกอรอณอเณรการันต์', '66fef00919e69_IMG_5243.jpg'),
(21, 'Nattawat', 'Phungern', 'nattawatphg@gmail.com', '$2y$10$pLxUnm8MANz3SyeHYTY7ZOBxJd5raO8J6.MqP8AZxm578LbDYk8Ly', 1, 'Nattawat Phungern', '66fefb99dfb9e_439857667_2081591265544053_8967209659224277100_n.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categorys`
--
ALTER TABLE `categorys`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `comments_ibfk_1` (`post_id`),
  ADD KEY `comments_ibfk_2` (`id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `post_user` (`id`),
  ADD KEY `posts_ibfk_2` (`category_id`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_role` (`role`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categorys`
--
ALTER TABLE `categorys`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=164;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=184;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`) ON DELETE CASCADE;

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `posts_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categorys` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role`) REFERENCES `role` (`role_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
