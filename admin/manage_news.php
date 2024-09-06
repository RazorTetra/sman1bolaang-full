<?php
require_once('../config.php');
include('../admin/auth.php'); // Mengimpor auth.php untuk pengecekan login

// Query untuk mengambil data artikel
$query = "SELECT id, title, DATE_FORMAT(created_at, '%d %M %Y') AS created_at FROM articles ORDER BY created_at DESC";
$result = $pdo->query($query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Berita</title>
    <link href="../assets/css/output.css" rel="stylesheet">
    <!--=============== FAVICON ===============-->
    <link rel="shortcut icon" href="../assets/img/logo-smk.png" type="image/x-icon">

</head>

<body class="bg-gray-100">
    <?php include('../admin/components/navbar.php'); ?>

    <main class="container mx-auto p-6">
        <section class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Daftar Berita</h2>
                <a href="buat_berita.php" class="bg-blue-500 hover:bg-blue-600 text-white text-align-center font-semibold py-2 px-4 rounded-lg">Tambah Berita</a>
            </div>

            <!-- Tabel berita dengan responsivitas -->
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-md">
                    <thead>
                        <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                            <th class="py-3 px-6 text-left">Judul</th>
                            <th class="py-3 px-6 text-left">Tanggal</th>
                            <th class="py-3 px-6 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 text-sm font-light">
                        <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
                            <tr class="border-b border-gray-200 hover:bg-gray-100">
                                <td class="py-3 px-6 text-left"><?php echo htmlspecialchars($row['title']); ?></td>
                                <td class="py-3 px-6 text-left"><?php echo htmlspecialchars($row['created_at']); ?></td>
                                <td class="py-3 px-6 text-center">
                                    <a href="edit_article.php?id=<?php echo $row['id']; ?>" class="text-blue-500 hover:text-blue-700">Edit</a>
                                    <a href="delete_article.php?id=<?php echo $row['id']; ?>" class="text-red-500 hover:text-red-700 ml-4" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</body>

</html>