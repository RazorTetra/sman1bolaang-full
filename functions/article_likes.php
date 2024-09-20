<?php
require_once('../config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $article_id = isset($_POST['article_id']) ? intval($_POST['article_id']) : 0;
    $user_ip = $_SERVER['REMOTE_ADDR'];

    // Check if the user has already liked the article
    $stmt = $pdo->prepare("SELECT id FROM article_likes WHERE article_id = :article_id AND user_ip = :user_ip");
    $stmt->execute(['article_id' => $article_id, 'user_ip' => $user_ip]);

    if (!$stmt->fetch()) {
        // If not liked, add a new like
        $stmt = $pdo->prepare("INSERT INTO article_likes (article_id, user_ip) VALUES (:article_id, :user_ip)");
        $stmt->execute(['article_id' => $article_id, 'user_ip' => $user_ip]);
    }

    // Get the updated like count
    $stmt = $pdo->prepare("SELECT COUNT(*) as like_count FROM article_likes WHERE article_id = :article_id");
    $stmt->execute(['article_id' => $article_id]);
    $like_count = $stmt->fetchColumn();

    echo json_encode(['success' => true, 'likes' => $like_count]);
} else {
    echo json_encode(['success' => false]);
}