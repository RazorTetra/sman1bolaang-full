<?php
require_once('../../config.php');

$article_id = isset($_GET['article_id']) ? intval($_GET['article_id']) : 0;

if ($article_id) {
    $stmt = $pdo->prepare("SELECT * FROM article_comments WHERE article_id = :article_id ORDER BY created_at DESC");
    $stmt->execute(['article_id' => $article_id]);
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($comments) > 0) {
        foreach ($comments as $comment) {
            echo '<div class="comment-item bg-gray-100 p-4 rounded mb-4">';
            echo '<p class="font-bold">' . htmlspecialchars($comment['user_name']) . '</p>';
            echo '<p class="text-sm text-gray-600">' . date('d F Y H:i', strtotime($comment['created_at'])) . '</p>';
            echo '<p class="mt-2">' . nl2br(htmlspecialchars($comment['comment'])) . '</p>';
            echo '<button class="delete-comment text-red-500 hover:text-red-700 mt-2" data-comment-id="' . $comment['id'] . '">Hapus</button>';
            echo '</div>';
        }
    } else {
        echo '<p>Tidak ada komentar untuk artikel ini.</p>';
    }
} else {
    echo '<p>ID artikel tidak valid.</p>';
}
?>