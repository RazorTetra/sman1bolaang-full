<?php
// api/gemini_connection.php

// Fungsi untuk mendapatkan API key dari database
function getGeminiKey() {
    global $pdo;
    $stmt = $pdo->query("SELECT api_key FROM api_keys WHERE service = 'gemini' ORDER BY id DESC LIMIT 1");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ? $result['api_key'] : null;
}

// Kelas untuk melacak penggunaan API
class UsageTracker {
    private $pdo;
    private $requests_today;
    private $last_request_time;
    private $requests_this_minute;
    private $tokens_this_minute;

    const MAX_RPM = 15;
    const MAX_TPM = 32000;
    const MAX_RPD = 1500;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->loadUsage();
    }

    private function loadUsage() {
        $stmt = $this->pdo->query("SELECT * FROM api_usage ORDER BY id DESC LIMIT 1");
        $usage = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($usage) {
            $this->requests_today = $usage['requests_today'];
            $this->last_request_time = strtotime($usage['last_request_time']);
            $this->requests_this_minute = $usage['requests_this_minute'];
            $this->tokens_this_minute = $usage['tokens_this_minute'];
        } else {
            $this->requests_today = 0;
            $this->last_request_time = 0;
            $this->requests_this_minute = 0;
            $this->tokens_this_minute = 0;
        }
    }

    public function canMakeRequest() {
        $current_time = time();
        
        // Reset counters jika sudah hari baru
        if (date('Y-m-d', $current_time) != date('Y-m-d', $this->last_request_time)) {
            $this->requests_today = 0;
            $this->requests_this_minute = 0;
            $this->tokens_this_minute = 0;
        }

        // Reset counters per menit
        if ($current_time - $this->last_request_time >= 60) {
            $this->requests_this_minute = 0;
            $this->tokens_this_minute = 0;
        }

        return ($this->requests_today < self::MAX_RPD &&
                $this->requests_this_minute < self::MAX_RPM &&
                $this->tokens_this_minute < self::MAX_TPM);
    }

    public function updateUsage($tokens_estimate) {
        $this->requests_today++;
        $this->requests_this_minute++;
        $this->tokens_this_minute += $tokens_estimate;
        $this->last_request_time = time();

        $stmt = $this->pdo->prepare("INSERT INTO api_usage (requests_today, last_request_time, requests_this_minute, tokens_this_minute) VALUES (?, ?, ?, ?)");
        $stmt->execute([$this->requests_today, date('Y-m-d H:i:s'), $this->requests_this_minute, $this->tokens_this_minute]);
    }
}

// Fungsi untuk melakukan request ke Gemini API
function chatWithGemini($message) {
    global $pdo;
    $api_key = getGeminiKey();
    if (!$api_key) {
        throw new Exception("Gemini API key not found");
    }

    $usage_tracker = new UsageTracker($pdo);
    if (!$usage_tracker->canMakeRequest()) {
        throw new Exception("Usage limit reached. Please try again later.");
    }

    $url = 'https://generativelanguage.googleapis.com/v1/models/gemini-1.0-pro:generateContent?key=' . $api_key;
    $headers = ['Content-Type: application/json'];

    $data = [
        'contents' => [
            [
                'role' => 'user',
                'parts' => [['text' => $message]]
            ]
        ]
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        throw new Exception('Curl error: ' . curl_error($ch));
    }

    curl_close($ch);

    $result = json_decode($response, true);

    if (isset($result['error'])) {
        throw new Exception('API Error: ' . $result['error']['message']);
    }

    $ai_response = $result['candidates'][0]['content']['parts'][0]['text'];
    $tokens_estimate = (strlen($message) + strlen($ai_response)) / 4; // Estimasi kasar
    $usage_tracker->updateUsage($tokens_estimate);

    return $ai_response;
}

// Fungsi untuk memproses chat
function processChat($userMessage) {
    global $pdo;
    $baseKnowledge = $pdo->query("SELECT content FROM base_knowledge ORDER BY id DESC LIMIT 1")->fetchColumn();
    $customKnowledge = $pdo->query("SELECT content FROM custom_knowledge ORDER BY id DESC LIMIT 1")->fetchColumn();

    $fullMessage = $baseKnowledge . " " . $customKnowledge . "\n\nUser: " . $userMessage;

    try {
        $response = chatWithGemini($fullMessage);
        return $response;
    } catch (Exception $e) {
        error_log($e->getMessage());
        return "Maaf, terjadi kesalahan dalam memproses permintaan Anda.";
    }
}