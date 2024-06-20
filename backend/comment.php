<?php
require "db_con.php";
include("authorization.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $comment = $_POST["comment"];
    $user_id = $_SESSION['id'];
    $ad_id = $_POST["ad_id"];

    if (!empty($comment)) {
        $insertQuery = "INSERT INTO comments (comment, likes, dislikes, comenteer_id, ad_id) VALUES (:comment, 0, 0, :comenteer_id, :ad_id)";
        $insertStmt = $pdo->prepare($insertQuery);
        $insertStmt->bindParam(':comment', $comment);
        $insertStmt->bindParam(':comenteer_id', $user_id);
        $insertStmt->bindParam(':ad_id', $ad_id);
        
        if ($insertStmt->execute()) {
            header("Location: /SeeAd.php?adId=$ad_id");
            exit();
        } else {
            echo "Error inserting comment.";
        }
    } else {
        header("Location: /SeeAd.php?adId=$ad_id");
    }
}
?>
