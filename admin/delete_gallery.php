<?php
require_once('../config.php');
include('../admin/auth.php');

// Periksa apakah ada ID gambar yang diterima
if (isset($_GET['id'])) {
    $image_id = $_GET['id'];

    // Ambil path gambar dari database
    $stmt = $pdo->prepare("SELECT image FROM gallery WHERE id = :id");
    $stmt->execute(['id' => $image_id]);
    $image = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($image) {
        // Hapus file gambar dari direktori
        $image_path = '../assets/img/' . $image['image'];
        if (file_exists($image_path)) {
            unlink($image_path);
        }

        // Hapus record gambar dari database
        $stmt = $pdo->prepare("DELETE FROM gallery WHERE id = :id");
        $stmt->execute(['id' => $image_id]);

        header('Location: manage_gallery.php');
        exit;
    } else {
        echo "Gambar tidak ditemukan!";
    }
} else {
    echo "ID gambar tidak valid!";
}
?>
