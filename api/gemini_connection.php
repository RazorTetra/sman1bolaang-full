<?php
// api/gemini_connection.php

// Function to get API key from database
function getGeminiKey()
{
    global $pdo;
    $stmt = $pdo->query("SELECT api_key FROM api_keys WHERE service = 'gemini' ORDER BY id DESC LIMIT 1");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ? $result['api_key'] : null;
}

function formatAIResponse($response) {
    // Convert ** to <strong> tags
    $response = preg_replace('/\*\*(.*?)\*\*/s', '<strong>$1</strong>', $response);
    
    // Convert lines starting with * to list items
    $response = preg_replace('/^\* (.*?)$/m', '<li>$1</li>', $response);
    
    // Wrap consecutive list items with <ul> tags
    $response = preg_replace('/(<li>.*?<\/li>(\s*)?)+/s', '<ul>$0</ul>', $response);
    
    // Convert empty lines to new paragraphs
    $response = '<p>' . preg_replace('/\n\s*\n/', '</p><p>', $response) . '</p>';
    
    // Wrap the entire response in a div with class for styling
    $response = '<div class="ai-response">' . $response . '</div>';
    
    return $response;
}

// Class to track API usage
class UsageTracker
{
    private $pdo;
    private $requests_today;
    private $last_request_time;
    private $requests_this_minute;
    private $tokens_this_minute;

    // Updated rate limits for Gemini 1.5 Flash
    const MAX_RPM = 15;  // Requests per minute
    const MAX_TPM = 1000000;  // Tokens per minute (1 million)
    const MAX_RPD = 1500;  // Requests per day

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
        $this->loadUsage();
    }

    private function loadUsage()
    {
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

    public function canMakeRequest()
    {
        $current_time = time();

        // Reset counters if it's a new day
        if (date('Y-m-d', $current_time) != date('Y-m-d', $this->last_request_time)) {
            $this->requests_today = 0;
            $this->requests_this_minute = 0;
            $this->tokens_this_minute = 0;
        }

        // Reset per-minute counters
        if ($current_time - $this->last_request_time >= 60) {
            $this->requests_this_minute = 0;
            $this->tokens_this_minute = 0;
        }

        return ($this->requests_today < self::MAX_RPD &&
            $this->requests_this_minute < self::MAX_RPM &&
            $this->tokens_this_minute < self::MAX_TPM);
    }

    public function updateUsage($tokens_estimate)
    {
        $this->requests_today++;
        $this->requests_this_minute++;
        $this->tokens_this_minute += $tokens_estimate;
        $this->last_request_time = time();

        $stmt = $this->pdo->prepare("INSERT INTO api_usage (requests_today, last_request_time, requests_this_minute, tokens_this_minute) VALUES (?, ?, ?, ?)");
        $stmt->execute([$this->requests_today, date('Y-m-d H:i:s'), $this->requests_this_minute, $this->tokens_this_minute]);
    }
}

// Function to make requests to Gemini API
function chatWithGemini($message)
{
    global $pdo;
    $api_key = getGeminiKey();
    if (!$api_key) {
        throw new Exception("Gemini API key not found");
    }

    $usage_tracker = new UsageTracker($pdo);
    if (!$usage_tracker->canMakeRequest()) {
        throw new Exception("Usage limit reached. Please try again later.");
    }

    // Updated to use Gemini 1.5 Flash
    $url = 'https://generativelanguage.googleapis.com/v1/models/gemini-1.5-flash:generateContent?key=' . $api_key;
    $headers = ['Content-Type: application/json'];

    $data = [
        'contents' => [
            [
                'role' => 'user',
                'parts' => [['text' => $message]]
            ]
        ],
        'generationConfig' => [
            'temperature' => 0.9,
            'topK' => 1,
            'topP' => 1,
            'maxOutputTokens' => 8192,
        ],
        'safetySettings' => [
            [
                'category' => 'HARM_CATEGORY_HARASSMENT',
                'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
            ],
            [
                'category' => 'HARM_CATEGORY_HATE_SPEECH',
                'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
            ],
            [
                'category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT',
                'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
            ],
            [
                'category' => 'HARM_CATEGORY_DANGEROUS_CONTENT',
                'threshold' => 'BLOCK_MEDIUM_AND_ABOVE'
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
    $tokens_estimate = (strlen($message) + strlen($ai_response)) / 4; // Rough estimation
    $usage_tracker->updateUsage($tokens_estimate);

    return $ai_response;
}

// Function to process chat
function processChat($userMessage)
{
    global $pdo;
    $baseKnowledge = $pdo->query("SELECT content FROM base_knowledge ORDER BY id DESC LIMIT 1")->fetchColumn();
    $customKnowledge = $pdo->query("SELECT content FROM custom_knowledge ORDER BY id DESC LIMIT 1")->fetchColumn();

    $fullMessage = $baseKnowledge . " " . $customKnowledge . "\n\nUser: " . $userMessage;

    try {
        $response = chatWithGemini($fullMessage);
        return $response;
    } catch (Exception $e) {
        error_log($e->getMessage());
        return "Sorry, there was an error processing your request.";
    }
}