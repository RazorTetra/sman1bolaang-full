<?php
// Fungsi untuk menyimpan pengunjung
function logVisitor($pdo)
{
    $ip_address = $_SERVER['REMOTE_ADDR']; // Mendapatkan alamat IP pengunjung
    $stmt = $pdo->prepare("INSERT INTO visitors (ip_address) VALUES (:ip_address)");
    $stmt->execute(['ip_address' => $ip_address]);
}

// Log pengunjung setiap kali halaman diakses
logVisitor($pdo);
?>
