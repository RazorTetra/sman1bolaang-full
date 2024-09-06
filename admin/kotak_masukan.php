<?php
require_once('../config.php');
include('../admin/auth.php');

// Delete functionality
if (isset($_POST['delete']) && isset($_POST['id'])) {
    $id = $_POST['id'];
    $stmt = $pdo->prepare("DELETE FROM feedback WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: kotak_masukan.php");
    exit();
}

// Sorting
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'created_at';
$order = isset($_GET['order']) ? $_GET['order'] : 'DESC';

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 10;
$start = ($page > 1) ? ($page * $perPage) - $perPage : 0;

// Count total rows for pagination
$total = $pdo->query("SELECT COUNT(*) FROM feedback")->fetchColumn();
$pages = ceil($total / $perPage);

// Query to fetch feedback data with sorting and pagination
$stmt = $pdo->prepare("SELECT * FROM feedback ORDER BY $sort $order LIMIT :start, :perPage");
$stmt->bindValue(':start', $start, PDO::PARAM_INT);
$stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);
$stmt->execute();
$feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Function to create sorting links
function sortLink($column, $currentSort, $currentOrder)
{
    $newOrder = ($currentSort === $column && $currentOrder === 'ASC') ? 'DESC' : 'ASC';
    return "kotak_masukan.php?sort=$column&order=$newOrder";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kotak Masukan - Admin</title>
    <link href="../assets/css/output.css" rel="stylesheet">
    <!--=============== FAVICON ===============-->
    <link rel="shortcut icon" href="../assets/img/logo-smk.png" type="image/x-icon">

    <style>
        .table {
            width: 100%;
            border-collapse: collapse;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .table th,
        .table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        .table th {
            background-color: #f3f4f6;
            color: #4b5563;
        }

        .table tr:hover {
            background-color: #f9fafb;
        }

        .table a {
            color: #3b82f6;
            text-decoration: none;
        }

        .table a:hover {
            text-decoration: underline;
        }

        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .pagination a {
            padding: 8px 16px;
            margin: 0 4px;
            border-radius: 4px;
            background-color: #3b82f6;
            color: white;
            text-decoration: none;
            font-weight: 600;
        }

        .pagination a:hover {
            background-color: #2563eb;
        }

        .modal {
            position: fixed;
            inset: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: rgba(0, 0, 0, 0.5);
            visibility: hidden;
            opacity: 0;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        .modal.show {
            visibility: visible;
            opacity: 1;
        }

        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            max-width: 800px;
            width: 100%;
            position: relative;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h2 {
            margin: 0;
        }

        .modal-header button {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
        }

        .modal-body {
            margin-top: 20px;
        }
    </style>
</head>

<body class="bg-gray-100">
    <?php include('../admin/components/navbar.php'); ?>

    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6">Kotak Masukan</h1>
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th><a href="<?php echo sortLink('name', $sort, $order); ?>">Nama</a></th>
                        <th><a href="<?php echo sortLink('email', $sort, $order); ?>">Email</a></th>
                        <th><a href="<?php echo sortLink('subject', $sort, $order); ?>">Subjek</a></th>
                        <th>Pesan</th>
                        <th><a href="<?php echo sortLink('created_at', $sort, $order); ?>">Tanggal</a></th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = $start + 1; ?>
                    <?php foreach ($feedbacks as $feedback): ?>
                        <tr>
                            <td class="text-center"><?php echo $no++; ?></td>
                            <td><?php echo htmlspecialchars($feedback['name'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($feedback['email'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($feedback['subject'] ?? 'N/A'); ?></td>
                            <td>
                                <?php
                                $message = htmlspecialchars($feedback['message'] ?? 'N/A');
                                echo (strlen($message) > 50) ? substr($message, 0, 50) . '...' : $message;
                                ?>
                            </td>
                            <td><?php echo htmlspecialchars($feedback['created_at']); ?></td>
                            <td class="text-center">
                                <button onclick="showFullMessage(
                                    '<?php echo htmlspecialchars($feedback['id']); ?>',
                                    '<?php echo htmlspecialchars($feedback['name']); ?>',
                                    '<?php echo htmlspecialchars($feedback['email']); ?>',
                                    '<?php echo htmlspecialchars($feedback['subject']); ?>',
                                    '<?php echo htmlspecialchars($feedback['message']); ?>',
                                    '<?php echo htmlspecialchars($feedback['created_at']); ?>'
                                )" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-1 px-2 rounded text-xs mb-1">
                                    Lihat
                                </button>
                                <form method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pesan ini?');">
                                    <input type="hidden" name="id" value="<?php echo $feedback['id']; ?>">
                                    <button type="submit" name="delete" class="bg-red-500 hover:bg-red-600 text-white font-medium py-1 px-2 rounded text-xs">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="pagination">
        <?php for ($i = 1; $i <= $pages; $i++): ?>
            <a href="?page=<?php echo $i; ?>&sort=<?php echo $sort; ?>&order=<?php echo $order; ?>">
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>
    </div>

    <!-- Modal for showing full message -->
    <div id="messageModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="font-bold text-2xl">Pesan Lengkap</h2>
                <button onclick="closeModal()">&times;</button>
            </div>
            <div id="messageContent" class="modal-body"></div>
        </div>
    </div>

    <script>
        function showFullMessage(id, name, email, subject, message, createdAt) {
            const contentDiv = document.getElementById('messageContent');
            contentDiv.innerHTML = `
                <p><strong>ID:</strong> ${id}</p>
                <p><strong>Nama:</strong> ${name}</p>
                <p><strong>Email:</strong> ${email}</p>
                <p><strong>Subjek:</strong> ${subject}</p>
                <p><strong>Pesan:</strong></p>
                <p>${message}</p>
                <p><strong>Tanggal:</strong> ${createdAt}</p>
            `;
            document.getElementById('messageModal').classList.add('show');
        }

        function closeModal() {
            document.getElementById('messageModal').classList.remove('show');
        }
    </script>
</body>

</html>