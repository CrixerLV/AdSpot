<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['id'])) {
        header("Location: login.php");
        exit;
    }

    $report = $_POST['report'];
    if (empty($report)) {
        exit;
    }

    require_once "db_con.php";

    $reporter_id = $_POST['reporter_id'];
    $seller_id = $_POST['seller_id'];

    $updateQuery = "INSERT INTO reports (report, reported_id, reporter_id) VALUES (:report, :reported_id, :reporter_id)";
    $updateStmt = $pdo->prepare($updateQuery);
    $updateStmt->bindParam(':report', $report, PDO::PARAM_STR_CHAR);
    $updateStmt->bindParam(':reported_id', $seller_id, PDO::PARAM_INT);
    $updateStmt->bindParam(':reporter_id', $reporter_id, PDO::PARAM_INT);
    $updateStmt->execute();

    header("Location: /Adspot/SeeAd.php?adId={$_POST['ad_id']}");
    exit;
} else {
    header("Location: /Adspot/SeeAd.php");
    exit;
}
?>
