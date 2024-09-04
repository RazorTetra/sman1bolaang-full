<?php
session_start();
require_once('../config.php');

// Periksa apakah user sudah login dan memiliki role admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../loginPage.php");
    exit();
}

// Periksa apakah parameter 'image' ada di URL
if (isset($_GET['image'])) {
    $image_name = basename($_GET['image']); // Ambil nama gambar dari parameter URL
    $gallery_dir = '../assets/img/';
    $image_path = $gallery_dir . $image_name;

    // Periksa apakah file gambar ada
    if (file_exists($image_path)) {
        // Hapus file gambar
        unlink($image_path);
        header("Location: manage_gallery.php"); // Redirect kembali ke halaman galeri setelah penghapusan
        exit();
    } else {
        echo "Gambar tidak ditemukan!";
    }
} else {
    echo "Parameter gambar tidak ditemukan!";
}
?>
