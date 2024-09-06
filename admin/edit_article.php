<?php
require_once('../config.php');
include '../admin/auth.php';

// Periksa apakah user memiliki role admin
if ($role !== 'admin') {
    header("Location: ../pages/loginPage.php");
    exit();
}

$id = isset($_GET['id']) ? $_GET['id'] : null;
$error = '';
$success = '';

if (!$id) {
    header("Location: manage_news.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];

    // Proses upload gambar jika ada
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['image']['name'];
        $filetype = pathinfo($filename, PATHINFO_EXTENSION);

        if (in_array(strtolower($filetype), $allowed)) {
            $newname = uniqid() . '.' . $filetype;
            $upload_dir = dirname(__DIR__) . '/assets/img/';
            if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $newname)) {
                $image = 'assets/img/' . $newname;  // Simpan path relatif
            } else {
                $error = "Gagal mengupload gambar.";
            }
        } else {
            $error = "Format file tidak diizinkan. Gunakan jpg, jpeg, png, atau gif.";
        }
    }

    if (empty($error)) {
        try {
            $stmt = $pdo->prepare("UPDATE articles SET title = ?, content = ?, updated_at = NOW() WHERE id = ?");
            $stmt->execute([$title, $content, $id]);

            if (isset($image)) {
                // Hapus gambar lama jika ada
                $stmt = $pdo->prepare("SELECT image FROM articles WHERE id = ?");
                $stmt->execute([$id]);
                $old_image = $stmt->fetchColumn();
                if ($old_image) {
                    $old_image_path = dirname(__DIR__) . '/' . $old_image;
                    if (file_exists($old_image_path)) {
                        unlink($old_image_path);
                    }
                }

                // Update dengan gambar baru
                $stmt = $pdo->prepare("UPDATE articles SET image = ? WHERE id = ?");
                $stmt->execute([$image, $id]);
            }

            $success = "Artikel berhasil diperbarui.";
        } catch (PDOException $e) {
            $error = "Terjadi kesalahan: " . $e->getMessage();
        }
    }
}

// Ambil data artikel
try {
    $stmt = $pdo->prepare("SELECT * FROM articles WHERE id = ?");
    $stmt->execute([$id]);
    $article = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$article) {
        header("Location: manage_news.php");
        exit();
    }
} catch (PDOException $e) {
    $error = "Terjadi kesalahan: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Artikel</title>
    <link href="../assets/css/output.css" rel="stylesheet">
    <!--=============== FAVICON ===============-->
    <link rel="shortcut icon" href="../assets/img/logo-smk.png" type="image/x-icon">

</head>

<body class="bg-gray-100">
    <?php include('../admin/components/navbar.php'); ?>

    <main class="container mx-auto p-6 bg-white shadow-md rounded-lg mt-10">
        <h2 class="text-3xl font-bold mb-6 text-gray-800">Edit Artikel</h2>

        <!-- Tampilkan pesan error jika ada -->
        <?php if ($error): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <!-- Tampilkan pesan sukses jika ada -->
        <?php if ($success): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <form action="" method="post" enctype="multipart/form-data" class="space-y-6">
            <!-- Input judul -->
            <div>
                <label for="title" class="block text-gray-700 font-medium">Judul:</label>
                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($article['title']); ?>" required class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500">
            </div>

            <!-- Input konten -->
            <div>
                <label for="content" class="block text-gray-700 font-medium">Konten:</label>
                <textarea id="content" name="content" rows="10" required class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500"><?php echo htmlspecialchars($article['content']); ?></textarea>
            </div>

            <!-- Input gambar -->
            <div>
                <label for="image" class="block text-gray-700 font-medium">Gambar Baru (opsional):</label>
                <input type="file" id="image" name="image" class="block w-full text-gray-700 border border-gray-300 rounded-md">
            </div>

            <!-- Tampilkan gambar lama -->
            <?php if ($article['image']): ?>
                <div class="mt-4">
                    <p class="text-gray-700">Gambar Saat Ini:</p>
                    <img src="../<?php echo htmlspecialchars($article['image']); ?>" alt="Gambar Artikel" class="max-w-xs border border-gray-300 rounded-md mt-2">
                </div>
            <?php endif; ?>

            <!-- Tombol submit -->
            <div>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-md focus:outline-none focus:shadow-outline">Perbarui Artikel</button>
            </div>
        </form>
    </main>
</body>

</html>