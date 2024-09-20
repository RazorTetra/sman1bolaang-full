<?php
require_once('../config.php');
include('../admin/auth.php');

// Check if button data exists, if not, create default entry
$stmt = $pdo->query("SELECT COUNT(*) FROM custom_navbar_button");
if ($stmt->fetchColumn() == 0) {
    $pdo->exec("INSERT INTO custom_navbar_button (text, url, is_visible, button_color, text_color) VALUES ('Custom Button', '#', 0, '#000000', '#FFFFFF')");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stmt = $pdo->prepare("UPDATE custom_navbar_button SET text = ?, url = ?, is_visible = ?, button_color = ?, text_color = ? WHERE id = 1");
    $stmt->execute([
        $_POST['text'],
        $_POST['url'],
        isset($_POST['is_visible']) ? 1 : 0,
        $_POST['button_color'],
        $_POST['text_color']
    ]);
    $_SESSION['message'] = "Button updated successfully!";
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Fetch current button settings
$stmt = $pdo->query("SELECT * FROM custom_navbar_button WHERE id = 1");
$button = $stmt->fetch(PDO::FETCH_ASSOC);

// Check for message in session
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Navbar</title>
    <link rel="shortcut icon" href="../assets/img/logo-smk.png" type="image/x-icon">
    <link href="../assets/css/output.css" rel="stylesheet">
    <style>
        .preview-button {
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
    </style>
</head>

<body>
    <?php include('../admin/components/navbar.php'); ?>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6 text-center">Manage Custom Navbar Button</h1>

        <?php if (isset($message)): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline"><?php echo $message; ?></span>
            </div>
        <?php endif; ?>

        <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <form method="POST" class="space-y-4">
                <div>
                    <label for="text" class="block text-sm font-medium text-gray-700">Button Text:</label>
                    <input type="text" id="text" name="text" value="<?php echo htmlspecialchars($button['text']); ?>" required 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>

                <div>
                    <label for="url" class="block text-sm font-medium text-gray-700">Button URL:</label>
                    <input type="url" id="url" name="url" value="<?php echo htmlspecialchars($button['url']); ?>" required 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>

                <div class="flex space-x-4">
                    <div>
                        <label for="button_color" class="block text-sm font-medium text-gray-700">Button Color:</label>
                        <input type="color" id="button_color" name="button_color" value="<?php echo htmlspecialchars($button['button_color']); ?>" 
                               class="mt-1 block w-full h-10 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>
                    <div>
                        <label for="text_color" class="block text-sm font-medium text-gray-700">Text Color:</label>
                        <input type="color" id="text_color" name="text_color" value="<?php echo htmlspecialchars($button['text_color']); ?>" 
                               class="mt-1 block w-full h-10 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>
                </div>

                <div class="flex items-center">
                    <input type="checkbox" id="is_visible" name="is_visible" <?php echo $button['is_visible'] ? 'checked' : ''; ?> 
                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <label for="is_visible" class="ml-2 block text-sm text-gray-900">Display Button</label>
                </div>

                <div>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Update
                    </button>
                </div>
            </form>
        </div>

        <div class="mt-8">
            <h2 class="text-2xl font-bold mb-4">Preview:</h2>
            <div class="flex justify-center">
                <button id="preview" class="px-4 py-2 rounded font-semibold text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-opacity-75">
                    Button Preview
                </button>
            </div>
        </div>
    </div>

    <script>
        function updatePreview() {
            const preview = document.getElementById('preview');
            preview.textContent = document.getElementById('text').value;
            preview.style.backgroundColor = document.getElementById('button_color').value;
            preview.style.color = document.getElementById('text_color').value;
            preview.style.display = document.getElementById('is_visible').checked ? 'inline-block' : 'none';
        }

        document.getElementById('text').addEventListener('input', updatePreview);
        document.getElementById('button_color').addEventListener('input', updatePreview);
        document.getElementById('text_color').addEventListener('input', updatePreview);
        document.getElementById('is_visible').addEventListener('change', updatePreview);

        // Initial update
        updatePreview();
    </script>
</body>
</html>