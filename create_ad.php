<?php
require "backend/db_con.php";
include("backend/authorization.php");


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
    <title>AdSpot - Sludinājuma izveide</title>
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
            Lūdzu pirms veido sludinājumu <a href="profile.php" class="alert-link">dodies uz profilu</a> un atjauno informāciju!
        </div>
    <?php endif; ?>
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
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
                    echo '<div class="dropdown ms-auto me-lg-5 d-flex flex-row align-items-center justify-content-center">';
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

    <div class="container mt-4 text-dark" style="min-height: 100vh; margin-bottom: 80px;">
    <div class="row mb-5 pb-5">
        <div class="col-md-6 mx-auto border border rounded-2 p-5">
            <h2 class="text-center fw-bold">Sludinājuma izveide</h2><br>
            <hr>
            <form action="backend/create_ad_process.php" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="adName" class="form-label">Sludinājuma nosaukums</label>
                    <input type="text" class="form-control" style="border:0; border-radius:0; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);" id="adName" name="adName" required>
                </div>

                <div class="mb-3">
                    <label for="adPrice" class="form-label">Cena</label>
                    <input type="number" class="form-control"  style="border:0; border-radius:0; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);"  id="adPrice" name="adPrice" required>
                </div>

                <div class="mb-3">
                    <label for="adDescription" class="form-label">Apraksts</label>
                    <textarea class="form-control overflow-auto" style="resize:none; border:0; border-radius:0; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);" id="adDescription" name="adDescription" rows="4" required></textarea>
                </div>

                <div class="mb-3">
                    <label for="adLocation" class="form-label">Atrašanās vieta</label>
                    <select class="form-select" id="adLocation" style="border:0; border-radius:0; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);"  name="adLocation" required>
                        <option value="" disabled selected>Izvēlieties atrašanās vietu</option>
                            <option value="Alūksne">Alūksne</option>
                            <option value="Bauska">Bauska</option>
                            <option value="Cēsis">Cēsis</option>
                            <option value="Daugavpils">Daugavpils</option>
                            <option value="Dobele">Dobele</option>
                            <option value="Gulbene">Gulbene</option>
                            <option value="Jelgava">Jelgava</option>
                            <option value="Jēkabpils">Jēkabpils</option>
                            <option value="Jūrmala">Jūrmala</option>
                            <option value="Krāslava">Krāslava</option>
                            <option value="Kuldīga">Kuldīga</option>
                            <option value="Liepāja">Liepāja</option>
                            <option value="Madona">Madona</option>
                            <option value="Ogre">Ogre</option>
                            <option value="Rēzekne">Rēzekne</option>
                            <option value="Rīga">Rīga</option>
                            <option value="Saldus">Saldus</option>
                            <option value="Sigulda">Sigulda</option>
                            <option value="Tukums">Tukums</option>
                            <option value="Valmiera">Valmiera</option>
                            <option value="Ventspils">Ventspils</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="adType" class="form-label" >Tips</label>
                    <select class="form-select" style="border:0; border-radius:0; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);" id="adType" name="adType" required>
                        <option style="display:none">Izvēlies</option>
                        <option value="Transports">Transports</option>
                        <option value="Elektronika">Elektronika</option>
                        <option value="Darbs un bizness">Darbs un bizness</option>
                        <option value="Mēbeles">Mājai, dārzam</option>
                        <option value="Dzīvnieki">Dzīvnieki</option>
                        <option value="Cits">Cits</option>
                    </select>
                </div>

                <div class="mb-3" id="vehicleTypeSection" style="display: none;">
                    <label for="vehicleType" class="form-label">Transports veids</label>
                    <select class="form-select" style="border:0; border-radius:0; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);" id="vehicleType" name="vehicleType" required>
                        <option style="display:none">Izvēlies</option>
                        <option value="Vieglā automašina">Vieglās automašīnas</option>
                        <option value="Smagā automašīna">Smagās automašīnas</option>
                        <option value="Motocikls">Motocikli</option>
                        <option value="Ūdens transports">Ūdens transports</option>
                        <option value="Lauksaimniecības tehnika">Lauksaimniecības tehnika</option>
                    </select>
                </div>

                <div class="mb-3" id="vehicleBrandSection" style="display: none;">
                    <label for="vehicleBrand" class="form-label">Marka</label>
                    <select class="form-select" style="border:0; border-radius:0; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);" id="vehicleBrand" name="vehicleBrand" required>
                        <option style="display:none">Marka</option>
                    </select>
                </div>


                <div class="mb-3" id="petTypeSection" style="display: none;">
                    <label for="petType" class="form-label">Dzīvnieka tips</label>
                    <select class="form-select" style="border:0; border-radius:0; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);" id="petType" name="petType" required>
                        <option style="display:none">Izvēlies</option>
                        <option value="Suns">Suņi</option>
                        <option value="Kaķi">Kaķi</option>
                        <option value="Grauzējs">Grauzēji</option>
                        <option value="Zivs">Zivtiņas</option>
                        <option value="Putns">Putni</option>
                        <option value="Lauksaimniecības dzīvnieks">Lauksaimniecības dzīvnieki</option>
                    </select>
                </div>

                <div class="mb-3" id="petBrandSection" style="display: none;">
                    <label for="petBrand" class="form-label">Škirne</label>
                    <select class="form-select" style="border:0; border-radius:0; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);" id="petBrand" name="petBrand" required>
                        <option style="display:none">Šķirne</option>
                    </select>
                </div>

                <div class="mb-3" id="electronicTypeSection" style="display: none;">
                    <label for="electronicType" class="form-label">Eletronikas tips</label>
                    <select class="form-select" style="border:0; border-radius:0; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);" id="electronicType" name="electronicType" required>
                        <option style="display:none">Izvēlies</option>
                        <option value="Sakaru līdzeklis">Sakaru līdzekļi</option>
                        <option value="Sadzīves tehnika">Sadzīves tehnika</option>
                        <option value="Dators">Datori</option>
                        <option value="Audio tehnika">Audio tehnika</option>
                        <option value="Video tehnika">Video tehnika</option>
                        <option value="Televizors">Televizori</option>
                    </select>
                </div>

                <div class="mb-3" id="electronicBrandSection" style="display: none;">
                    <label for="electronicBrand" class="form-label">Veids</label>
                    <select class="form-select" style="border:0; border-radius:0; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);" id="electronicBrand" name="electronicBrand" required>
                        <option style="display:none">Veids</option>
                    </select>
                </div>

                <div class="mb-3" id="JobTypeSection" style="display: none;">
                    <label for="JobType" class="form-label">Darbs un bizness</label>
                    <select class="form-select" style="border:0; border-radius:0; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);" id="JobType" name="JobType" required>
                        <option style="display:none">Izvēlies</option>
                        <option value="Vakance">Vakances</option>
                        <option value="Kursi">Kursi</option>
                        <option value="Darbs un bizness">Meklē darbu</option>
                    </select>
                </div>

                <div class="mb-3" id="JobBrandSection" style="display: none;">
                    <label for="JobBrand" class="form-label">Veids</label>
                    <select class="form-select" style="border:0; border-radius:0; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);" id="JobBrand" name="JobBrand" required>
                        <option style="display:none">Izvēlies</option>
                    </select>
                </div>

                <div class="mb-3" id="FurnitureTypeSection" style="display: none;">
                    <label for="FurnitureType" class="form-label">Mājai</label>
                    <select class="form-select" style="border:0; border-radius:0; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);" id="FurnitureType" name="FurnitureType" required>
                        <option style="display:none">Izvēlies</option>
                        <option value="Mājai, dārzam">Mēbeles, interjers</option>
                        <option value="Gleznas">Gleznas</option>
                        <option value="Augi">Augi</option>
                    </select>
                </div>

                <div class="mb-3" id="FurnitureBrandSection" style="display: none;">
                    <label for="FurnitureBrand" class="form-label">Veids</label>
                    <select class="form-select" style="border:0; border-radius:0; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);" id="FurnitureBrand" name="FurnitureBrand" required>
                        <option style="display:none">Izvēlies</option>
                    </select>
                </div>

                <div class="mb-3" id="otherTypeSection" style="display: none;">
                    <label for="otherType" class="form-label">Cits</label>
                    <input class="form-control" style="border:0; border-radius:0; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);" id="otherBrand" name="othersBrand"></input required>
                </div>

                <div class="mb-3">
                    <label for="adImages" class="form-label">Bildes</label>
                    <input type="file" class="form-control" maxlength="5" style="border:0; border-radius:0; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);" id="adImages" name="adImages[]" multiple accept="image/*" required>
                </div>


                <div class="d-flex flex-column flex-lg-row justify-content-between">                
                    <button type="submit" class="btn btn-primary mb-3 mb-lg-0">Iesniegt pārbaudei</button>
                    <button type="button" onclick="window.location.href='dashboard.php';" class="btn btn-danger">Atcelt sludinājuma izveidi</button>
                </div>
            </form>
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
        <div class="w-50">

        </div>
        <div class="w-sm-25">
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
<script src="https://maps.google.com/maps/api/js?key=AIzaSyDxTV3a6oL6vAaRookXxpiJhynuUpSccjY&libraries=places&callback=initAutocomplete" type="text/javascript"></script>
<script src="JS/type_of_ad.js"></script>
</body>
</html>
