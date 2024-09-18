<?php
require_once('../config.php');
include('../admin/auth.php');

// Fetch all images from the database
$stmt = $pdo->prepare("SELECT id, image, is_displayed FROM gallery ORDER BY created_at DESC");
$stmt->execute();
$gallery_images = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $selected_images = $_POST['display_images'] ?? [];

    if (count($selected_images) > 6) {
        $_SESSION['error_message'] = "Maksimal hanya bisa menampilkan 6 gambar.";
    } else {
        // Reset all images to not displayed
        $pdo->prepare("UPDATE gallery SET is_displayed = 0")->execute();

        // Update selected images to be displayed
        foreach ($selected_images as $image_id) {
            $stmt = $pdo->prepare("UPDATE gallery SET is_displayed = 1 WHERE id = :id");
            $stmt->execute(['id' => $image_id]);
        }

        $_SESSION['success_message'] = "Perubahan berhasil disimpan.";
    }
    header("Location: manage_gallery.php");
    exit();
}

// Process image deletion
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $image_id = $_GET['id'];

    // Fetch image path from database
    $stmt = $pdo->prepare("SELECT image FROM gallery WHERE id = :id");
    $stmt->execute(['id' => $image_id]);
    $image = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($image) {
        // Delete image file from directory
        $image_path = '../assets/img/' . $image['image'];
        if (file_exists($image_path)) {
            unlink($image_path);
        }

        // Delete image record from database
        $stmt = $pdo->prepare("DELETE FROM gallery WHERE id = :id");
        $stmt->execute(['id' => $image_id]);

        $_SESSION['success_message'] = "Gambar berhasil dihapus.";
    } else {
        $_SESSION['error_message'] = "Gambar tidak ditemukan!";
    }
    header("Location: manage_gallery.php");
    exit();
}

// Retrieve and clear any stored messages
$success_message = $_SESSION['success_message'] ?? null;
$error_message = $_SESSION['error_message'] ?? null;
unset($_SESSION['success_message'], $_SESSION['error_message']);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Galeri</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="../assets/css/output.css" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!--=============== FAVICON ===============-->
    <link rel="shortcut icon" href="../assets/img/logo-smk.png" type="image/x-icon">

    <style>
        .image-container {
            position: relative;
            width: 100%;
            padding-top: 100%;
            /* 1:1 Aspect Ratio */
        }

        .image-container img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .checkbox-container {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 10;
        }

        .checkbox-container input[type="checkbox"] {
            display: none;
        }

        .checkbox-container .checkbox-label {
            display: inline-block;
            width: 24px;
            height: 24px;
            background-color: rgba(255, 255, 255, 0.7);
            border: 2px solid #4a5568;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .checkbox-container input[type="checkbox"]:checked+.checkbox-label {
            background-color: #4299e1;
            border-color: #4299e1;
        }

        .checkbox-container .checkbox-label::after {
            content: '\2714';
            display: none;
            color: white;
            font-size: 16px;
            text-align: center;
            line-height: 20px;
        }

        .checkbox-container input[type="checkbox"]:checked+.checkbox-label::after {
            display: block;
        }
    </style>
</head>

