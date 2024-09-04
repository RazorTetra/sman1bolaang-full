<?php
include('../config.php');
session_start();

// Mengecek apakah user sudah login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../functions/loginPage.php');
    exit();
}

// Mendapatkan username jika ada
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Admin';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>

<body>
    <?php include('../admin/components/navbar.php'); ?>

    <main>
        <p>Selamat datang, <?php echo htmlspecialchars($username); ?>!</p>
        <p>Pilih menu di atas untuk mengelola berita atau galeri.</p>
    </main>
</body>

</html>