<?php
require_once('../config.php');
include('../admin/auth.php');

$message = '';
$messageType = '';

// ==================== HANDLE FORM SUBMISSIONS ====================

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_FILES["new_image"]) && $_FILES["new_image"]["error"] == 0) {
        // Handle structure image upload
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
    } elseif (isset($_POST['aksi'])) {
        // Handle other form submissions (staff management and tupoksi)
        switch ($_POST['aksi']) {
            case 'update_tupoksi':
                if (isset($_POST['google_drive_link'])) {
                    $response = json_decode(updateTupoksi($_POST['google_drive_link']), true);
                } else {
                    $response = ['success' => false, 'message' => "Link Google Drive Tupoksi tidak ditemukan."];
                }
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            case 'tambah_staff':
            case 'edit_staff':
                $pesan = kelolaProfilStaff($_POST, isset($_FILES['foto']) ? $_FILES['foto'] : null);
                break;
            case 'hapus_staff':
                $id = intval($_POST['id']);
                $stmt = $pdo->prepare("DELETE FROM profil_staff WHERE id = ?");
                $stmt->execute([$id]);
                $pesan = "Profil staff berhasil dihapus.";
                break;
        }
    }
}

// ==================== HELPER FUNCTIONS ====================

// Function to update Tupoksi link
function updateTupoksi($link)
{
    global $pdo;
    try {
        $stmt = $pdo->prepare("INSERT INTO tupoksi_staff (google_drive_link) VALUES (?)");
        $stmt->execute([$link]);
        return json_encode(['success' => true, 'message' => "Link Tupoksi berhasil diperbarui."]);
    } catch (PDOException $e) {
        return json_encode(['success' => false, 'message' => "Terjadi kesalahan saat menyimpan data ke database: " . $e->getMessage()]);
    }
}

