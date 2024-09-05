<?php
require_once('../config.php');
include('../admin/auth.php'); // Mengimpor auth.php untuk pengecekan login
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Galeri</title>
    <link href="../assets/css/output.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <?php include('../admin/components/navbar.php'); ?>

    <main class="container mx-auto p-6">
        <section class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Daftar Gambar Galeri</h2>
                <a href="upload_gallery.php" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg">Tambah Gambar</a>
            </div>

            <!-- Grid responsive untuk galeri -->
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
                <?php
                $gallery_dir = '../assets/img/';
                $images = glob($gallery_dir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);

                foreach ($images as $image) {
                    $image_name = basename($image);
                    echo "
                    <div class='relative group'>
                        <img src='../assets/img/$image_name' alt='$image_name' class='w-full h-48 object-cover rounded-lg shadow-md'>
                        <div class='absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300'>
                            <a href='delete_image.php?image=$image_name' class='bg-red-500 text-white py-2 px-4 rounded-lg hover:bg-red-600' onclick=\"return confirm('Yakin ingin menghapus gambar ini?')\">Hapus</a>
                        </div>
                    </div>";
                }
                ?>
            </div>
        </section>
    </main>
</body>

</html>