<?php
include('../admin/auth.php'); 
include('../config.php');

// Fungsi untuk mendapatkan nilai dari database
function getValue($table)
{
    global $pdo;
    if ($table === 'api_keys') {
        $stmt = $pdo->query("SELECT api_key FROM $table WHERE service = 'gemini' LIMIT 1");
    } else {
        $stmt = $pdo->query("SELECT content FROM $table ORDER BY id DESC LIMIT 1");
    }
    return $stmt->fetchColumn();
}

// Fungsi untuk memperbarui nilai di database
function updateValue($table, $content)
{
    global $pdo;
    if ($table === 'api_keys') {
        $stmt = $pdo->prepare("
            INSERT INTO $table (service, api_key) 
            VALUES ('gemini', :content) 
            ON DUPLICATE KEY UPDATE api_key = :content
        ");
    } else {
        // Cek apakah sudah ada data
        $checkStmt = $pdo->query("SELECT COUNT(*) FROM $table");
        $count = $checkStmt->fetchColumn();

        if ($count > 0) {
            // Update data yang ada
            $stmt = $pdo->prepare("UPDATE $table SET content = :content ORDER BY id DESC LIMIT 1");
        } else {
            // Insert data baru jika belum ada
            $stmt = $pdo->prepare("INSERT INTO $table (content) VALUES (:content)");
        }
    }
    return $stmt->execute(['content' => $content]);
}

// Inisialisasi variabel pesan
$updateMessage = '';

// Proses form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_api_key'])) {
        if (updateValue('api_keys', $_POST['api_key'])) {
            $updateMessage = "API Key berhasil diperbarui.";
        }
    } elseif (isset($_POST['update_base_knowledge'])) {
        if (updateValue('base_knowledge', $_POST['base_knowledge'])) {
            $updateMessage = "Base Knowledge berhasil diperbarui.";
        }
    } elseif (isset($_POST['update_custom_knowledge'])) {
        if (updateValue('custom_knowledge', $_POST['custom_knowledge'])) {
            $updateMessage = "Custom Knowledge berhasil diperbarui.";
        }
    }
    
    // Simpan pesan dalam session
    $_SESSION['update_message'] = $updateMessage;
    
    // Redirect untuk menghindari pengiriman ulang form
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Ambil pesan dari session jika ada
if (isset($_SESSION['update_message'])) {
    $updateMessage = $_SESSION['update_message'];
    unset($_SESSION['update_message']); // Hapus pesan dari session
}

