-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 09, 2024 at 02:38 PM
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
(1, 'Dengan penuh rasa syukur dan bangga menyambut Anda semua di platform website kami yang dirancang untuk menjadi jembatan informasi yang menghubungkan kami dengan seluruh siswa, serta masyarakat luas.<br><br>Mewujudkan visi dan misi sekolah dengan penuh dedikasi. Melalui slogan kami, MATTOA (Menjadikan Aku Tangguh Terampil Optimis Amanah).<br><br>Terima kasih telah mengunjungi website kami.&nbsp;<span style=\"background-color: rgba(255,255,255,var(--tw-bg-opacity)); font-family: inherit;\">Mari bersama-sama kita wujudkan pendidikan berkualitas dan membangun masa depan yang gemilang.</span>', 'Sukur Moanto,<br><br><b>Brusly Polakitan, S.Kom, M.Pd.</b><br><br><b>Kepala Sekolah SMK Negeri 1 Bolaang</b><div><br></div><div><br></div>', 'assets/img/about-perfil-1.jpg', 'https://www.facebook.com/bpolakitan', 'https://www.instagram.com/bpolakitan', 'https://www.youtube.com/bpolakitan');

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
-- Table structure for table `contact_info`
--

CREATE TABLE `contact_info` (
  `id` int(11) NOT NULL,
  `type` enum('email','whatsapp') NOT NULL,
  `value` varchar(255) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_info`
--

INSERT INTO `contact_info` (`id`, `type`, `value`, `is_active`) VALUES
(1, 'email', 'smkn1bolaang@gmail.com', 1),
(2, 'whatsapp', '0811-437-795', 1);

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
(3, 'asdf', 'asdf@gmail.com', 'asdf', 'asdf', '2024-09-06 19:14:29'),
(4, 'asdf', 'asdf@gmail.com', 'asdf', 'jhfhgdfghdgf', '2024-09-07 05:25:38');

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
-- Table structure for table `skills`
--

CREATE TABLE `skills` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `icon` varchar(50) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `skills`
--

INSERT INTO `skills` (`id`, `title`, `icon`, `image`, `description`) VALUES
(1, 'Bisnis Marketing', 'ri-shake-hands-line', '66dedc334e843.jpg', 'Mempelajari teknik pemasaran, strategi penjualan, serta manajemen keuangan dan akuntansi.'),
(2, 'Desain Komunikasi Visual', 'ri-pen-nib-line', NULL, 'Mempelajari prinsip desain grafis, komunikasi visual, dan multimedia, serta teknik-teknik kreatif untuk merancang materi promosi, iklan, branding, dan media digital.'),
(3, 'Agribisinis & Tanaman Pangan Holtikultura', 'ri-plant-line', NULL, 'Mempelajari teknik-teknik modern dalam budidaya tanaman, manajemen usaha pertanian, serta teknologi terbaru dalam pengolahan dan pemasaran hasil pertanian.'),
(4, 'Kuliner', 'ri-cake-3-line', NULL, 'Mempersiapkan siswa untuk berkarir di industri makanan dengan mengajarkan teknik memasak, pengembangan resep, dan estetika penyajian makanan.'),
(5, 'Asisten Keperawatan', 'ri-hospital-line', NULL, 'Mempersiapkan siswa untuk mendukung tenaga medis dalam perawatan pasien. Siswa mempelajari teknik dasar perawatan kesehatan, termasuk pengukuran tanda vital, pemberian obat, dan perawatan luka.'),
(6, 'Teknik Sepeda Motor', 'ri-e-bike-2-line', NULL, 'Mempelajari teknik-teknik dasar dan lanjutan dalam diagnosis, servis mesin, sistem kelistrikan, dan sistem transmisi.'),
(8, 'Belajar Bersama Guru 4', 'ri-message-2-line', '66dee685270c4.jpg', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#039;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.');

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
(1, 'Facebook', 'https://www.facebook.com/', 'ri-facebook-circle-line', 1, '2024-09-06 18:16:33', '2024-09-07 05:11:37'),
(2, 'Instagram', '#', 'ri-instagram-line', 1, '2024-09-06 18:16:33', '2024-09-06 18:16:33'),
(3, 'YouTube', '#', 'ri-youtube-line', 1, '2024-09-06 18:16:33', '2024-09-06 18:16:33');

-- --------------------------------------------------------

--
-- Table structure for table `struktur_organisasi`
--

CREATE TABLE `struktur_organisasi` (
  `id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `struktur_organisasi`
--

INSERT INTO `struktur_organisasi` (`id`, `image_path`) VALUES
(1, 'assets/img/struktur_1725686425.jpg');

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
-- Dumping data for table `visitors`
--

INSERT INTO `visitors` (`id`, `ip_address`, `visit_time`) VALUES
(1, '::1', '2024-09-06 22:06:31'),
(2, '::1', '2024-09-06 22:06:51'),
(3, '::1', '2024-09-06 22:11:22'),
(4, '::1', '2024-09-06 22:39:41'),
(5, '::1', '2024-09-06 22:42:43'),
(6, '::1', '2024-09-07 04:18:50'),
(7, '::1', '2024-09-07 04:22:59'),
(8, '::1', '2024-09-07 04:24:37'),
(9, '::1', '2024-09-07 05:23:42'),
(10, '::1', '2024-09-07 05:25:21'),
(11, '::1', '2024-09-07 05:28:01'),
(12, '::1', '2024-09-07 05:28:36'),
(13, '::1', '2024-09-07 05:28:47'),
(14, '::1', '2024-09-07 05:29:03'),
(15, '::1', '2024-09-07 06:42:25'),
(16, '::1', '2024-09-07 06:43:45'),
(17, '::1', '2024-09-07 06:44:22'),
(18, '::1', '2024-09-07 06:49:43'),
(19, '::1', '2024-09-07 06:50:58'),
(20, '::1', '2024-09-07 10:56:45'),
(21, '::1', '2024-09-07 10:56:56'),
(22, '::1', '2024-09-07 10:57:11'),
(23, '::1', '2024-09-07 10:57:11'),
(24, '::1', '2024-09-07 10:57:26'),
(25, '::1', '2024-09-07 10:59:29'),
(26, '::1', '2024-09-07 13:29:08'),
(27, '::1', '2024-09-07 13:29:09'),
(28, '::1', '2024-09-07 13:29:23'),
(29, '::1', '2024-09-07 13:30:45'),
(30, '::1', '2024-09-07 13:30:45'),
(31, '::1', '2024-09-07 13:31:07'),
(32, '::1', '2024-09-07 13:37:08'),
(33, '::1', '2024-09-07 13:54:30'),
(34, '::1', '2024-09-07 13:57:06'),
(35, '::1', '2024-09-07 13:57:26'),
(36, '::1', '2024-09-07 14:08:16'),
(37, '::1', '2024-09-07 14:37:16'),
(38, '::1', '2024-09-07 14:37:16'),
(39, '::1', '2024-09-07 14:41:32'),
(40, '::1', '2024-09-07 15:21:52'),
(41, '::1', '2024-09-07 15:21:55'),
(42, '::1', '2024-09-07 15:23:44'),
(43, '::1', '2024-09-07 15:28:49'),
(44, '::1', '2024-09-07 15:28:59'),
(45, '::1', '2024-09-07 15:30:12'),
(46, '::1', '2024-09-07 15:52:12'),
(47, '::1', '2024-09-07 16:04:55'),
(48, '::1', '2024-09-07 16:06:18'),
(49, '::1', '2024-09-07 16:07:18'),
(50, '::1', '2024-09-07 16:26:56'),
(51, '::1', '2024-09-07 16:28:53'),
(52, '::1', '2024-09-07 16:29:01'),
(53, '::1', '2024-09-07 16:30:50'),
(54, '::1', '2024-09-07 16:30:55'),
(55, '::1', '2024-09-07 16:32:35'),
(56, '::1', '2024-09-07 16:32:37'),
(57, '::1', '2024-09-07 16:32:41'),
(58, '::1', '2024-09-07 16:32:46'),
(59, '::1', '2024-09-07 16:33:27'),
(60, '::1', '2024-09-07 16:33:31'),
(61, '::1', '2024-09-07 16:33:45'),
(62, '::1', '2024-09-07 16:34:24'),
(63, '::1', '2024-09-07 16:35:57'),
(64, '::1', '2024-09-07 16:36:14'),
(65, '::1', '2024-09-07 16:36:40'),
(66, '::1', '2024-09-07 16:36:53'),
(67, '::1', '2024-09-07 16:38:02'),
(68, '::1', '2024-09-07 16:38:32'),
(69, '::1', '2024-09-07 16:38:55'),
(70, '::1', '2024-09-07 16:39:00'),
(71, '::1', '2024-09-07 16:39:35'),
(72, '::1', '2024-09-07 16:44:23'),
(73, '::1', '2024-09-08 16:46:39'),
(74, '::1', '2024-09-08 17:00:36'),
(75, '::1', '2024-09-08 17:01:28'),
(76, '::1', '2024-09-08 17:01:32'),
(77, '::1', '2024-09-08 17:07:41'),
(78, '::1', '2024-09-08 17:08:20'),
(79, '::1', '2024-09-08 17:10:11'),
(80, '::1', '2024-09-08 17:10:15'),
(81, '::1', '2024-09-08 17:45:07'),
(82, '::1', '2024-09-08 17:49:50'),
(83, '::1', '2024-09-08 17:51:59'),
(84, '::1', '2024-09-08 17:53:26'),
(85, '::1', '2024-09-08 17:54:40'),
(86, '::1', '2024-09-08 17:56:52'),
(87, '::1', '2024-09-08 17:57:06'),
(88, '::1', '2024-09-08 17:57:08'),
(89, '::1', '2024-09-08 17:57:12'),
(90, '::1', '2024-09-08 17:58:49'),
(91, '::1', '2024-09-08 17:58:51'),
(92, '::1', '2024-09-08 17:59:52'),
(93, '::1', '2024-09-08 18:02:21'),
(94, '::1', '2024-09-08 18:04:28'),
(95, '::1', '2024-09-08 18:04:33'),
(96, '::1', '2024-09-08 18:05:48'),
(97, '::1', '2024-09-08 18:06:04'),
(98, '::1', '2024-09-08 18:06:35'),
(99, '::1', '2024-09-08 18:08:19'),
(100, '::1', '2024-09-08 18:10:05'),
(101, '::1', '2024-09-08 18:11:03'),
(102, '127.0.0.1', '2024-09-09 04:33:19'),
(103, '::1', '2024-09-09 04:56:01'),
(104, '::1', '2024-09-09 05:00:01'),
(105, '::1', '2024-09-09 05:04:40'),
(106, '::1', '2024-09-09 05:04:58'),
(107, '::1', '2024-09-09 05:08:49'),
(108, '::1', '2024-09-09 05:08:54'),
(109, '::1', '2024-09-09 05:11:22'),
(110, '::1', '2024-09-09 05:11:27'),
(111, '::1', '2024-09-09 11:01:57'),
(112, '::1', '2024-09-09 11:31:48'),
(113, '::1', '2024-09-09 11:31:56'),
(114, '::1', '2024-09-09 11:32:59'),
(115, '::1', '2024-09-09 12:14:00'),
(116, '::1', '2024-09-09 12:15:36'),
(117, '::1', '2024-09-09 12:16:15'),
(118, '::1', '2024-09-09 12:35:07'),
(119, '::1', '2024-09-09 12:35:10');

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
-- Indexes for table `contact_info`
--
ALTER TABLE `contact_info`
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
-- Indexes for table `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `social_media_links`
--
ALTER TABLE `social_media_links`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `struktur_organisasi`
--
ALTER TABLE `struktur_organisasi`
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
-- AUTO_INCREMENT for table `contact_info`
--
ALTER TABLE `contact_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `gallery`
--
ALTER TABLE `gallery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `skills`
--
ALTER TABLE `skills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `social_media_links`
--
ALTER TABLE `social_media_links`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `struktur_organisasi`
--
ALTER TABLE `struktur_organisasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `visitors`
--
ALTER TABLE `visitors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=120;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
