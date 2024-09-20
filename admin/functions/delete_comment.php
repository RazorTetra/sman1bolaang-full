<?php
require_once('../../config.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comment_id = isset($_POST['comment_id']) ? intval($_POST['comment_id']) : 0;

    if ($comment_id) {
        $stmt = $pdo->prepare("DELETE FROM article_comments WHERE id = :comment_id");
        $result = $stmt->execute(['comment_id' => $comment_id]);

        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete comment']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid comment ID']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>