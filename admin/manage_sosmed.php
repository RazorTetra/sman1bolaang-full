<?php
require_once('../config.php');
include('../admin/auth.php');

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $platform = $_POST['platform'];
    $url = $_POST['url'];
    $icon = $_POST['icon'];
    $id = $_POST['id'];

    $stmt = $pdo->prepare("UPDATE social_media_links SET platform = ?, url = ?, icon = ? WHERE id = ?");
    $stmt->execute([$platform, $url, $icon, $id]);

    // Redirect to avoid form resubmission
    header("Location: manage_sosmed.php");
    exit();
}

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
    <link rel="shortcut icon" href="../assets/img/logo-smk.png" type="image/x-icon">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Media Links - Admin</title>
    <link href="../assets/css/output.css" rel="stylesheet">
    <link rel="shortcut icon" href="../assets/img/logo-smk.png" type="image/x-icon">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100">
    <?php include('../admin/components/navbar.php'); ?>

    <main class="container mx-auto p-6">
        <section class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Daftar Media Sosial</h2>
            </div>

            <!-- Tabel media sosial dengan responsivitas -->
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-md">
                    <thead>
                        <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                            <th class="py-3 px-6 text-left">Platform</th>
                            <th class="py-3 px-6 text-left">URL</th>
                            <th class="py-3 px-6 text-left">Icon</th>
                            <th class="py-3 px-6 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 text-sm font-light">
                        <?php foreach ($socialLinks as $link): ?>
                            <tr class="border-b border-gray-200 hover:bg-gray-100">
                                <td class="py-3 px-6 text-left"><?php echo htmlspecialchars($link['platform']); ?></td>
                                <td class="py-3 px-6 text-left">
                                    <a href="<?php echo htmlspecialchars($link['url']); ?>" target="_blank" rel="noopener noreferrer" class="text-blue-600 hover:text-blue-800">
                                        <?php echo htmlspecialchars($link['url']); ?>
                                    </a>
                                </td>
                                <td class="py-3 px-6 text-left"><i class="<?php echo htmlspecialchars($link['icon']); ?>"></i></td>
                                <td class="py-3 px-6 text-center">
                                    <button class="edit-btn text-blue-500 hover:text-blue-700"
                                        data-id="<?php echo $link['id']; ?>"
                                        data-platform="<?php echo htmlspecialchars($link['platform']); ?>"
                                        data-url="<?php echo htmlspecialchars($link['url']); ?>"
                                        data-icon="<?php echo htmlspecialchars($link['icon']); ?>">
                                        Edit
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <!-- Edit Form Modal -->
    <div id="edit-modal" class="hidden fixed z-50 inset-0 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-md">
                <form method="POST" action="manage_sosmed.php" class="p-6">
                    <input type="hidden" name="id" id="edit-id">
                    <div class="mb-4">
                        <label for="platform" class="block text-sm font-medium text-gray-700 mb-2">Platform</label>
                        <input type="text" id="edit-platform" name="platform" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div class="mb-4">
                        <label for="url" class="block text-sm font-medium text-gray-700 mb-2">URL</label>
                        <input type="url" id="edit-url" name="url" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div class="mb-6">
                        <label for="icon" class="block text-sm font-medium text-gray-700 mb-2">Icon (Font Awesome class)</label>
                        <input type="text" id="edit-icon" name="icon" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" id="close-modal" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium py-2 px-4 rounded transition duration-300 ease-in-out">Cancel</button>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded transition duration-300 ease-in-out">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const modal = document.getElementById('edit-modal');

        function openModal() {
            modal.classList.remove('hidden');
        }

        function closeModal() {
            modal.classList.add('hidden');
        }

        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function() {
                document.getElementById('edit-id').value = this.dataset.id;
                document.getElementById('edit-platform').value = this.dataset.platform;
                document.getElementById('edit-url').value = this.dataset.url;
                document.getElementById('edit-icon').value = this.dataset.icon;
                openModal();
            });
        });

        document.getElementById('close-modal').addEventListener('click', closeModal);

        modal.addEventListener('click', function(event) {
            if (event.target === modal) {
                closeModal();
            }
        });
    </script>
</body>

</html>

</html>