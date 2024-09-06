<?php
// Pastikan tidak ada output sebelum header JSON
error_reporting(E_ALL);
ini_set('display_errors', 0);

header('Content-Type: application/json');

// Gunakan konfigurasi yang sudah ada
include('../config.php');

// Fungsi untuk membersihkan input
function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Cek apakah form telah di-submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil dan bersihkan input
    $name = clean_input($_POST['user_name'] ?? '');
    $email = clean_input($_POST['user_email'] ?? '');
    $subject = clean_input($_POST['user_subject'] ?? '');
    $message = clean_input($_POST['user_message'] ?? '');

    // Validasi input
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        echo json_encode(['status' => 'error', 'message' => 'Semua field harus diisi.']);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => 'Format email tidak valid.']);
        exit;
    }

    if (strlen($message) < 10) {
        echo json_encode(['status' => 'error', 'message' => 'Pesan harus minimal 10 karakter.']);
        exit;
    }

    // Filter kata-kata kasar
    $bad_words = ['kntl', 'kontol']; // Tambahkan kata-kata lain sesuai kebutuhan
    $pattern = '/\b(' . implode('|', $bad_words) . ')\b/i';
    if (preg_match($pattern, $message)) {
        echo json_encode(['status' => 'error', 'message' => 'Pesan mengandung kata kasar.']);
        exit;
    }

    // SQL query
    $sql = "INSERT INTO feedback (name, email, subject, message) VALUES (:name, :email, :subject, :message)";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':subject', $subject);
        $stmt->bindParam(':message', $message);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Pesan Anda telah dikirim. Terima kasih!']);
        } else {
            error_log("Query failed: " . $stmt->queryString);
            error_log("Bind Params: Name: $name, Email: $email, Subject: $subject, Message: $message");
            echo json_encode(['status' => 'error', 'message' => 'Gagal mengirim pesan. Coba lagi.']);
        }
    } catch (PDOException $e) {
        error_log("Database Error: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Terjadi kesalahan database.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Metode permintaan tidak valid.']);
}
?>