<body class="bg-gray-100" x-data="{ 
    lightboxOpen: false, 
    lightboxImage: '', 
    selectedCount: <?php echo count(array_filter($gallery_images, function ($img) {
                        return $img['is_displayed'];
                    })); ?>
}">
    <?php include('../admin/components/navbar.php'); ?>

    <main class="container mx-auto p-6">
        <section class="bg-white p-8 rounded-lg shadow-lg">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-bold text-gray-800">Kelola Gambar Galeri</h2>
                <a href="upload_gallery.php" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition duration-300 flex items-center">
                    <i class="fas fa-plus mr-2"></i> Tambah Gambar
                </a>
            </div>

            <?php if ($success_message): ?>
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                    <p><?php echo $success_message; ?></p>
                </div>
            <?php endif; ?>

            <?php if ($error_message): ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    <p><?php echo $error_message; ?></p>
                </div>
            <?php endif; ?>

            <form action="manage_gallery.php" method="POST">
                <!-- Displayed images section -->
                <div class="mb-10">
                    <h3 class="text-2xl font-semibold text-gray-800 mb-6">Gambar yang Ditampilkan di Halaman Utama</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
                        <?php foreach ($gallery_images as $image): ?>
                            <?php if ($image['is_displayed']): ?>
                                <div class="relative group">
                                    <div class="image-container rounded-lg shadow-md overflow-hidden">
                                        <img src="../assets/img/<?php echo htmlspecialchars($image['image']); ?>" alt="Gambar Galeri" class="transition duration-300 group-hover:opacity-75">
                                    </div>
                                    <div class="checkbox-container">
                                        <input type="checkbox" id="display_<?php echo $image['id']; ?>" name="display_images[]" value="<?php echo $image['id']; ?>" checked
                                            @change="selectedCount = document.querySelectorAll('input[name=\'display_images[]\']:checked').length">
                                        <label for="display_<?php echo $image['id']; ?>" class="checkbox-label"></label>
                                    </div>
                                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <button type="button" @click="lightboxOpen = true; lightboxImage = '../assets/img/<?php echo htmlspecialchars($image['image']); ?>'" class="bg-blue-500 text-white p-2 rounded-full mr-2">
                                            <i class="fas fa-search-plus"></i>
                                        </button>
                                        <a href="manage_gallery.php?action=delete&id=<?php echo $image['id']; ?>"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus gambar ini?');"
                                            class="bg-red-500 text-white py-2 px-4 rounded-lg shadow-md transition duration-300 hover:bg-red-600">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Other images section -->
                <h3 class="text-2xl font-semibold text-gray-800 mb-6">Gambar Lainnya</h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
                    <?php foreach ($gallery_images as $image): ?>
                        <?php if (!$image['is_displayed']): ?>
                            <div class="relative group">
                                <div class="image-container rounded-lg shadow-md overflow-hidden">
                                    <img src="../assets/img/<?php echo htmlspecialchars($image['image']); ?>" alt="Gambar Galeri" class="transition duration-300 group-hover:opacity-75">
                                </div>
                                <div class="checkbox-container">
                                    <input type="checkbox" id="display_<?php echo $image['id']; ?>" name="display_images[]" value="<?php echo $image['id']; ?>"
                                        @change="selectedCount = document.querySelectorAll('input[name=\'display_images[]\']:checked').length">
                                    <label for="display_<?php echo $image['id']; ?>" class="checkbox-label"></label>
                                </div>
                                <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <button type="button" @click="lightboxOpen = true; lightboxImage = '../assets/img/<?php echo htmlspecialchars($image['image']); ?>'" class="bg-blue-500 text-white p-2 rounded-full mr-2">
                                        <i class="fas fa-search-plus"></i>
                                    </button>
                                    <a href="manage_gallery.php?action=delete&id=<?php echo $image['id']; ?>"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus gambar ini?');"
                                        class="bg-red-500 text-white py-2 px-4 rounded-lg shadow-md transition duration-300 hover:bg-red-600">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>

                <div class="mt-10 flex items-center justify-between">
                    <p class="text-lg text-gray-600">
                        <span x-text="selectedCount"></span> dari 9 gambar dipilih
                    </p>
                    <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-8 rounded-lg transition duration-300 flex items-center"
                        :disabled="selectedCount > 9"
                        :class="{ 'opacity-50 cursor-not-allowed': selectedCount > 9 }">
                        <i class="fas fa-save mr-2"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </section>
    </main>

    <!-- Lightbox -->
    <div x-show="lightboxOpen" x-cloak class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50">
        <div @click.away="lightboxOpen = false" class="max-w-3xl w-full bg-white p-2 rounded-lg">
            <img :src="lightboxImage" alt="Full size image" class="w-full h-auto">
            <button @click="lightboxOpen = false" class="mt-4 bg-red-500 text-white py-2 px-4 rounded-lg">
                Tutup
            </button>
        </div>
    </div>
</body>

</html>