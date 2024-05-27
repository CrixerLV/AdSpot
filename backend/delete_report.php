<?php
require "db_con.php";
include("authorization.php");

if (isset($_GET['id'])) {
    $report_id = $_GET['id'];

    $query = "DELETE FROM reports WHERE report_id = :report_id";
    $stmt = $pdo->prepare($query);
    
    try {
        $stmt->execute(['report_id' => $report_id]);
        exit();
    } catch (PDOException $e) {
        die("Deletion failed: " . $e->getMessage());
    }
} else {
    die("Invalid request.");
}
?>
