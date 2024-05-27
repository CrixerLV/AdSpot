<?php
session_start();
require "db_con.php";

if(isset($_GET['conversationId']) && filter_var($_GET['conversationId'], FILTER_VALIDATE_INT)) {
    $conversationId = $_GET['conversationId'];

    $sql = "SELECT messages.message, messages.sender_id, messages.sent_at, users.name, users.lastname 
            FROM messages 
            JOIN users ON messages.sender_id = users.user_id
            WHERE messages.conversation_id = :conversationId
            ORDER BY messages.sent_at ASC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':conversationId', $conversationId, PDO::PARAM_INT);
    $stmt->execute();
    
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    header('Content-Type: application/json');
    echo json_encode($messages);
} else {
    http_response_code(400);
    echo json_encode(array('error' => 'Invalid or missing conversation ID'));
}
?>
