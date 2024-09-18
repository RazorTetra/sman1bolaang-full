<?php
// api/process_chat.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    require_once '../config.php';
    require_once '../api/gemini_connection.php';
    // Jika Anda membuat file baru untuk fungsi formatAIResponse, include di sini
    // require_once '../helpers.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $userMessage = isset($_POST['message']) ? $_POST['message'] : '';

        if (!empty($userMessage)) {
            $response = processChat($userMessage);
            
            // Log respon asli untuk debugging
            error_log("Original AI response: " . $response);
            
            // Format respon AI
            $formattedResponse = formatAIResponse($response);
            
            // Log respon yang telah diformat untuk debugging
            error_log("Formatted AI response: " . $formattedResponse);
            
            // Kirim respon yang telah diformat
            echo $formattedResponse;
        } else {
            throw new Exception("Pesan tidak boleh kosong");
        }
    } else {
        throw new Exception("Metode tidak diizinkan");
    }
} catch (Exception $e) {
    error_log('Error in process_chat.php: ' . $e->getMessage());
    http_response_code(500);
    echo "Terjadi kesalahan: " . $e->getMessage();
}
?>