// Ambil nilai-nilai dari database
$apiKey = getValue('api_keys');
$baseKnowledge = getValue('base_knowledge');
$customKnowledge = getValue('custom_knowledge');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola AI - Admin Panel</title>
    <link href="../assets/css/output.css" rel="stylesheet">
    <style>
        .bg-primary { background-color: #3490dc; }
        .bg-secondary { background-color: #f6f9fc; }
        .text-primary { color: #3490dc; }
        .border-primary { border-color: #3490dc; }
        .hover\:bg-primary-dark:hover { background-color: #2779bd; }
        .warning-box {
            background-color: #fff3cd;
            border: 1px solid #ffeeba;
            color: #856404;
            padding: 1rem;
            border-radius: 0.25rem;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body class="bg-secondary">
    <?php include('../admin/components/navbar.php'); ?>

    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold text-center text-primary">AI Chatbot</h1>
        <h3 class="text-xl text-center text-gray-400">Powered by Google Gemini 1.0 Pro</h3>
        <p class="text-center mb-8 text-gray-400">Visit: <a class="text-primary" href="https://ai.google.dev/" target="_blank">the website</a></p>

        <?php if (!empty($updateMessage)): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                <p><?php echo $updateMessage; ?></p>
            </div>
        <?php endif; ?>

        <div class="warning-box">
            <h3 class="font-bold mb-2">Peringatan Keamanan:</h3>
            <ul class="list-disc pl-5">
                <li>Jangan pernah memasukkan informasi kredensial atau data pribadi sensitif ke dalam sistem ini.</li>
                <li>Pastikan untuk menggunakan API key yang aman dan tidak membagikannya kepada siapapun.</li>
                <li>Informasi yang dimasukkan di sini akan digunakan oleh AI untuk berinteraksi dengan pengguna.</li>
            </ul>
        </div>

        <div class="grid lg:grid-cols-2 gap-8">
            <!-- API Key Section -->
            <div class="bg-white p-6 rounded-lg shadow-md border border-primary">
                <h2 class="text-xl font-semibold mb-4 text-primary">API Key Gemini</h2>
                <form method="POST" class="space-y-4">
                    <div>
                        <label for="api_key" class="block text-sm font-medium text-gray-700">API Key</label>
                        <input type="text" id="api_key" name="api_key" value="<?php echo htmlspecialchars($apiKey); ?>" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                    </div>
                    <button type="submit" name="update_api_key"
                        class="w-full bg-primary hover:bg-primary-dark text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-300">
                        Update API Key
                    </button>
                </form>
            </div>

            <!-- Base Knowledge Section -->
            <div class="bg-white p-6 rounded-lg shadow-md border border-primary">
                <h2 class="text-xl font-semibold mb-4 text-primary">Base Knowledge</h2>
                <p class="text-sm text-gray-600 mb-2">Masukkan informasi dasar tentang sekolah, sistem pendidikan, dan daerah sekitar.</p>
                <form method="POST" class="space-y-4">
                    <div>
                        <label for="base_knowledge" class="block text-sm font-medium text-gray-700">Content (max 3000 characters)</label>
                        <textarea id="base_knowledge" name="base_knowledge" rows="8" maxlength="3000" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"><?php echo htmlspecialchars($baseKnowledge); ?></textarea>
                        <p id="base_knowledge_count" class="text-sm text-gray-500 mt-1">0 / 3000 characters</p>
                    </div>
                    <button type="submit" name="update_base_knowledge"
                        class="w-full bg-primary hover:bg-primary-dark text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-300">
                        Update Base Knowledge
                    </button>
                </form>
            </div>

            <!-- Custom Knowledge Section -->
            <div class="bg-white p-6 rounded-lg shadow-md border border-primary lg:col-span-2">
                <h2 class="text-xl font-semibold mb-4 text-primary">Custom Knowledge</h2>
                <p class="text-sm text-gray-600 mb-2">Tambahkan informasi khusus atau petunjuk tambahan untuk AI.</p>
                <form method="POST" class="space-y-4">
                    <div>
                        <label for="custom_knowledge" class="block text-sm font-medium text-gray-700">Content (max 2000 characters)</label>
                        <textarea id="custom_knowledge" name="custom_knowledge" rows="8" maxlength="2000" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"><?php echo htmlspecialchars($customKnowledge); ?></textarea>
                        <p id="custom_knowledge_count" class="text-sm text-gray-500 mt-1">0 / 2000 characters</p>
                    </div>
                    <button type="submit" name="update_custom_knowledge"
                        class="w-full bg-primary hover:bg-primary-dark text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-300">
                        Update Custom Knowledge
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function updateCharCount(textareaId, counterId, maxLength) {
            const textarea = document.getElementById(textareaId);
            const counter = document.getElementById(counterId);

            function updateCount() {
                const currentLength = textarea.value.length;
                counter.textContent = `${currentLength} / ${maxLength} characters`;

                if (currentLength > maxLength) {
                    textarea.value = textarea.value.slice(0, maxLength);
                    counter.textContent = `${maxLength} / ${maxLength} characters`;
                }
            }

            textarea.addEventListener('input', updateCount);
            updateCount(); // Initial count
        }

        updateCharCount('base_knowledge', 'base_knowledge_count', 3000);
        updateCharCount('custom_knowledge', 'custom_knowledge_count', 2000);
    </script>
</body>
</html>