<?php
require "db_con.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $commentId = $_POST['comment_id'];
    $action = $_POST['action'];

    if (in_array($action, ['like', 'dislike'])) {
        $column = $action === 'like' ? 'likes' : 'dislikes';

        $stmt = $pdo->prepare("UPDATE comments SET $column = $column + 1 WHERE comment_id = :commentId");
        $stmt->bindParam(':commentId', $commentId, PDO::PARAM_INT);
        $stmt->execute();
        
        $stmt = $pdo->prepare("SELECT likes, dislikes FROM comments WHERE comment_id = :commentId");
        $stmt->bindParam(':commentId', $commentId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        echo json_encode([
            'success' => true,
            'likes' => $result['likes'],
            'dislikes' => $result['dislikes']
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
}
?>
