<?php
require_once('../config.php');
include('../admin/auth.php');

$msg = '';

// Fetch existing data
$stmt = $pdo->query("SELECT * FROM beranda LIMIT 1");
$beranda = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $youtube_link = $_POST['youtube_link'];
    $title = $_POST['title'];
    $description = $_POST['description'];

    if ($beranda) {
        // Update existing data
        $sql = "UPDATE beranda SET youtube_link = ?, title = ?, description = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$youtube_link, $title, $description, $beranda['id']]);
    } else {
        // Insert new data if not exists
        $sql = "INSERT INTO beranda (youtube_link, title, description) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$youtube_link, $title, $description]);
    }

    $msg = "Data beranda berhasil diperbarui!";

    // Refresh data after update
    $stmt = $pdo->query("SELECT * FROM beranda LIMIT 1");
    $beranda = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Beranda</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <?php include('../admin/components/navbar.php'); ?>


    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold mb-4">Kelola Beranda</h1>
        <?php if ($msg): ?>
            <p class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"><?php echo $msg; ?></p>
        <?php endif; ?>
        <form action="" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="youtube_link">
                    Link YouTube
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="youtube_link" type="text" name="youtube_link" value="<?php echo $beranda['youtube_link'] ?? ''; ?>" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="title">
                    Judul
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="title" type="text" name="title" value="<?php echo $beranda['title'] ?? ''; ?>" required>
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                    Deskripsi
                </label>
                <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="description" name="description" rows="4" required><?php echo $beranda['description'] ?? ''; ?></textarea>
            </div>
            <div class="flex items-center justify-between">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</body>

</html>