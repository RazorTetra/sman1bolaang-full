-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 18, 2024 at 11:49 AM
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
  `youtube` varchar(255) DEFAULT NULL,
  `whatsapp` varchar(20) DEFAULT '+628123456789',
  `tabea_text` varchar(255) DEFAULT 'Tabea !'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `about_info`
--

INSERT INTO `about_info` (`id`, `description`, `name`, `image`, `facebook`, `instagram`, `youtube`, `whatsapp`, `tabea_text`) VALUES
(1, 'Dengan penuh rasa syukur dan bangga menyambut Anda semua di platform website kami yang dirancang untuk menjadi jembatan informasi yang menghubungkan kami dengan seluruh siswa, serta masyarakat luas.<br><br>Mewujudkan visi dan misi sekolah dengan penuh dedikasi. Melalui slogan kami, MATTOA (Menjadikan Aku Tangguh Terampil Optimis Amanah).<br><br>Terima kasih telah mengunjungi website kami.&nbsp;<span style=\"background-color: rgba(255,255,255,var(--tw-bg-opacity)); font-family: inherit;\">Mari bersama-sama kita wujudkan pendidikan berkualitas dan membangun masa depan yang gemilang.</span>', 'Sukur Moanto,<br><br><b>Brusly Polakitan, S.Kom, M.Pd.</b><br><br><b>Kepala Sekolah SMK Negeri 1 Bolaang</b><div><br></div><div><br></div>', 'assets/img/about-perfil-1.jpg', 'https://www.facebook.com/bpolakitan', 'https://www.instagram.com/bpolakitan', 'https://www.youtube.com/bpolakitan', '+628123456789', 'Tabea !');

-- --------------------------------------------------------

--
-- Table structure for table `api_keys`
--