// Function to manage staff profiles
function kelolaProfilStaff($data, $foto = null)
{
    global $pdo;
    $id = isset($data['id']) ? $data['id'] : null;

    $fields = ['nama', 'jabatan', 'riwayat_pendidikan', 'status', 'mata_pelajaran', 'lama_mengajar', 'pangkat', 'alamat', 'motto'];
    $params = array_intersect_key($data, array_flip($fields));

    if ($id) {
        $sql = "UPDATE profil_staff SET " . implode("=?,", $fields) . "=? WHERE id=?";
        $params[] = $id;
        if ($foto && $foto['error'] == 0) {
            $foto_name = "staff_" . time() . "." . pathinfo($foto['name'], PATHINFO_EXTENSION);
            move_uploaded_file($foto['tmp_name'], "../assets/img/" . $foto_name);
            $sql = str_replace("WHERE", ", lokasi_foto=? WHERE", $sql);
            $params[] = $foto_name;
        }
    } else {
        // Check if a staff with the same name already exists
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM profil_staff WHERE nama = ?");
        $stmt->execute([$data['nama']]);
        if ($stmt->fetchColumn() > 0) {
            return "Staff dengan nama tersebut sudah ada.";
        }

        $sql = "INSERT INTO profil_staff (" . implode(",", $fields) . ",lokasi_foto) VALUES (" . str_repeat("?,", count($fields)) . "?)";
        if ($foto && $foto['error'] == 0) {
            $foto_name = "staff_" . time() . "." . pathinfo($foto['name'], PATHINFO_EXTENSION);
            move_uploaded_file($foto['tmp_name'], "../assets/img/" . $foto_name);
            $params[] = $foto_name;
        } else {
            $params[] = '';
        }
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute(array_values($params));

    return "Profil staff berhasil " . ($id ? "diperbarui" : "ditambahkan") . ".";
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['aksi'])) {
    $response = ['success' => false, 'message' => ''];

    switch ($_POST['aksi']) {
        case 'tambah_staff':
        case 'edit_staff':
            $response['message'] = kelolaProfilStaff($_POST, isset($_FILES['foto']) ? $_FILES['foto'] : null);
            $response['success'] = true;
            break;
        case 'hapus_staff':
            $id = intval($_POST['id']);
            $stmt = $pdo->prepare("DELETE FROM profil_staff WHERE id = ?");
            $stmt->execute([$id]);
            $response['message'] = "Profil staff berhasil dihapus.";
            $response['success'] = true;
            break;
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// ==================== FETCH DATA ====================

// Fetch current struktur organisasi
$stmt = $pdo->query("SELECT * FROM struktur_organisasi WHERE id = 1");
$struktur = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch staff data
$stmt = $pdo->query("SELECT * FROM profil_staff ORDER BY jabatan");
$result_staff = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch current Tupoksi link
$stmt = $pdo->query("SELECT * FROM tupoksi_staff ORDER BY tanggal_upload DESC LIMIT 1");
$current_tupoksi = $stmt->fetch(PDO::FETCH_ASSOC);

// ==================== HTML OUTPUT ====================
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
        .table-cell-truncate {
            max-width: 150px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
</head>

<body class="bg-gray-100">
    <?php include('../admin/components/navbar.php'); ?>

    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">Manage Struktur Organisasi</h1>

        <!-- Structure Image Section -->
        <div class="bg-white p-4 sm:p-6 rounded-lg shadow-md mb-8">
            <h2 class="text-xl sm:text-2xl font-semibold mb-4 text-gray-700">Gambar Struktur Organisasi</h2>
            <div class="w-full overflow-hidden rounded-lg shadow">
                <img src="../<?php echo htmlspecialchars($struktur['image_path']); ?>"
                    alt="Struktur Organisasi"
                    class="w-full h-auto object-contain max-h-[70vh]">
            </div>

            <form action="" method="POST" enctype="multipart/form-data" class="mt-6">
                <div class="mb-4">
                    <label for="new_image" class="block text-sm font-medium text-gray-700 mb-2">Upload Gambar Baru</label>
                    <input type="file" id="new_image" name="new_image" accept="image/*"
                        class="w-full px-3 py-2 text-gray-700 border rounded-lg focus:outline-none" required>
                </div>
                <button type="submit"
                    class="w-full sm:w-auto bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg transition duration-300">
                    Update Gambar
                </button>
            </form>
        </div>

        <!-- Tupoksi Section -->
        <div class="bg-white p-6 rounded-lg shadow-md mb-8">
            <h2 class="text-2xl font-semibold mb-4 text-gray-700">Kelola Tupoksi PDF</h2>
            <?php if ($current_tupoksi): ?>
                <p class="mb-2">Link Tupoksi saat ini: <a href="<?php echo htmlspecialchars($current_tupoksi['google_drive_link']); ?>" target="_blank" class="text-blue-500 hover:underline"><?php echo htmlspecialchars($current_tupoksi['google_drive_link']); ?></a></p>
                <p class="mb-4">Tanggal Upload: <?php echo htmlspecialchars($current_tupoksi['tanggal_upload']); ?></p>
            <?php else: ?>
                <p class="mb-4 text-gray-600">Belum ada link Tupoksi.</p>
            <?php endif; ?>
            <form id="tupoksiForm" action="" method="POST">
                <input type="hidden" name="aksi" value="update_tupoksi">
                <div class="mb-4">
                    <label for="google_drive_link" class="block text-sm font-medium text-gray-700 mb-2">Link Google Drive Tupoksi PDF</label>
                    <input type="url" class="w-full px-3 py-2 text-gray-700 border rounded-lg focus:outline-none" id="google_drive_link" name="google_drive_link" required placeholder="https://drive.google.com/file/d/...">
                </div>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg transition duration-300">Update Link Tupoksi</button>
            </form>
        </div>

        <!-- Staff Profiles Section -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-2xl font-semibold mb-4 text-gray-700">Kelola Profil Staff</h2>
            <button class="bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded-lg mb-4 transition duration-300" onclick="openModal()">
                Tambah Staff Baru
            </button>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-300">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="py-2 px-4 border-b text-left">Foto</th>
                            <th class="py-2 px-4 border-b text-left">Nama</th>
                            <th class="py-2 px-4 border-b text-left">Jabatan</th>
                            <th class="py-2 px-4 border-b text-left">Pendidikan</th>
                            <th class="py-2 px-4 border-b text-left">Status</th>
                            <th class="py-2 px-4 border-b text-left">Mata Pelajaran</th>
                            <th class="py-2 px-4 border-b text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($result_staff as $row): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="py-2 px-4 border-b">
                                    <img src="../assets/img/<?php echo htmlspecialchars($row['lokasi_foto']); ?>" alt="Foto <?php echo htmlspecialchars($row['nama']); ?>" class="w-6 h-8 object-cover">
                                </td>
                                <td class="py-2 px-4 border-b table-cell-truncate" title="<?php echo htmlspecialchars($row['nama']); ?>"><?php echo htmlspecialchars($row['nama']); ?></td>
                                <td class="py-2 px-4 border-b table-cell-truncate" title="<?php echo htmlspecialchars($row['jabatan']); ?>"><?php echo htmlspecialchars($row['jabatan']); ?></td>
                                <td class="py-2 px-4 border-b table-cell-truncate" title="<?php echo htmlspecialchars($row['riwayat_pendidikan']); ?>"><?php echo htmlspecialchars($row['riwayat_pendidikan']); ?></td>
                                <td class="py-2 px-4 border-b table-cell-truncate" title="<?php echo htmlspecialchars($row['status']); ?>"><?php echo htmlspecialchars($row['status']); ?></td>
                                <td class="py-2 px-4 border-b table-cell-truncate" title="<?php echo htmlspecialchars($row['mata_pelajaran']); ?>"><?php echo htmlspecialchars($row['mata_pelajaran']); ?></td>
                                <td class="py-2 px-4 border-b">
                                    <button onclick="viewStaffDetail(<?php echo $row['id']; ?>)" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-1 px-2 rounded text-xs mr-1">Lihat</button>
                                    <button onclick="editStaff(<?php echo $row['id']; ?>)" class="bg-green-500 hover:bg-green-600 text-white font-bold py-1 px-2 rounded text-xs mr-1">Edit</button>
                                    <button onclick="confirmDelete(<?php echo $row['id']; ?>)" class="bg-red-500 hover:bg-red-600 text-white font-bold py-1 px-2 rounded text-xs">Hapus</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Staff Modal -->
    <div id="staffModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900" id="staffModalLabel">Tambah/Edit Profil Staff</h3>
                <form id="staffForm" method="POST" enctype="multipart/form-data" class="mt-2">
                    <input type="hidden" name="aksi" value="tambah_staff">
                    <input type="hidden" name="id" id="staff_id">
                    <div class="mt-2">
                        <label for="nama" class="block text-sm font-medium text-gray-700 text-left">Nama:</label>
                        <input type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" id="nama" name="nama" required>
                    </div>
                    <!-- <div class="mt-2">
                        <label for="gelar" class="block text-sm font-medium text-gray-700 text-left">Gelar:</label>
                        <input type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" id="gelar" name="gelar">
                    </div> -->
                    <div class="mt-2">
                        <label for="jabatan" class="block text-sm font-medium text-gray-700 text-left">Jabatan:</label>
                        <input type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" id="jabatan" name="jabatan" required>
                    </div>
                    <div class="mt-2">
                        <label for="riwayat_pendidikan" class="block text-sm font-medium text-gray-700 text-left">Riwayat Pendidikan:</label>
                        <textarea class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" id="riwayat_pendidikan" name="riwayat_pendidikan" required></textarea>
                    </div>
                    <div class="mt-2">
                        <label for="status" class="block text-sm font-medium text-gray-700 text-left">Status:</label>
                        <input type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" id="status" name="status" required>
                    </div>
                    <div class="mt-2">
                        <label for="mata_pelajaran" class="block text-sm font-medium text-gray-700 text-left">Mata Pelajaran:</label>
                        <input type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" id="mata_pelajaran" name="mata_pelajaran" required>
                    </div>
                    <div class="mt-2">
                        <label for="lama_mengajar" class="block text-sm font-medium text-gray-700 text-left">Lama Mengajar (Tahun):</label>
                        <input type="number" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" id="lama_mengajar" name="lama_mengajar" required>
                    </div>
                    <div class="mt-2">
                        <label for="pangkat" class="block text-sm font-medium text-gray-700 text-left">Pangkat:</label>
                        <input type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" id="pangkat" name="pangkat" required>
                    </div>
                    <div class="mt-2">
                        <label for="alamat" class="block text-sm font-medium text-gray-700 text-left">Alamat:</label>
                        <textarea class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" id="alamat" name="alamat" required></textarea>
                    </div>
                    <div class="mt-2">
                        <label for="motto" class="block text-sm font-medium text-gray-700 text-left">Motto:</label>
                        <input type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" id="motto" name="motto" required>
                    </div>
                    <div class="mt-2">
                        <label for="foto" class="block text-sm font-medium text-gray-700 text-left">Foto:</label>
                        <input type="file" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" id="foto" name="foto">
                    </div>
                    <div class="mt-4 flex justify-between">
                        <button type="button" onclick="closeModal()" class="inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm">
                            Batal
                        </button>
                        <button type="submit" class="inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- See all data -->
    <div id="staffDetailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-6 border w-11/12 max-w-md min-w-[300px] shadow-lg rounded-md bg-white">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-semibold text-gray-900" id="staffDetailName"></h3>
                <button onclick="closeStaffDetailModal()" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="flex flex-col gap-4" id="staffDetailContent">
                <!-- Content will be dynamically inserted here -->
            </div>
        </div>
    </div>

    <!-- Confirm Delete Modal -->
    <div id="confirmDeleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Konfirmasi Hapus</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">Apakah Anda yakin ingin menghapus profil ini?</p>
                </div>
                <div class="items-center px-4 py-3">
                    <button id="confirmDeleteButton" class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md mr-2 shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300">
                        Hapus
                    </button>
                    <button onclick="closeConfirmDeleteModal()" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Message Modal -->
    <div id="messageModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Pesan</h3>
                <div class="mt-2 px-7 py-3">
                    <p id="modalMessage" class="text-sm text-gray-500"></p>
                </div>
                <div class="items-center px-4 py-3">
                    <button id="closeMessageModal" class="px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                        OK
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Mesaage Modal style 2 -->
    <div id="notificationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Notifikasi</h3>
                <div class="mt-2 px-7 py-3">
                    <p id="notificationMessage" class="text-sm text-gray-500"></p>
                </div>
                <div class="items-center px-4 py-3">
                    <button id="closeNotificationModal" class="px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                        OK
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js.manage_struktur.js"></script>
    <script>
        <?php if ($message): ?>
            showMessage("<?php echo addslashes($message); ?>");
        <?php endif; ?>
    </script>
</body>

</html>