<?php
include('config.php');

// Coba lakukan koneksi ke database
if ($pdo) {
    echo "Koneksi ke database berhasil!";
} else {
    echo "Gagal terhubung ke database: " . mysqli_connect_error();
}
?>
