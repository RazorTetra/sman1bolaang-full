<?php
require_once('../config.php');
include('../admin/auth.php'); // Mengimpor auth.php untuk pengecekan login

// Query untuk mengambil data artikel
$query = "SELECT id, title, DATE_FORMAT(created_at, '%d %M %Y') AS created_at FROM articles ORDER BY created_at DESC";
$result = $pdo->query($query);

// Inisialisasi variabel pencarian
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Query untuk mengambil data artikel dengan pencarian dan jumlah like/komentar
$query = "SELECT a.id, a.title, DATE_FORMAT(a.created_at, '%d %M %Y') AS created_at,
          (SELECT COUNT(*) FROM article_likes WHERE article_id = a.id) AS like_count,
          (SELECT COUNT(*) FROM article_comments WHERE article_id = a.id) AS comment_count
          FROM articles a
          WHERE a.title LIKE :search
          ORDER BY a.created_at DESC";

$stmt = $pdo->prepare($query);
$stmt->execute(['search' => "%$search%"]);

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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body class="bg-gray-100">
    <?php include('../admin/components/navbar.php'); ?>

    <main class="container mx-auto p-6">
        <section class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Daftar Berita</h2>
                <a href="buat_berita.php" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg">Tambah Berita</a>
            </div>

            <!-- Form pencarian -->
            <form action="" method="GET" class="mb-4">
                <div class="flex">
                    <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Cari judul berita..." class="flex-grow p-2 border rounded-l-lg">
                    <button type="submit" class="bg-blue-500 text-white px-4 rounded-r-lg">Cari</button>
                </div>
            </form>

            <!-- Tabel berita -->
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-md">
                    <thead>
                        <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                            <th class="py-3 px-6 text-left">Judul</th>
                            <th class="py-3 px-6 text-left">Tanggal</th>
                            <th class="py-3 px-6 text-center">Like</th>
                            <th class="py-3 px-6 text-center">Komentar</th>
                            <th class="py-3 px-6 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 text-sm font-light">
                        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                            <tr class="border-b border-gray-200 hover:bg-gray-100">
                                <td class="py-3 px-6 text-left"><?php echo htmlspecialchars($row['title']); ?></td>
                                <td class="py-3 px-6 text-left"><?php echo htmlspecialchars($row['created_at']); ?></td>
                                <td class="py-3 px-6 text-center"><?php echo $row['like_count']; ?></td>
                                <td class="py-3 px-6 text-center">
                                    <a href="#" class="view-comments" data-article-id="<?php echo $row['id']; ?>">
                                        <span class="comment-count"><?php echo $row['comment_count']; ?></span>
                                    </a>
                                </td>
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

    <!-- Modal untuk menampilkan komentar -->
    <div id="commentModal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                        Komentar
                    </h3>
                    <div class="mt-2">
                        <div id="commentList" class="max-h-96 overflow-y-auto">
                            <!-- Komentar akan ditampilkan di sini -->
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" id="closeModal">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>


    <!-- Script Komentar -->

    <script>
        $(document).ready(function() {
            $('.view-comments').click(function(e) {
                e.preventDefault();
                var articleId = $(this).data('article-id');

                $.ajax({
                    url: 'functions/get_comments.php',
                    type: 'GET',
                    data: {
                        article_id: articleId
                    },
                    success: function(response) {
                        $('#commentList').html(response);
                        $('#commentModal').removeClass('hidden');
                    }
                });
            });

            $('#closeModal').click(function() {
                $('#commentModal').addClass('hidden');
            });

            // Delegasi event untuk tombol hapus komentar
            $('#commentList').on('click', '.delete-comment', function(e) {
                e.preventDefault();
                var commentId = $(this).data('comment-id');
                var commentElement = $(this).closest('.comment-item');

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Komentar ini akan dihapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: 'functions/delete_comment.php',
                            type: 'POST',
                            data: {
                                comment_id: commentId
                            },
                            dataType: 'json', // Pastikan respons diinterpretasikan sebagai JSON
                            success: function(response) {
                                if (response.success) {
                                    commentElement.remove();
                                    Swal.fire(
                                        'Terhapus!',
                                        'Komentar telah dihapus.',
                                        'success'
                                    );
                                } else {
                                    Swal.fire(
                                        'Error!',
                                        response.message || 'Gagal menghapus komentar.',
                                        'error'
                                    );
                                }
                            },
                            error: function() {
                                Swal.fire(
                                    'Error!',
                                    'Terjadi kesalahan saat menghubungi server.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });
        });
    </script>
</body>

</html>