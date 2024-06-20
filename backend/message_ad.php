<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['id'])) {
        header("Location: /Adspot/login.php");
        exit;
    }

    $message = $_POST['message'];
    $messager_id = $_SESSION['id'];
    $seller_id = $_POST['seller_id'];
    $ad_id = $_POST['ad_id'];

    if (empty($message)) {
        exit;
    }

    require_once "db_con.php";

    try {
        $pdo->beginTransaction();

        $conversationQuery = "SELECT conversation_id FROM Conversations WHERE (user1_id = :user1_id AND user2_id = :user2_id) OR (user1_id = :user2_id AND user2_id = :user1_id)";
        $conversationStmt = $pdo->prepare($conversationQuery);
        $conversationStmt->bindParam(':user1_id', $messager_id, PDO::PARAM_INT);
        $conversationStmt->bindParam(':user2_id', $seller_id, PDO::PARAM_INT);
        $conversationStmt->execute();
        $conversation = $conversationStmt->fetch(PDO::FETCH_ASSOC);

        if ($conversation) {
            $conversation_id = $conversation['conversation_id'];
        } else {
            $createConversationQuery = "INSERT INTO Conversations (user1_id, user2_id) VALUES (:user1_id, :user2_id)";
            $createConversationStmt = $pdo->prepare($createConversationQuery);
            $createConversationStmt->bindParam(':user1_id', $messager_id, PDO::PARAM_INT);
            $createConversationStmt->bindParam(':user2_id', $seller_id, PDO::PARAM_INT);
            $createConversationStmt->execute();
            $conversation_id = $pdo->lastInsertId();
        }

        $insertMessageQuery = "INSERT INTO Messages (message, conversation_id, sender_id) VALUES (:message, :conversation_id, :sender_id)";
        $insertMessageStmt = $pdo->prepare($insertMessageQuery);
        $insertMessageStmt->bindParam(':message', $message, PDO::PARAM_STR);
        $insertMessageStmt->bindParam(':conversation_id', $conversation_id, PDO::PARAM_INT);
        $insertMessageStmt->bindParam(':sender_id', $messager_id, PDO::PARAM_INT);
        $insertMessageStmt->execute();

        $pdo->commit();

        header("Location: /SeeAd.php?adId={$ad_id}");
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Failed: " . $e->getMessage();
    }
} else {
    header("Location: /SeeAd.php");
    exit;
}
?>
