<?php
require_once '../config.php';
include '../admin/auth.php';

// Ambil data dari database
$stmt = $pdo->prepare("SELECT * FROM about_info WHERE id = 1");
$stmt->execute();
$about = $stmt->fetch();

// Proses form submit (untuk non-AJAX fallback)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data input dari form
    $description = !empty($_POST['description']) ? $_POST['description'] : $about['description'];
    $name = !empty($_POST['name']) ? $_POST['name'] : $about['name'];
    $facebook = !empty($_POST['facebook']) ? $_POST['facebook'] : $about['facebook'];
    $instagram = !empty($_POST['instagram']) ? $_POST['instagram'] : $about['instagram'];
    $youtube = !empty($_POST['youtube']) ? $_POST['youtube'] : $about['youtube'];

    // Tangani upload gambar dengan pengecekan apakah ada file yang diupload
    $image_path = $_FILES['image']['name'] ? 'assets/img/' . $_FILES['image']['name'] : $about['image'];

    // Validasi file gambar
    if ($_FILES['image']['name']) {
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);

        if (!in_array(strtolower($file_extension), $allowed_extensions)) {
            echo json_encode(['success' => false, 'message' => 'Format file tidak didukung.']);
            exit();
        }

        move_uploaded_file($_FILES['image']['tmp_name'], '../' . $image_path);
    }

    // Update data di database
    $stmt = $pdo->prepare("UPDATE about_info SET description = :description, name = :name, image = :image, facebook = :facebook, instagram = :instagram, youtube = :youtube WHERE id = 1");
    $stmt->execute([
        'description' => $description,
        'name' => $name,
        'image' => $image_path,
        'facebook' => $facebook,
        'instagram' => $instagram,
        'youtube' => $youtube
    ]);

    // Jika request melalui AJAX, kirim respons JSON
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        echo json_encode(['success' => true, 'message' => 'Data berhasil diperbarui']);
        exit();
    }

    // Redirect untuk menghindari resubmission
    header("Location: " . $_SERVER['PHP_SELF'] . "?updated=1");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage About Section</title>
    <link href="../assets/css/output.css" rel="stylesheet">
    <style>
        .editor-toolbar button {
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            padding: 8px;
            margin-right: 5px;
            cursor: pointer;
        }

        .editor-toolbar button:hover {
            background-color: #ddd;
        }

        .editor-content {
            border: 1px solid #ccc;
            padding: 10px;
            min-height: 200px;
            overflow-y: auto;
        }

        #name-editor {
            white-space: pre-wrap;
            word-wrap: break-word;
        }

        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            background-color: #4CAF50;
            color: white;
            border-radius: 5px;
            display: none;
            z-index: 1000;
            animation: fadeInOut 2.5s ease-in-out;
        }

        @keyframes fadeInOut {

            0%,
            100% {
                opacity: 0;
            }

            10%,
            90% {
                opacity: 1;
            }
        }
    </style>
</head>

<body class="bg-gray-100 font-sans">
    <?php include('../admin/components/navbar.php'); ?>

    <main class="p-6 max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-5">Manage About Section</h1>

        <div id="notification" class="notification"></div>

        <form id="aboutForm" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded shadow">
            <!-- Name field with reduced size -->
            <label class="block mb-2 text-lg font-semibold">Name:</label>
            <div class="editor-toolbar mb-2">
                <button type="button" onclick="toggleBold('name-editor')"><b>B</b></button>
            </div>
            <div contenteditable="true" id="name-editor" class="editor-content mb-4 border rounded p-2" style="height: 4rem; max-height: 4rem; overflow-y: auto;"><?php echo $about['name']; ?></div>
            <input type="hidden" name="name" id="name" value="<?php echo htmlspecialchars($about['name']); ?>">

            <!-- Image field -->
            <label class="block mb-2 text-lg font-semibold">Profile Image:</label>
            <input type="file" name="image" class="mb-4">
            <img src="../<?php echo htmlspecialchars($about['image']); ?>" alt="Current Image" class="mb-4" width="150">

            <!-- Rich Text Editor for Description -->
            <label class="block mb-2 text-lg font-semibold">Description:</label>
            <div class="editor-toolbar mb-2">
                <button type="button" onclick="execCmd('bold')"><b>B</b></button>
                <button type="button" onclick="execCmd('italic')"><i>I</i></button>
                <button type="button" onclick="execCmd('underline')"><u>U</u></button>
                <button type="button" onclick="execCmd('createLink', prompt('Enter URL:', 'http://'))">🔗</button>
            </div>
            <div contenteditable="true" id="editor" class="editor-content mb-4 border rounded p-2"><?php echo $about['description']; ?></div>
            <textarea name="description" id="description" class="hidden"><?php echo htmlspecialchars($about['description']); ?></textarea>

            <!-- Social media links -->
            <label class="block mb-2 text-lg font-semibold">Facebook:</label>
            <input type="text" name="facebook" value="<?php echo htmlspecialchars($about['facebook']); ?>" class="w-full p-2 border rounded mb-4">

            <label class="block mb-2 text-lg font-semibold">Instagram:</label>
            <input type="text" name="instagram" value="<?php echo htmlspecialchars($about['instagram']); ?>" class="w-full p-2 border rounded mb-4">

            <label class="block mb-2 text-lg font-semibold">YouTube:</label>
            <input type="text" name="youtube" value="<?php echo htmlspecialchars($about['youtube']); ?>" class="w-full p-2 border rounded mb-4">

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update</button>
        </form>
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function toggleBold(editorId) {
            document.execCommand('bold', false, null);
            document.getElementById(editorId).focus();
        }

        function execCmd(command, value = null) {
            document.execCommand(command, false, value);
            document.getElementById('editor').focus();
        }

        function updateInput(editorId, inputId) {
            let editor = document.getElementById(editorId);
            let input = document.getElementById(inputId);
            input.value = editor.innerHTML;
        }

        document.getElementById('name-editor').addEventListener('input', function() {
            updateInput('name-editor', 'name');
        });

        document.getElementById('editor').addEventListener('input', function() {
            updateInput('editor', 'description');
        });

        // Paste as plain text
        document.getElementById('name-editor').addEventListener('paste', function(e) {
            e.preventDefault();
            let text = e.clipboardData.getData('text/plain');
            document.execCommand('insertText', false, text);
        });

        document.getElementById('editor').addEventListener('paste', function(e) {
            e.preventDefault();
            let text = e.clipboardData.getData('text/plain');
            document.execCommand('insertText', false, text);
        });

        $(document).ready(function() {
            $('#aboutForm').submit(function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                $.ajax({
                    url: '<?php echo $_SERVER['PHP_SELF']; ?>',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        try {
                            let res = JSON.parse(response);
                            if (res.success) {
                                // Update notifikasi
                                $('#notification').text(res.message).fadeIn().delay(2000).fadeOut();

                                // Cek apakah gambar di-update dan refresh gambar
                                if (formData.get('image').name) {
                                    let newImagePath = 'assets/img/' + formData.get('image').name;
                                    $('img[alt="Current Image"]').attr('src', '../' + newImagePath + '?' + new Date().getTime());
                                }
                            } else {
                                alert(res.message);
                            }
                        } catch (e) {
                            window.location.reload();
                        }
                    }
                });
            });
        });
    </script>
</body>

</html>