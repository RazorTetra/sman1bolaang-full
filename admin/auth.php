<?php
// Memulai session
session_start();

// Mengecek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    // Jika user belum login, arahkan ke halaman login
    header('Location: ../pages/loginPage.php');
    exit();
}

// Mendapatkan role dan username dari session
$role = isset($_SESSION['role']) ? $_SESSION['role'] : null;
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
?>
