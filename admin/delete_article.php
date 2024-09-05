<?php
require_once('../config.php');
include '../admin/auth.php';

// Periksa apakah user memiliki role admin
if ($role !== 'admin') {
    header("Location: ../pages/loginPage.php");
    exit();
}

$id = isset($_GET['id']) ? $_GET['id'] : null;

if (!$id) {
    header("Location: manage_news.php");
    exit();
}

try {
    // Ambil nama file gambar sebelum menghapus artikel
    $stmt = $pdo->prepare("SELECT image FROM articles WHERE id = ?");
    $stmt->execute([$id]);
    $article = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Hapus artikel dari database
    $stmt = $pdo->prepare("DELETE FROM articles WHERE id = ?");
    $stmt->execute([$id]);
    
    // Jika ada gambar terkait, hapus file gambar
    if ($article && $article['image']) {
        $image_path = $_SERVER['DOCUMENT_ROOT'] . '/assets/img/' . $article['image'];
        if (file_exists($image_path)) {
            unlink($image_path);
        }
    }
    
    $_SESSION['success_message'] = "Artikel berhasil dihapus.";
} catch (PDOException $e) {
    $_SESSION['error_message'] = "Terjadi kesalahan: " . $e->getMessage();
}

header("Location: manage_news.php");
exit();
?>