CREATE TABLE `api_keys` (
  `id` int(11) NOT NULL,
  `service` varchar(50) NOT NULL,
  `api_key` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `api_keys`
--

INSERT INTO `api_keys` (`id`, `service`, `api_key`, `created_at`) VALUES
(1, 'gemini', 'AIzaSyBlfXoiyJbItNGe72seHcqq0VqwHhAZbhw', '2024-09-16 17:03:23');

-- --------------------------------------------------------

--
-- Table structure for table `api_usage`
--

CREATE TABLE `api_usage` (
  `id` int(11) NOT NULL,
  `requests_today` int(11) DEFAULT NULL,
  `last_request_time` datetime DEFAULT NULL,
  `requests_this_minute` int(11) DEFAULT NULL,
  `tokens_this_minute` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `api_usage`
--

INSERT INTO `api_usage` (`id`, `requests_today`, `last_request_time`, `requests_this_minute`, `tokens_this_minute`) VALUES
(1, 1, '2024-09-16 17:12:50', 1, 145),
(2, 2, '2024-09-16 17:15:57', 1, 127),
(3, 3, '2024-09-16 17:16:22', 2, 275);

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
-- Table structure for table `base_knowledge`
--

CREATE TABLE `base_knowledge` (
  `id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `base_knowledge`
--

INSERT INTO `base_knowledge` (`id`, `content`, `created_at`, `updated_at`) VALUES
(1, 'Anda adalah asisten AI untuk SMKN 1 Bolaang, Sulawesi Utara, Indonesia. Informasi hingga 2024:\r\n\r\n1. SMKN 1 Bolaang:\r\n   - Lokasi: Jl. AKD Mopuya Selatan, Bolaang, Sulawesi Utara\r\n   - Visi: Menghasilkan SDM kompeten, mandiri, berakhlak mulia\r\n   - Misi: Pendidikan berkualitas, kewirausahaan, profesionalisme, nilai karakter\r\n   - Program Keahlian: [Isi dengan program keahlian SMKN 1 Bolaang]\r\n   - Fasilitas: [Sebutkan fasilitas utama sekolah]\r\n   - Prestasi: [Sebutkan beberapa prestasi penting sekolah]\r\n\r\n2. Pendidikan Sulawesi Utara:\r\n   - Struktur pendidikan dasar dan menengah\r\n   - Kebijakan vokasi provinsi\r\n   - Program unggulan\r\n\r\n3. SMK dan SMP di Sulawesi Utara:\r\n   - Daftar utama SMK dan SMP negeri\r\n   - Perbandingan umum SMK dan SMA\r\n   - Tantangan dan peluang pendidikan menengah\r\n\r\n4. Kehidupan Siswa:\r\n   - Kegiatan ekstrakurikuler umum\r\n   - Persiapan Ujian Nasional dan ujian masuk PT\r\n   - Program beasiswa tersedia\r\n\r\n5. Perguruan Tinggi Indonesia:\r\n   - Sistem pendidikan tinggi\r\n   - Daftar PTN/PTS terkemuka\r\n   - Jalur masuk umum\r\n\r\n6. Bolaang Mongondow dan Sulawesi Utara:\r\n   - Geografi dan iklim\r\n   - Budaya dan bahasa daerah\r\n   - Potensi ekonomi dan industri\r\n   - Objek wisata utama\r\n\r\nBatasi informasi pada topik-topik di atas dan wilayah Sulawesi Utara.', '2024-09-16 19:17:09', '2024-09-16 19:21:37');

-- --------------------------------------------------------

--
-- Table structure for table `beranda`
--

CREATE TABLE `beranda` (
  `id` int(11) NOT NULL,
  `youtube_link` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `beranda`
--

INSERT INTO `beranda` (`id`, `youtube_link`, `title`, `description`, `created_at`, `updated_at`) VALUES
(1, 'https://www.youtube.com/embed/p48DjKj-JyI?si=c-O2boxorz6np0Vr', 'SMKN 1 Bolaang', 'MATTOA SmeckONEBol\n\" Menjadikan Aku Tangguh Terampil \n Optimis Amanah \"', '2024-09-16 23:03:56', '2024-09-16 23:03:56');

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
-- Table structure for table `custom_knowledge`
--

CREATE TABLE `custom_knowledge` (
  `id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `custom_knowledge`
--

INSERT INTO `custom_knowledge` (`id`, `content`, `created_at`, `updated_at`) VALUES
(1, '-', '2024-09-16 19:19:24', '2024-09-16 19:19:24');

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
(16, 'berita-1 - Copy.jpg', '2024-09-05 10:13:48', 1, 0),
(17, 'berita-2 - Copy.jpg', '2024-09-05 10:13:48', 1, 0),
(18, 'berita-3 - Copy.jpg', '2024-09-05 10:13:48', 1, 0),
(19, 'berita-4 - Copy.jpg', '2024-09-05 10:13:48', 1, 0),
(20, 'berita-5 - Copy.jpg', '2024-09-05 10:13:48', 1, 0),
(21, 'berita-6 - Copy.jpg', '2024-09-05 10:13:48', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `profil_staff`
--

CREATE TABLE `profil_staff` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `jabatan` varchar(255) NOT NULL,
  `riwayat_pendidikan` text DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `mata_pelajaran` varchar(255) DEFAULT NULL,
  `lama_mengajar` int(11) DEFAULT NULL,
  `pangkat` varchar(255) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `motto` text DEFAULT NULL,
  `lokasi_foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `profil_staff`
--

INSERT INTO `profil_staff` (`id`, `nama`, `jabatan`, `riwayat_pendidikan`, `status`, `mata_pelajaran`, `lama_mengajar`, `pangkat`, `alamat`, `motto`, `lokasi_foto`) VALUES
(13, 'Brusly P. Polakitan, S.Kom, M.Pd', 'Kepala Sekolah', 'S1. Teknik Informatika\r\nS2. Manajemen Pendidikan \r\n', 'PNS Daerah Prov. Sulut ', 'Informatika / TKJ', 14, 'Penata Tkt. I / IIID ', 'Kinilow Satu Kec. Tomohon Utara', 'Knowing Is Not Enough, But Playing Is Everything', 'staff_1726026331.png'),
(14, 'Siti Saraswati Tegela, S.Pd', 'Wakil Kepala Sekolah Bidang Akademik ', 'S1. Matematika ', 'PPPK Daerah Prov. Sulut ', 'Matematika', 4, '-', '-', '-', 'staff_1726028352.jpg'),
(15, 'Andini Mamonto, S.Pd', 'Wakil Kepala Sekolah Bidang Sarana Prasarana ', 'S1. Bahasa Indonesia ', 'PPPK Daerah Prov. Sulut ', 'Matematika', 4, '-', '-', '-', 'staff_1726028553.jpg'),
(16, 'Fitra Sugeha, S.P', 'Wakil Kepala Sekolah Bidang Hubmas HKI ', 'S1. Pertanian ', 'PPPK Daerah Prov. Sulut ', 'Pertanian', 4, '-', '-', '-', 'staff_1726028596.jpg'),
(17, 'Risnawati Tunggali, S.Pd', 'Bendahara', 'S1. Sejarah ', 'PPPK Daerah Prov. Sulut ', 'Sejarah ', 4, '-', '-', '-', 'staff_1726028632.jpg');

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
(1, 'Bisnis Marketing', 'ri-shake-hands-line', '', 'Mempelajari teknik pemasaran, strategi penjualan, serta manajemen keuangan dan akuntansi.'),
(2, 'Desain Komunikasi Visual', 'ri-pen-nib-line', NULL, 'Mempelajari prinsip desain grafis, komunikasi visual, dan multimedia, serta teknik-teknik kreatif untuk merancang materi promosi, iklan, branding, dan media digital.'),
(3, 'Agribisinis & Tanaman Pangan Holtikultura', 'ri-plant-line', NULL, 'Mempelajari teknik-teknik modern dalam budidaya tanaman, manajemen usaha pertanian, serta teknologi terbaru dalam pengolahan dan pemasaran hasil pertanian.'),
(4, 'Kuliner', 'ri-cake-3-line', NULL, 'Mempersiapkan siswa untuk berkarir di industri makanan dengan mengajarkan teknik memasak, pengembangan resep, dan estetika penyajian makanan.'),
(5, 'Asisten Keperawatan', 'ri-hospital-line', NULL, 'Mempersiapkan siswa untuk mendukung tenaga medis dalam perawatan pasien. Siswa mempelajari teknik dasar perawatan kesehatan, termasuk pengukuran tanda vital, pemberian obat, dan perawatan luka.'),
(6, 'Teknik Sepeda Motor', 'ri-e-bike-2-line', NULL, 'Mempelajari teknik-teknik dasar dan lanjutan dalam diagnosis, servis mesin, sistem kelistrikan, dan sistem transmisi.'),
(8, 'Belajar Bersama Guru 4', 'ri-message-2-line', '', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&amp;#039;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.');

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
(2, 'Instagram', 'https://instagram.com', 'ri-instagram-line', 1, '2024-09-06 18:16:33', '2024-09-16 23:08:04'),
(3, 'YouTube', 'https://youtube.com', 'ri-youtube-line', 1, '2024-09-06 18:16:33', '2024-09-16 23:08:31');

-- --------------------------------------------------------

--
-- Table structure for table `struktur_organisasi`
--

CREATE TABLE `struktur_organisasi` (
  `id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `tanggal_upload` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `struktur_organisasi`
--

INSERT INTO `struktur_organisasi` (`id`, `judul`, `image_path`, `tanggal_upload`) VALUES
(12, 'struktur organisasi', 'assets/img/struktur_12_1726645286.jpg', '2024-09-18 07:41:26'),
(13, 'struktur organisasi', 'assets/img/struktur_13_1726651415.jpg', '2024-09-18 09:23:35');

-- --------------------------------------------------------

--
-- Table structure for table `tupoksi_staff`
--

CREATE TABLE `tupoksi_staff` (
  `id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `tanggal_upload` timestamp NOT NULL DEFAULT current_timestamp(),
  `google_drive_link` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tupoksi_staff`
--

INSERT INTO `tupoksi_staff` (`id`, `judul`, `deskripsi`, `tanggal_upload`, `google_drive_link`) VALUES
(65, 'asfsdf', NULL, '2024-09-18 09:01:28', 'https://drive.google.com/file/d/1RY5uzeCGuer6PrzEz04-jar8x9nD0kHE/view?usp=sharing');

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
(2, 'super', '$2y$10$cz2tuRYk8BqaGOSqaLNkBOWgY5uDqJ1b53REz7gpjcKGF8ELwir2.', 'super@gmail.com', 'admin', 0, '2024-09-18 06:36:40');

-- --------------------------------------------------------

--
-- Table structure for table `visitors`
--

CREATE TABLE `visitors` (
  `id` int(11) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `visit_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `visitor_count`
--

CREATE TABLE `visitor_count` (
  `id` int(11) NOT NULL,
  `total_count` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `visitor_count`
--

INSERT INTO `visitor_count` (`id`, `total_count`) VALUES
(1, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `about_info`
--
ALTER TABLE `about_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `api_keys`
--
ALTER TABLE `api_keys`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `service` (`service`),
  ADD UNIQUE KEY `service_2` (`service`),
  ADD UNIQUE KEY `service_3` (`service`),
  ADD UNIQUE KEY `service_4` (`service`),
  ADD UNIQUE KEY `service_5` (`service`);

--
-- Indexes for table `api_usage`
--
ALTER TABLE `api_usage`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `base_knowledge`
--
ALTER TABLE `base_knowledge`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `beranda`
--
ALTER TABLE `beranda`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_info`
--
ALTER TABLE `contact_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `custom_knowledge`
--
ALTER TABLE `custom_knowledge`
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
-- Indexes for table `profil_staff`
--
ALTER TABLE `profil_staff`
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
-- Indexes for table `tupoksi_staff`
--
ALTER TABLE `tupoksi_staff`
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
-- Indexes for table `visitor_count`
--
ALTER TABLE `visitor_count`
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
-- AUTO_INCREMENT for table `api_keys`
--
ALTER TABLE `api_keys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `api_usage`
--
ALTER TABLE `api_usage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `base_knowledge`
--
ALTER TABLE `base_knowledge`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `beranda`
--
ALTER TABLE `beranda`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `contact_info`
--
ALTER TABLE `contact_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `custom_knowledge`
--
ALTER TABLE `custom_knowledge`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
-- AUTO_INCREMENT for table `profil_staff`
--
ALTER TABLE `profil_staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tupoksi_staff`
--
ALTER TABLE `tupoksi_staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `visitors`
--
ALTER TABLE `visitors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `visitor_count`
--
ALTER TABLE `visitor_count`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
