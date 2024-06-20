<?php
require "db_con.php";
include("authorization.php");

if(isset($_GET['adId'])) {
    $adId = $_GET['adId'];
    
    $sql = "DELETE FROM ads WHERE adId = :adId AND sellerId = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':adId', $adId, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $_SESSION['id'], PDO::PARAM_INT);

    if ($stmt->execute()) {
        header("Location: /user_ads.php");
        exit();
    } else {
        echo "Error deleting ad.";
    }
} else {
    header("Location: /user_ads.php");
    exit();
}
?>
