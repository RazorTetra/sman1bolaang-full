<?php
require_once('../config.php');
include('../admin/auth.php');

// Fetch social media links
$stmt = $pdo->query("SELECT * FROM social_media_links WHERE is_active = TRUE");
$socialLinks = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Media Links - Admin</title>
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
    </style>
</head>

<body class="bg-gray-100">
    <?php include('../admin/components/navbar.php'); ?>

    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-4">Social Media Links</h1>
        <p class="mb-6">Link Sosial media dibagian Kontak</p>
        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Platform</th>
                        <th>URL</th>
                        <th>Icon</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($socialLinks as $link): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($link['platform']); ?></td>
                            <td><?php echo htmlspecialchars($link['url']); ?></td>
                            <td><i class="<?php echo htmlspecialchars($link['icon']); ?>"></i></td>
                            <td>
                                <button class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-1 px-2 rounded text-xs">Edit</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>