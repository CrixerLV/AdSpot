<?php
require "./backend/db_con.php";
include("./backend/authorization.php");

$loginError = "";

$countQuery = "SELECT COUNT(adId) AS adCount FROM ads";
$countStmt = $pdo->query($countQuery);
$adCount = $countStmt->fetch(PDO::FETCH_ASSOC)['adCount'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AdSpot - Dashboard</title>
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.2/dist/css/bootstrap.min.css" />
</head>
<body class="bg-dark">
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link text-white" href="dashboard.php">Sākums</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="allads.php">Visi Sludinājumi</a>
                </li>
            </ul>
            <form method="get" action="allads.php" class="form-inline mx-2">
                <div class="input-group">
                    <input class="form-control mx-1" style="width: 300px;" type="search" name="search" placeholder="Meklē pēc nosaukuma" aria-label="Search">
                    <button class="btn btn-light" type="submit">Meklēt</button>
                </div>
            </form>
        </div>
        <?php
            if (isset($_SESSION['name']) && isset($_SESSION['lastname'])) {
                echo '<div class="dropdown ms-auto px-2">';
                echo '<a href="#" class="nav-link nav-item text-white dropdown-toggle" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">' . $_SESSION['name'] . ' ' . $_SESSION['lastname'] . '</a>';
                echo '<ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">';
                echo '<li><a class="dropdown-item" href="profile.php">Profils</a></li>';
                echo '<li><a class="dropdown-item" href="user_ads.php">Mani sludinājumi</a></li>';

                $adminCheckSql = "SELECT admin FROM users WHERE user_id = :user_id";
                $adminCheckStmt = $pdo->prepare($adminCheckSql);
                $adminCheckStmt->bindParam(':user_id', $_SESSION['id'], PDO::PARAM_INT);
                $adminCheckStmt->execute();
                $isAdmin = $adminCheckStmt->fetchColumn();

                if ($isAdmin) {
                    echo '<li><a class="dropdown-item" href="admin_panel.php">Admina panelis</a></li>';
                }

                echo '<li><a class="dropdown-item" href="./backend/logout.php">Iziet</a></li>';
                echo '</ul>';
                echo '</div>';
            }
        ?>
    </div>
</nav>

<style>
@import url('https://fonts.googleapis.com/css2?family=Russo+One&family=Teko:wght@600&family=Whisper&display=swap');
</style>
<div class="position-relative text-center text-white mt-5">
        <div class="d-inline-flex display-2 fw-bold"><h1 class="text-white mx-1">SVEICINĀTS,</h1><h1 class="text-primary mx-1 text-uppercase"><?php echo $_SESSION['name']?>!</h1></div>

        <div class="container mt-5">
            <h5 class="mt-2">Izveidoti jau</h5>
            <div class="display-4 text-info"><?php echo $adCount; ?></div>
            <h5 class="mb-3">Sludinājumi</h5>

            <div class="d-flex justify-content-center mb-2">
                <a href="create_ad.php" class="btn btn-primary me-3 btn-lg">Izveido savu sludinājumu</a>
                <a href="user_ads.php" class="btn btn-primary btn-lg">Apskati savus sludinājumus</a>
            </div>
        </div>
    </div>

    <video id="background-video" class="w-100" autoplay muted loop style="position: fixed; top: 0; left: 0; z-index: -1;">
        <source src="Video.mp4" type="video/mp4">
    </video>


<div class="container mt-4">
    <div class="row">
        <div class="col-md-6 p-5">
        </div>
        <div class="col-md-6 p-5">
        </div>
    </div>
</div>

<div class="container mt-4 text-center" style="margin-top: 20%;">
    <div class="row">
        <div class="col-md-4">
            <div class="card text-dark bg-light mb-3" style="border-radius:0px">
                <img src="Logo.png" class="card-img-top mx-auto img-fluid" alt="Card Image" style="width: 128px; height: 128px; object-fit: contain;">
                <div class="card-body">
                    <h5 class="card-title">Izveido savu sludinājumu</h5>
                    <p class="card-text">Publicē savu unikālo sludinājumu mūsu platformā, lai atrastu potenciālos pircējus!</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-dark bg-light mb-3" style="border-radius:0px">
                <img src="Logo.png" class="card-img-top mx-auto img-fluid" alt="Card Image" style="width: 128px; height: 128px; object-fit: contain;">
                <div class="card-body">
                    <h5 class="card-title">Apskati pieejamos sludinājumus</h5>
                    <p class="card-text">Atrast dažādus sev nepieciešamos sludinājumus tieši šeit .</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-dark bg-light mb-3" style="border-radius:0px">
                <img src="Logo.png" class="card-img-top mx-auto img-fluid" alt="Card Image" style="width: 128px; height: 128px; object-fit: contain;">
                <div class="card-body">
                    <h5 class="card-title">Saņem jaunumus un piedāvājumus</h5>
                    <p class="card-text">Reģistrējies, lai saņemtu jaunākos sludinājumus un izdevīgus piedāvājumus.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<footer class="bg-primary text-muted fixed-bottom text-center p-2">
    &copy; 2023 AdSpot
</footer>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js"></script>
<script src="https://unpkg.com/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
</body>
</html>
