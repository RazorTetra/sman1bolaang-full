<?php
// api/process_chat.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    require_once '../config.php'; // Pastikan path ini benar
    require_once '../api/gemini_connection.php'; // Nama file telah diubah

    // Pastikan request adalah POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Ambil pesan dari request
        $userMessage = isset($_POST['message']) ? $_POST['message'] : '';

        if (!empty($userMessage)) {
            // Gunakan fungsi processChat untuk mendapatkan respons
            $response = processChat($userMessage);
            echo $response;
        } else {
            throw new Exception("Pesan tidak boleh kosong");
        }
    } else {
        throw new Exception("Metode tidak diizinkan");
    }
} catch (Exception $e) {
    error_log('Error in process_chat.php: ' . $e->getMessage());
    http_response_code(500);
    echo "Terjadi kesalahan: " . $e->getMessage(); // Hanya untuk debugging, hapus atau komentari ini di produksi
}
?>