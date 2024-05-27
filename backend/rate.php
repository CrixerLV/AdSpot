<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['id'])) {
        header("Location: login.php");
        exit;
    }

    $rating = $_POST['rating'];
    if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
        exit;
    }

    require_once "db_con.php";

    $fromWhoId = $_SESSION['id'];
    $toWhoId = $_POST['seller_id'];

    $updateQuery = "UPDATE users SET rating = rating + :rating, ratingamount = ratingamount + 1 WHERE user_id = :userId";
    $updateVotesQuery = "INSERT INTO votes (from_who_id, to_who_id) VALUES (:fromWhoId, :toWhoId)";
    $updateStmt = $pdo->prepare($updateQuery);
    $updateVotesStmt = $pdo->prepare($updateVotesQuery);
    $updateStmt->bindParam(':rating', $rating, PDO::PARAM_INT);
    $updateStmt->bindParam(':userId', $toWhoId, PDO::PARAM_INT);
    $updateVotesStmt->bindParam(':fromWhoId', $fromWhoId, PDO::PARAM_INT);
    $updateVotesStmt->bindParam(':toWhoId', $toWhoId, PDO::PARAM_INT);
    $updateStmt->execute();
    $updateVotesStmt->execute();

    header("Location: /Adspot/SeeAd.php?adId={$_POST['ad_id']}");
    exit;
} else {
    header("Location: /Adspot/SeeAd.php");
    exit;
}
?>
