<?php
include('../admin/auth.php'); // Mengimpor auth.php untuk pengecekan login

// Mendapatkan username jika ada
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Admin';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="../assets/css/output.css" rel="stylesheet">
</head>

<body class="bg-gray-100 font-sans">
    <?php include('../admin/components/navbar.php'); ?>

    <main class="p-6 max-w-4xl mx-auto">

        <h1 class="text-2xl font-semibold text-gray-800 mb-4">Selamat datang, <?php echo htmlspecialchars($username); ?>!</h1>
        <p class="text-gray-600">Pilih menu di atas untuk mengelola berita atau galeri.</p>

    </main>
</body>

</html>