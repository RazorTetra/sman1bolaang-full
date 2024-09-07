<?php
require_once('../config.php');
include('../admin/auth.php');

$message = '';
$messageType = '';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $target_dir = "../assets/img/";
    $file_extension = pathinfo($_FILES["new_image"]["name"], PATHINFO_EXTENSION);
    $new_file_name = "struktur_" . time() . "." . $file_extension;
    $target_file = $target_dir . $new_file_name;
    $uploadOk = 1;
    $imageFileType = strtolower($file_extension);

    // Check if image file is an actual image or fake image
    $check = getimagesize($_FILES["new_image"]["tmp_name"]);
    if ($check === false) {
        $message = "File is not an image.";
        $messageType = "error";
        $uploadOk = 0;
    }

    // Check file size (limit to 5MB)
    if ($_FILES["new_image"]["size"] > 5000000) {
        $message = "Sorry, your file is too large. Maximum size is 5MB.";
        $messageType = "error";
        $uploadOk = 0;
    }

    // Allow certain file formats
    $allowed_types = ["jpg", "jpeg", "png", "gif"];
    if (!in_array($imageFileType, $allowed_types)) {
        $message = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $messageType = "error";
        $uploadOk = 0;
    }

    // If everything is ok, try to upload file and update database
    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["new_image"]["tmp_name"], $target_file)) {
            try {
                $db_image_path = "assets/img/" . $new_file_name; // Store relative path in database
                $stmt = $pdo->prepare("UPDATE struktur_organisasi SET image_path = ? WHERE id = 1");
                if ($stmt->execute([$db_image_path])) {
                    $message = "The file has been uploaded and the database has been updated.";
                    $messageType = "success";

                    // Delete old file if exists
                    $stmt = $pdo->query("SELECT image_path FROM struktur_organisasi WHERE id = 1");
                    $old_file = $stmt->fetchColumn();
                    if ($old_file && file_exists("../" . $old_file) && $old_file != $db_image_path) {
                        unlink("../" . $old_file);
                    }
                } else {
                    $message = "Sorry, there was an error updating the database.";
                    $messageType = "error";
                }
            } catch (PDOException $e) {
                $message = "Database error: " . $e->getMessage();
                $messageType = "error";
            }
        } else {
            $message = "Sorry, there was an error uploading your file.";
            $messageType = "error";
        }
    }
}

// Fetch current struktur organisasi
$stmt = $pdo->query("SELECT * FROM struktur_organisasi WHERE id = 1");
$struktur = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Struktur Organisasi - Admin</title>
    <link href="../assets/css/output.css" rel="stylesheet">
    <!--=============== FAVICON ===============-->
    <link rel="shortcut icon" href="../assets/img/logo-smk.png" type="image/x-icon">

    <style>
        .img-preview {
            max-width: 300px;
            max-height: 300px;
            object-fit: contain;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>

<body class="bg-gray-100">
    <?php include('../admin/components/navbar.php'); ?>

    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-4">Manage Struktur Organisasi</h1>
        <p class="mb-6">Update gambar struktur organisasi</p>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold mb-4">Gambar Saat Ini</h2>
            <img src="../<?php echo htmlspecialchars($struktur['image_path']); ?>" alt="Struktur Organisasi" class="img-preview mb-4">

            <form action="" method="POST" enctype="multipart/form-data" class="mt-6">
                <div class="mb-4">
                    <label for="new_image" class="block text-sm font-medium text-gray-700">Upload Gambar Baru</label>
                    <input type="file" id="new_image" name="new_image" accept="image/*" class="mt-1 block w-full" required>
                </div>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded">
                    Update Gambar
                </button>
            </form>
        </div>
    </div>

    <!-- Modal -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p id="modalMessage"></p>
        </div>
    </div>

    <script>
        var modal = document.getElementById("myModal");
        var span = document.getElementsByClassName("close")[0];
        var modalMessage = document.getElementById("modalMessage");

        <?php if ($message): ?>
            modal.style.display = "block";
            modalMessage.textContent = "<?php echo $message; ?>";
            modalMessage.className = "<?php echo $messageType; ?>";
        <?php endif; ?>

        span.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>

</html>