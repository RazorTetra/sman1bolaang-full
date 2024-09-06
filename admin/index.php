<?php
include('../admin/auth.php'); // Mengimpor auth.php untuk pengecekan login
require('../config.php'); // Mengimpor konfigurasi koneksi database

// Mendapatkan username jika ada
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Admin';

// Fungsi untuk menghitung jumlah baris dalam tabel
function getCount($pdo, $table)
{
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM $table");
    $stmt->execute();
    return $stmt->fetchColumn();
}

// Fungsi untuk menghitung jumlah pengunjung unik
function getUniqueVisitorsCount($pdo)
{
    $stmt = $pdo->prepare("SELECT COUNT(DISTINCT ip_address) FROM visitors");
    $stmt->execute();
    return $stmt->fetchColumn();
}

// Menggunakan fungsi untuk mendapatkan jumlah berita, gambar, dan pengunjung
try {
    $total_articles = getCount($pdo, 'articles');
    $total_images = getCount($pdo, 'gallery'); // Ganti 'gallery' dengan nama tabel gambar Anda
    $unique_visitors = getUniqueVisitorsCount($pdo);
} catch (PDOException $e) {
    die('Error: ' . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="../assets/css/output.css" rel="stylesheet">
    <!--=============== FAVICON ===============-->
    <link rel="shortcut icon" href="../assets/img/logo-smk.png" type="image/x-icon">
</head>

<body class="bg-gray-100 font-sans">
    <?php include('../admin/components/navbar.php'); ?>

    <main class="p-6 max-w-4xl mx-auto">
        <h1 class="text-2xl font-semibold text-gray-800 mb-4">Selamat datang, <?php echo htmlspecialchars($username); ?>!</h1>
        <p class="text-gray-600">Pilih menu di atas untuk mengelola berita atau galeri.</p>

        <!-- Dashboard Info -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-6">
            <!-- Jumlah Berita -->
            <div class="bg-white shadow rounded-lg p-4">
                <h2 class="text-lg font-semibold text-gray-800">Jumlah Berita</h2>
                <p class="text-gray-600 text-2xl"><?php echo $total_articles; ?> Berita</p>
            </div>

            <!-- Jumlah Gambar di Galeri -->
            <div class="bg-white shadow rounded-lg p-4">
                <h2 class="text-lg font-semibold text-gray-800">Jumlah Gambar di Galeri</h2>
                <p class="text-gray-600 text-2xl"><?php echo $total_images; ?> Gambar</p>
            </div>

            <!-- Jumlah Pengunjung Unik -->
            <div class="bg-white shadow rounded-lg p-4">
                <h2 class="text-lg font-semibold text-gray-800">Jumlah Pengunjung Unik</h2>
                <p class="text-gray-600 text-2xl"><?php echo $unique_visitors; ?> Pengunjung</p>
            </div>
        </div>
    </main>
</body>

</html>
