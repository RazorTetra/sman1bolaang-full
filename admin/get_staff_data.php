<?php
require_once('../config.php');

header('Content-Type: application/json');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    $stmt = $pdo->prepare("SELECT * FROM profil_staff WHERE id = ?");
    $stmt->execute([$id]);
    $staff = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($staff) {
        echo json_encode($staff);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Staff not found']);
    }
} else {
    // Fetch all staff data
    $stmt = $pdo->query("SELECT * FROM profil_staff ORDER BY jabatan");
    $result_staff = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($result_staff);
}
?>