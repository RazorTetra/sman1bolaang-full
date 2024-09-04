<?php
session_start();
require_once('../config.php');

// Periksa apakah user sudah login dan memiliki role admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../loginPage.php");
    exit();
}

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
    <link rel="stylesheet" href="../assets/css/admin.css"> <!-- Ganti dengan path CSS Anda -->
</head>

<body>
    <?php include('../admin/components/navbar.php'); ?>

    <main>
        <section>
            <h2>Daftar Berita</h2>
            <a href="buat_berita.php" class="button">Tambah Berita</a>
            <table>
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                            <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                            <td>
                                <a href="edit_article.php?id=<?php echo $row['id']; ?>">Edit</a>
                                <a href="delete_article.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>
    </main>
</body>

</html>
