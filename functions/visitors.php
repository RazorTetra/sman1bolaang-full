<?php
// Fungsi untuk menyimpan pengunjung
function logVisitor($pdo)
{
    $ip_address = $_SERVER['REMOTE_ADDR'];

    // Hitung jumlah pengunjung saat ini
    $stmt = $pdo->query("SELECT COUNT(*) FROM visitors");
    $current_count = $stmt->fetchColumn();

    // Ambil total count dari tabel visitor_count
    $stmt = $pdo->query("SELECT total_count FROM visitor_count WHERE id = 1");
    $total_count = $stmt->fetchColumn();

    // Tambahkan pengunjung baru
    $stmt = $pdo->prepare("INSERT INTO visitors (ip_address) VALUES (:ip_address)");
    $stmt->execute(['ip_address' => $ip_address]);

    // Tambahkan ke total count
    $new_total_count = $total_count + 1;
    $stmt = $pdo->prepare("UPDATE visitor_count SET total_count = :total_count WHERE id = 1");
    $stmt->execute(['total_count' => $new_total_count]);

    // Jika jumlah pengunjung melebihi 70, reset tabel visitors
    if ($current_count >= 70) {
        $pdo->exec("TRUNCATE TABLE visitors");
    }
}

// Log pengunjung setiap kali halaman diakses
logVisitor($pdo);
?>
