<?php
require_once('../config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $article_id = isset($_POST['article_id']) ? intval($_POST['article_id']) : 0;
    $user_name = isset($_POST['user_name']) ? $_POST['user_name'] : '';
    $comment = isset($_POST['comment']) ? $_POST['comment'] : '';

    if ($article_id && $user_name && $comment) {
        $stmt = $pdo->prepare("INSERT INTO article_comments (article_id, user_name, comment) VALUES (:article_id, :user_name, :comment)");
        $result = $stmt->execute([
            'article_id' => $article_id,
            'user_name' => $user_name,
            'comment' => $comment
        ]);

        if ($result) {
            $comment_id = $pdo->lastInsertId();
            $stmt = $pdo->prepare("SELECT * FROM article_comments WHERE id = :id");
            $stmt->execute(['id' => $comment_id]);
            $new_comment = $stmt->fetch(PDO::FETCH_ASSOC);

            echo json_encode([
                'success' => true,
                'user_name' => htmlspecialchars($new_comment['user_name']),
                'comment' => nl2br(htmlspecialchars($new_comment['comment'])),
                'created_at' => date('d F Y H:i', strtotime($new_comment['created_at']))
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add comment']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid input']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}