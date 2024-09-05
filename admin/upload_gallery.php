<?php
require_once('../config.php');
include('../admin/auth.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $upload_dir = '../assets/img/';
    
    // Hitung jumlah file yang diupload, maksimal 6 file
    $total_files = count($_FILES['images']['name']);
    if ($total_files > 6) {
        echo "Maksimal hanya bisa upload 6 gambar sekaligus.";
        exit;
    }

    for ($i = 0; $i < $total_files; $i++) {
        if ($_FILES['images']['error'][$i] == 0) {
            $file_name = basename($_FILES['images']['name'][$i]);
            $target_file = $upload_dir . $file_name;

            // Pindahkan file yang diupload ke folder galeri
            if (move_uploaded_file($_FILES['images']['tmp_name'][$i], $target_file)) {
                // Simpan nama file ke database
                $stmt = $pdo->prepare("INSERT INTO gallery (image) VALUES (:image)");
                $stmt->execute(['image' => $file_name]);
            }
        }
    }

    header('Location: manage_gallery.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Gambar Galeri</title>
    <link href="../assets/css/output.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <?php include('../admin/components/navbar.php'); ?>

    <main class="container mx-auto p-6">
        <section class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Upload Gambar Galeri</h2>
            <form action="upload_gallery.php" method="POST" enctype="multipart/form-data">
                <input type="file" name="images[]" multiple class="mb-4" accept="image/*">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg">Upload Gambar</button>
                <p>Maksimal 6 gambar dapat diupload sekaligus.</p>
            </form>
        </section>
    </main>
</body>
</html>
