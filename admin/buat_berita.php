<?php
include('../admin/auth.php'); // Mengimpor auth.php untuk pengecekan login
include('../config.php');

$message = ""; // Variabel untuk menyimpan pesan sukses atau error

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $judul = $_POST['judul'];
    $tanggal = $_POST['tanggal'];
    $konten = $_POST['konten'];
    $gambar = $_FILES['gambar'];

    // Validasi form
    if (empty($judul) || empty($tanggal) || empty($konten) || $gambar['error'] !== UPLOAD_ERR_OK) {
        $message = "Semua kolom harus diisi dan gambar harus diupload.";
    } else {
        // Cek apakah file adalah gambar
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($gambar['type'], $allowedTypes)) {
            $message = "Hanya file gambar (JPG, PNG, GIF) yang diperbolehkan.";
        } else {
            // Upload gambar
            $gambarPath = 'assets/img/' . basename($gambar['name']);
            if (move_uploaded_file($gambar['tmp_name'], '../' . $gambarPath)) {
                // Simpan artikel ke database
                $stmt = $pdo->prepare("INSERT INTO articles (title, content, image, created_at, updated_at) VALUES (:title, :content, :image, NOW(), NOW())");
                $stmt->execute([
                    'title' => $judul,
                    'content' => $konten,
                    'image' => $gambarPath
                ]);
                $message = "Artikel berhasil dibuat.";
            } else {
                $message = "Gagal mengupload gambar.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Berita</title>
    <link href="../assets/css/output.css" rel="stylesheet">
    <!--=============== FAVICON ===============-->
    <link rel="shortcut icon" href="../assets/img/logo-smk.png" type="image/x-icon">

</head>

<body class="bg-gray-100 font-sans">
    <?php include('../admin/components/navbar.php'); ?>

    <main class="p-6">
        <h2 class="text-2xl font-bold mb-4">Form Buat Berita</h2>

        <!-- Menampilkan pesan sukses atau error -->
        <?php if (!empty($message)): ?>
            <div class="mb-4 p-4 text-white <?php echo strpos($message, 'berhasil') !== false ? 'bg-green-500' : 'bg-red-500'; ?> rounded">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data" class="max-w-4xl mx-auto bg-white p-6 rounded shadow-md">
            <div class="mb-4">
                <label for="judul" class="block text-gray-700 font-semibold mb-2">Judul Berita</label>
                <input type="text" id="judul" name="judul" required class="w-full border border-gray-300 p-2 rounded">
            </div>
            <div class="mb-4">
                <label for="tanggal" class="block text-gray-700 font-semibold mb-2">Tanggal</label>
                <input type="date" id="tanggal" name="tanggal" required class="w-full border border-gray-300 p-2 rounded">
            </div>
            <div class="mb-4">
                <label for="konten" class="block text-gray-700 font-semibold mb-2">Konten</label>
                <textarea id="konten" name="konten" rows="6" required class="w-full border border-gray-300 p-2 rounded"></textarea>
            </div>
            <div class="mb-4">
                <label for="gambar" class="block text-gray-700 font-semibold mb-2">Upload Gambar</label>
                <input type="file" id="gambar" name="gambar" required class="w-full border border-gray-300 p-2 rounded">
            </div>
            <button type="submit" name="submit" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">Kirim</button>
        </form>
    </main>
</body>

</html>