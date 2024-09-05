<?php
require_once('../config.php');
include('../admin/auth.php');

if (isset($_GET['image'])) {
    $image_name = $_GET['image'];
    $file_path = '../assets/img/' . $image_name;

    // Hapus gambar dari folder
    if (file_exists($file_path)) {
        unlink($file_path);

        // Hapus entri dari database
        $stmt = $pdo->prepare("DELETE FROM gallery WHERE image = :image");
        $stmt->execute(['image' => $image_name]);

        header('Location: manage_gallery.php');
        exit;
    }
}
?>
