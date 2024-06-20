<?php
require "./backend/db_con.php";
include("./backend/authorization.php");

$loginError = "";

$countQuery = "SELECT COUNT(adId) AS adCount FROM ads";
$countStmt = $pdo->query($countQuery);
$adCount = $countStmt->fetch(PDO::FETCH_ASSOC)['adCount'];

$userid = $_SESSION['id'];

$sql = "SELECT * FROM users WHERE user_id = :user_id";
$stmt = $pdo->prepare($sql);

$stmt->bindParam(':user_id', $_SESSION['id'], PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$showWarning = empty($user['phone']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AdSpot - Dashboard</title>
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.2/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="icon" type="image" href="favico.png">
    <style>
        .nav-link{
            color: white;
            font-weight: bold;
        }
        .nav-link:focus, .nav-link:hover {
            color: #0d6efd;
        }
        #externals:hover{
        transform: scale(1.1);
        transition: transform 0.1s ease-in-out;
        color: #0d6efd;
        cursor: pointer;
      }
    </style>
</head>
<body class="bg-light" style="font-family: 'Open Sans', sans-serif;">
    <?php if ($showWarning): ?>
        <div class="alert alert-warning alert-dismissible text-center rounded-0" role="alert">
            Lūdzu <a href="profile.php" class="alert-link">dodies uz profilu</a> un atjauno informāciju!
        </div>
    <?php endif; ?>
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container-fluid ">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse bg-dark p-3 mt-2" id="navbarNav">
                <a class="navbar-brand d-flex justify-content-center align-items-center" href="dashboard.php">
                    <img src="LogoBetter.png" class="w-50" alt="Logo">
                </a>
                <ul class="navbar-nav d-flex d-sm-flex flex-sm-row flex-column align-items-center justify-content-center text-center">
                    <li class="nav-item">
                        <a class="nav-link text" href="dashboard.php">Sākums</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text" href="allads.php">Visi Sludinājumi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text" href="create_ad.php">Izveidot Sludinājumu</a>
                    </li>
                </ul>
                <?php
                if (isset($_SESSION['name']) && isset($_SESSION['lastname'])) {
                    echo '<div class="dropdown ms-auto me-sm-5 me-0 d-flex flex-row align-items-center justify-content-center">';
                    echo '<a href="#" class="nav-link nav-item dropdown-toggle text-center" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">' . $_SESSION['name'] . ' ' . $_SESSION['lastname'] . '</a>';
                    echo '<ul class="dropdown-menu mx-0" aria-labelledby="dropdownMenuLink">';
                    echo '<li><a class="dropdown-item" href="profile.php">Profils</a></li>';
                    echo '<li><a class="dropdown-item" href="messages.php">Ziņojumi</a></li>';
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
        </div>
    </nav>

<style>
@import url('https://fonts.googleapis.com/css2?family=Russo+One&family=Teko:wght@600&family=Whisper&display=swap');
</style>
    <div class="position-relative text-center text-white mt-5">
            <div class="d-inline-flex display-2 fw-bold"><h1 class="text-white mx-1">SVEICINĀTS,</h1><h1 class="text-primary mx-1 text-uppercase fw-bold"><?php echo $_SESSION['name']?>!</h1></div>

            <div class="container-fluid text-center mt-5">
                <h5 class="mt-2">Izveidoti</h5>
                <div class="display-4 text-primary"><?php echo $adCount; ?></div>
                <h5 class="mb-3">Sludinājumi</h5>

                <div class="d-flex justify-content-center mb-2 mt-4">
                    <a href="create_ad.php" class="btn btn-outline-light rounded-0 btn-lg">Izveidot sludinājumu</a>
                </div>
            </div>
    </div>

    <video id="background-video" class="w-100 d-none d-lg-block" autoplay muted loop style="position: fixed; top: 0; left: 0; z-index: -1;">
        <source src="Video.mp4" type="video/mp4">
    </video>
    
    <video id="background-video" class="d-block d-lg-none" autoplay muted loop style="position: fixed; top: 0; left: 0; z-index: -1; height:100vh;">
        <source src="Video.mp4" type="video/mp4">
    </video>


    <div class="container mt-4 mb-5">
        <div class="row mb-5 pb-5">
            <div class="col-md-6 p-5">
            </div>
            <div class="col-md-6 p-5">
            </div>
        </div>
    </div>

    <div class="container text-center p-5 mb-5">
        <div class="row">
            <div class="col-md-4">
                <div class="card text-dark bg-light mb-3" style="border-radius:0px">
                    <img src="LogoBetter.png" class="card-img-top mx-auto img-fluid" alt="Card Image" style="width: 128px; height: 128px; object-fit: contain;">
                    <div class="card-body">
                        <h5 class="card-title">Izveido savu sludinājumu</h5>
                        <p class="card-text">Publicē savu unikālo sludinājumu mūsu platformā, lai atrastu potenciālos pircējus!</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-dark bg-light mb-3" style="border-radius:0px">
                    <img src="LogoBetter.png" class="card-img-top mx-auto img-fluid" alt="Card Image" style="width: 128px; height: 128px; object-fit: contain;">
                    <div class="card-body">
                        <h5 class="card-title">Apskati pieejamos sludinājumus</h5>
                        <p class="card-text">Atrast dažādus sev nepieciešamos sludinājumus tieši šeit .</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-dark bg-light mb-3" style="border-radius:0px">
                    <img src="LogoBetter.png" class="card-img-top mx-auto img-fluid" alt="Card Image" style="width: 128px; height: 128px; object-fit: contain;">
                    <div class="card-body">
                        <h5 class="card-title">Saņem jaunumus un piedāvājumus</h5>
                        <p class="card-text">Reģistrējies, lai saņemtu jaunākos sludinājumus un izdevīgus piedāvājumus.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
<footer class="row bg-dark p-sm-5 p-1 text-white mt-5 m-0 static-bottom">
    <div class="col-md-3">
        <div class="row">
            <div class="p-0">
                <img src="LogoBetter.png" class="w-100">
            </div>
        </div>
        <div class="row g-0 mt-3">
            <label class="text-light text-opacity-25 mt-3 mb-3">AdSpot sludinājumu vietne ir labākais un efektīvākais veids, kā tev notirgot savu īpašumu vai piederīgo mantu.</label>
        </div>
    </div>
    <div class="col-md-6 d-sm-flex">
        <div class="w-sm-25 mx-auto">
        <label classs="mb-3"><strong>Pārvietoties</strong></label>
            <li class="list-group-item"><a href="dashboard.php" class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover link-light mx-sm-1">Sākums</a></li>
            <li class="list-group-item"><a href="allads.php" class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover link-light mx-sm-1">Visi sludinājumi</a></li>
            <li class="list-group-item"><a href="create_ad.php" class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover link-light mx-sm-1">Izveidot sludinājumu</a></li>
            <li class="list-group-item"><a href="profile.php" class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover link-light mx-sm-1">Profils</a></li>
            <label class="mt-3 mb-1"><strong>Informācija</strong></label>
            <li class="list-group-item"><a href="#" class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover link-light mx-sm-1">Noteikumi</a></li>
            <li class="list-group-item"><a href="#" class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover link-light mx-sm-1">Par mums</a></li>
        </div>                              
    </div>
    <div class="col-md-3">
        <div class="">
            <div class="w-100">
                <div class="text-start">
                    <label><strong>Ar mums vari sazināties šeit!</strong></label>
                </div>
                <div class="d-flex">
                    <h3 class="m-1"><i id="externals" class="fab fa-instagram-square"></i></h3>
                    <h3 class="m-1"><i id="externals" class="fab fa-facebook-square"></i></h3>
                    <h3 class="m-1"><i id="externals" class="fab fa-youtube-square"></i></h3>
                    <h3 class="m-1"><i id="externals" class="fa-brands fa-square-x-twitter"></i></h3>
                </div>
            </div>
            <div class="mt-5">
                <div class="input-group">
                    <input type="text" class="form-control rounded-5 p-2" placeholder="Vieta ieteikumam..." aria-label="Vieta ieteikumiem" aria-describedby="button-addon2">
                    <button class="btn btn-primary rounded-5 mx-4" type="button" id="button-addon2">Iesniegt</button>
                </div>
            </div>
        </div>
    </div>
    <hr class="mt-5 mb-5">
    <p class="text-center text-light italic p-0 m-0">© 2024 AdSpot</p>
</footer>


<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js"></script>
<script src="https://unpkg.com/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
</body>
</html>
