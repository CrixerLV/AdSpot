<?php
session_start();

require "db_con.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['id'])) {
    if (!isset($_POST['message']) || !isset($_POST['conversation_id'])) {
        echo json_encode(['error' => 'Missing parameters']);
        exit;
    }

    $senderId = $_SESSION['id'];
    
    $message = htmlspecialchars($_POST['message']);
    $conversationId = $_POST['conversation_id'];
    
    try {
        $stmt = $pdo->prepare("INSERT INTO messages (message, conversation_id, sender_id) VALUES (:message, :conversation_id, :sender_id)");
        $stmt->bindParam(':message', $message, PDO::PARAM_STR);
        $stmt->bindParam(':conversation_id', $conversationId, PDO::PARAM_INT);
        $stmt->bindParam(':sender_id', $senderId, PDO::PARAM_INT);
        $stmt->execute();

        echo json_encode(['success' => true, 'message' => 'Ziņa veiksmīgi nosūtīta']);

    } catch (PDOException $e) {
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Unauthorized']);
}
?>
