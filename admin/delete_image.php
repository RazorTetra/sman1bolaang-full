<?php
require_once('../config.php');
include('../admin/auth.php'); // Mengimpor auth.php untuk pengecekan login


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
