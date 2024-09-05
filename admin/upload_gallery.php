<?php
require_once('../config.php');
include('../admin/auth.php'); // Mengimpor auth.php untuk pengecekan login


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image'])) {
    $gallery_dir = '../assets/img/';
    $image_name = basename($_FILES['image']['name']);
    $image_path = $gallery_dir . $image_name;
    $image_tmp_name = $_FILES['image']['tmp_name'];

    // Validasi file gambar
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    if (in_array($_FILES['image']['type'], $allowed_types)) {
        if (move_uploaded_file($image_tmp_name, $image_path)) {
            echo "Gambar berhasil diunggah!";
        } else {
            echo "Gagal mengunggah gambar.";
        }
    } else {
        echo "Jenis file tidak diperbolehkan.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Gambar Galeri</title>
    <link rel="stylesheet" href="../assets/css/admin.css"> 
    <link href="../assets/css/output.css" rel="stylesheet">
</head>

<body>
    <?php include('../admin/components/navbar.php'); ?>

    <main>
        <section>
            <h2>Unggah Gambar Baru</h2>
            <form action="upload_gallery.php" method="post" enctype="multipart/form-data">
                <input type="file" name="image" accept="image/*" required>
                <button type="submit" class="button">Unggah</button>
            </form>
        </section>
    </main>
</body>

</html>