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
    }
}

// ==================== HELPER FUNCTIONS ====================

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

function handleStrukturSubmission($data, $files)
{
    global $pdo;
    $id = isset($data['id']) ? $data['id'] : null;
    $judul = $data['judul'];

    if ($id) {
        // Update existing struktur
        $sql = "UPDATE struktur_organisasi SET judul = ? WHERE id = ?";
        $params = [$judul, $id];
    } else {
        // Add new struktur
        $sql = "INSERT INTO struktur_organisasi (judul) VALUES (?)";
        $params = [$judul];
    }

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        if (!$id) {
            $id = $pdo->lastInsertId();
        }

        // Handle image upload if provided
        if (isset($files['image']) && $files['image']['error'] == 0) {
            $uploadResult = uploadImage($files['image'], $id);
            if (!$uploadResult['success']) {
                return $uploadResult;
            }
        }

        return ['success' => true, 'message' => ($id ? 'Struktur berhasil diperbarui.' : 'Struktur baru berhasil ditambahkan.')];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()];
    }
}

function uploadImage($file, $strukturId)
{
    $target_dir = "../assets/img/";
    $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $new_file_name = "struktur_" . $strukturId . "_" . time() . "." . $file_extension;
    $target_file = $target_dir . $new_file_name;

    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        global $pdo;
        $sql = "UPDATE struktur_organisasi SET image_path = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(["assets/img/" . $new_file_name, $strukturId]);
        return ['success' => true, 'message' => 'Gambar berhasil diunggah.'];
    } else {
        return ['success' => false, 'message' => 'Gagal mengunggah gambar.'];
    }
}

function deleteStruktur($id)
{
    global $pdo;
    try {
        $stmt = $pdo->prepare("DELETE FROM struktur_organisasi WHERE id = ?");
        $stmt->execute([$id]);
        return ['success' => true, 'message' => 'Struktur berhasil dihapus.'];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()];
    }
}

function handleTupoksiSubmission($data)
{
    global $pdo;
    error_log("handleTupoksiSubmission called with data: " . print_r($data, true));
    
    $id = isset($data['id']) ? $data['id'] : null;
    $judul = $data['judul'];
    $google_drive_link = $data['google_drive_link'];

    if ($id) {
        $sql = "UPDATE tupoksi_staff SET judul = ?, google_drive_link = ? WHERE id = ?";
        $params = [$judul, $google_drive_link, $id];
    } else {
        $sql = "INSERT INTO tupoksi_staff (judul, google_drive_link) VALUES (?, ?)";
        $params = [$judul, $google_drive_link];
    }

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return ['success' => true, 'message' => ($id ? 'Tupoksi berhasil diperbarui.' : 'Tupoksi baru berhasil ditambahkan.')];
    } catch (PDOException $e) {
        error_log("Error in handleTupoksiSubmission: " . $e->getMessage());
        return ['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()];
    }
}

function deleteTupoksi($id)
{
    global $pdo;
    try {
        $stmt = $pdo->prepare("DELETE FROM tupoksi_staff WHERE id = ?");
        $stmt->execute([$id]);
        return ['success' => true, 'message' => 'Tupoksi berhasil dihapus.'];
    } catch (PDOException $e) {
        return ['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['aksi'])) {
    $response = ['success' => false, 'message' => ''];

    switch ($_POST['aksi']) {
        case 'tambah_struktur':
        case 'edit_struktur':
            $response = handleStrukturSubmission($_POST, $_FILES);
            break;
        case 'hapus_struktur':
            $response = deleteStruktur($_POST['id']);
            break;
        case 'tambah_tupoksi':
        case 'edit_tupoksi':
            $response = handleTupoksiSubmission($_POST);
            break;
        case 'hapus_tupoksi':
            $response = deleteTupoksi($_POST['id']);
            break;
        case 'tambah_staff':
        case 'edit_staff':
            $response['message'] = kelolaProfilStaff($_POST, isset($_FILES['foto']) ? $_FILES['foto'] : null);
            $response['success'] = true;
            break;
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

function getStrukturData($page = 1, $limit = 10)
{
    global $pdo;
    $offset = ($page - 1) * $limit;

    $stmt = $pdo->prepare("SELECT * FROM struktur_organisasi ORDER BY tanggal_upload DESC LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->query("SELECT COUNT(*) FROM struktur_organisasi");
    $total = $stmt->fetchColumn();

    return [
        'data' => $data,
        'totalPages' => ceil($total / $limit)
    ];
}

function getTupoksiData($page = 1, $limit = 10)
{
    global $pdo;
    $offset = ($page - 1) * $limit;

    $stmt = $pdo->prepare("SELECT * FROM tupoksi_staff ORDER BY tanggal_upload DESC LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->query("SELECT COUNT(*) FROM tupoksi_staff");
    $total = $stmt->fetchColumn();

    return [
        'data' => $data,
        'totalPages' => ceil($total / $limit)
    ];
}


// Fetch current struktur organisasi
// $stmt = $pdo->query("SELECT * FROM struktur_organisasi WHERE id = 1");
// $struktur = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch staff data
$stmt = $pdo->query("SELECT * FROM profil_staff ORDER BY jabatan");
$result_staff = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch current Tupoksi link
// $stmt = $pdo->query("SELECT * FROM tupoksi_staff ORDER BY tanggal_upload DESC LIMIT 1");
// $current_tupoksi = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch struktur organisasi data
$currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
$strukturData = getStrukturData($currentPage);
$tupoksiData = getTupoksiData($currentPage);

// Fetch staff data
$stmt = $pdo->query("SELECT * FROM profil_staff ORDER BY jabatan");
$result_staff = $stmt->fetchAll(PDO::FETCH_ASSOC);
