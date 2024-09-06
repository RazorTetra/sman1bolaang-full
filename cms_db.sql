-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 06, 2024 at 11:42 PM
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
-- Database: `cms_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `about_info`
--

CREATE TABLE `about_info` (
  `id` int(11) NOT NULL,
  `description` text NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `instagram` varchar(255) DEFAULT NULL,
  `youtube` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `about_info`
--

INSERT INTO `about_info` (`id`, `description`, `name`, `image`, `facebook`, `instagram`, `youtube`) VALUES
(1, 'Dengan penuh rasa syukur dan bangga menyambut Anda semua di platform website kami yang dirancang untuk menjadi jembatan informasi yang menghubungkan kami dengan seluruh siswa, serta masyarakat luas.<br><br>Mewujudkan visi dan misi sekolah dengan penuh dedikasi. Melalui slogan kami, MATTOA (Menjadikan Aku Tangguh Terampil Optimis Amanah).<br><br>Terima kasih telah mengunjungi website kami.&nbsp;<span style=\"background-color: rgba(255,255,255,var(--tw-bg-opacity)); font-family: inherit;\">Mari bersama-sama kita wujudkan pendidikan berkualitas dan membangun masa depan yang gemilang.</span>', 'Sukur Moanto,<br><br><b>Brusly Polakitan, S.Kom, M.Pd.</b><br><br>Kepala Sekolah SMK Negeri 1 Bolaang<div><br></div><div><br></div>', 'assets/img/about-perfil-1.jpg', 'https://www.facebook.com/bpolakitan', 'https://www.instagram.com/bpolakitan', 'https://www.youtube.com/bpolakitan');

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE `articles` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`id`, `title`, `content`, `image`, `created_at`, `updated_at`) VALUES
(2, 'Belajar Bersama Guru 2', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 'assets/img/66d9528252a4f.jpg', '2024-09-04 20:09:38', '2024-09-05 14:41:06'),
(5, 'Menulis Puisi', 'asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf asdf ', 'assets/img/berita-5 - Copy.jpg', '2024-09-05 15:13:34', '2024-09-05 15:13:34');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `name`, `email`, `subject`, `message`, `created_at`) VALUES
(2, 'asdf', 'admin@gmail.com', 'addf', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', '2024-09-06 15:14:33'),
(3, 'asdf', 'asdf@gmail.com', 'asdf', 'asdf', '2024-09-06 19:14:29');

-- --------------------------------------------------------

--
-- Table structure for table `gallery`
--

CREATE TABLE `gallery` (
  `id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_displayed` tinyint(1) DEFAULT 0,
  `display_order` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gallery`
--

INSERT INTO `gallery` (`id`, `image`, `created_at`, `is_displayed`, `display_order`) VALUES
(16, 'berita-1 - Copy.jpg', '2024-09-05 10:13:48', 0, 0),
(17, 'berita-2 - Copy.jpg', '2024-09-05 10:13:48', 0, 0),
(18, 'berita-3 - Copy.jpg', '2024-09-05 10:13:48', 0, 0),
(19, 'berita-4 - Copy.jpg', '2024-09-05 10:13:48', 0, 0),
(20, 'berita-5 - Copy.jpg', '2024-09-05 10:13:48', 0, 0),
(21, 'berita-6 - Copy.jpg', '2024-09-05 10:13:48', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `social_media_links`
--

CREATE TABLE `social_media_links` (
  `id` int(11) NOT NULL,
  `platform` varchar(50) NOT NULL,
  `url` varchar(255) NOT NULL,
  `icon` varchar(50) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `social_media_links`
--

INSERT INTO `social_media_links` (`id`, `platform`, `url`, `icon`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Facebook', 'https://www.facebook.com/smkn1bolaang', 'ri-facebook-circle-line', 1, '2024-09-06 18:16:33', '2024-09-06 18:16:33'),
(2, 'Instagram', '#', 'ri-instagram-line', 1, '2024-09-06 18:16:33', '2024-09-06 18:16:33'),
(3, 'YouTube', '#', 'ri-youtube-line', 1, '2024-09-06 18:16:33', '2024-09-06 18:16:33');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `role` enum('admin','editor','user') NOT NULL,
  `login_attempts` int(11) DEFAULT 0,
  `last_attempt` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `role`, `login_attempts`, `last_attempt`) VALUES
(1, 'admin', '$2y$10$ErtD1Y6bPEbZYkM4sHQ.G.qNxtg40GH50lALC2hec6hdsrj8QwQK2', 'admin@gmail.com', 'admin', 0, NULL),
(2, 'super', '$2y$10$cz2tuRYk8BqaGOSqaLNkBOWgY5uDqJ1b53REz7gpjcKGF8ELwir2.', 'super@gmail.com', 'admin', 0, '2024-09-05 11:24:35');

-- --------------------------------------------------------

--
-- Table structure for table `visitors`
--

CREATE TABLE `visitors` (
  `id` int(11) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `visit_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `about_info`
--
ALTER TABLE `about_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gallery`
--
ALTER TABLE `gallery`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `social_media_links`
--
ALTER TABLE `social_media_links`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `visitors`
--
ALTER TABLE `visitors`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `about_info`
--
ALTER TABLE `about_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `gallery`
--
ALTER TABLE `gallery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `social_media_links`
--
ALTER TABLE `social_media_links`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `visitors`
--
ALTER TABLE `visitors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
