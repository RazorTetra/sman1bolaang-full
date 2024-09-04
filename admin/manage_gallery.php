<?php
session_start();
require_once('../config.php');

// Periksa apakah user sudah login dan memiliki role admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../loginPage.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Galeri</title>
    <link rel="stylesheet" href="../assets/css/admin.css"> <!-- Ganti dengan path CSS Anda -->
</head>

<body>
    <?php include('../admin/components/navbar.php'); ?>


    <main>
        <section>
            <h2>Daftar Gambar Galeri</h2>
            <a href="upload_gallery.php" class="button">Tambah Gambar</a>
            <div class="gallery-container">
                <?php
                $gallery_dir = '../assets/img/';
                $images = glob($gallery_dir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);

                foreach ($images as $image) {
                    $image_name = basename($image);
                    echo "<div class='gallery-item'>
                            <img src='../assets/img/$image_name' alt='$image_name'>
                            <a href='delete_image.php?image=$image_name' onclick=\"return confirm('Yakin ingin menghapus gambar ini?')\">Hapus</a>
                          </div>";
                }
                ?>
            </div>
        </section>
    </main>
</body>

</html>