<?php
require "./backend/db_con.php";
include("./backend/authorization.php");


$countQuery = "SELECT COUNT(adId) AS adCount FROM ads";
$countStmt = $pdo->query($countQuery);
$adCount = $countStmt->fetch(PDO::FETCH_ASSOC)['adCount'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AdSpot - Admin Panel</title>
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.2/dist/css/bootstrap.min.css" />
</head>
<body class="bg-dark">
    <div class="container-fluid">
    <nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="dashboard.php">AdSpot Admin Panel</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="Admin\edit_complaints.php">Rediģēt Sūdzības</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="Admin\edit_users.php">Rediģēt Lietotājus</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="Admin\edit_ads.php">Rediģēt Sludinājumus</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
    <main class="w-100">
        <div class="position-relative text-center text-white mt-5">
            <div id="dynamic-content">
                <embed src="Admin\edit_complaints.php"></embed>
            </div>
        </div>
    </main>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js"></script>
    <script src="https://unpkg.com/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
</body>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    $(document).ready(function () {
        $('.nav-link').on('click', function () {
            var href = $(this).attr('href');
            $('#dynamic-content').load(href);
            return false;
        });
    });
</script>

</html>
