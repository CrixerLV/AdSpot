<?php
require "backend/db_con.php";
include("backend/authorization.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AdSpot - Sludinājuma izveide</title>
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.2/dist/css/bootstrap.min.css" />
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">Sākums</a>
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

<div class="container mt-4 text-dark">
    <div class="row">
        <div class="col-md-6 mx-auto">
            <h2>Izveido sludinājumu</h2><br>
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
                    <input type="text" class="form-control" style="border:0; border-radius:0; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);" id="adLocation" placeholder="Atrašanās vieta" name="adLocation" required>
                </div>


                <div class="mb-3">
                    <label for="adType" class="form-label" >Tips</label>
                    <select class="form-select" style="border:0; border-radius:0; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);" id="adType" name="adType" required>
                        <option style="display:none">Izvēlies</option>
                        <option value="Vehicle">Transports</option>
                        <option value="Electronic">Elektronika</option>
                        <option value="Job">Darbs un bizness</option>
                        <option value="Furniture">Mājai, dārzam</option>
                        <option value="Pets">Dzīvnieki</option>
                        <option value="Other">Cits</option>
                    </select>
                </div>

                <div class="mb-3" id="vehicleTypeSection" style="display: none;">
                    <label for="vehicleType" class="form-label">Transports veids</label>
                    <select class="form-select" style="border:0; border-radius:0; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);" id="vehicleType" name="vehicleType" required>
                        <option style="display:none">Izvēlies</option>
                        <option value="Cars">Vieglās automašīnas</option>
                        <option value="Heavy">Smagās automašīnas</option>
                        <option value="Motorcycles">Motocikli</option>
                        <option value="WaterTransport">Ūdens transports</option>
                        <option value="FarmEquipment">Lauksaimniecības tehnika</option>
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
                        <option value="Dog">Suņi</option>
                        <option value="Cat">Kaķi</option>
                        <option value="Rats">Grauzēji</option>
                        <option value="Fish">Zivtiņas</option>
                        <option value="Birds">Putni</option>
                        <option value="BigAnimals">Lauksaimniecības dzīvnieki</option>
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
                        <option value="Contact">Sakaru līdzekļi</option>
                        <option value="Life">Sadzīves tehnika</option>
                        <option value="Computer">Datori</option>
                        <option value="Audio">Audio tehnika</option>
                        <option value="Video">Video tehnika</option>
                        <option value="TV">Televizori</option>
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
                        <option value="Vacancy">Vakances</option>
                        <option value="Courses">Kursi</option>
                        <option value="Job">Meklē darbu</option>
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
                        <option value="Furniture">Mēbeles, interjers</option>
                        <option value="Art">Gleznas</option>
                        <option value="Plants">Augi</option>
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
                    <input type="file" class="form-control" style="border:0; border-radius:0; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);" id="adImages" name="adImages[]" multiple accept="image/*" required>
                </div>

                <div class="d-flex justify-content-between">                
                    <button type="submit" class="btn btn-primary">Iesniegt pārbaudei</button>
                    <button type="button" href="my_ads.php" class="btn btn-danger">Atcelt sludinājuma izveidi</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js"></script>
<script src="https://unpkg.com/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
<script src="https://maps.google.com/maps/api/js?key=AIzaSyDxTV3a6oL6vAaRookXxpiJhynuUpSccjY&libraries=places&callback=initAutocomplete" type="text/javascript"></script>
<script src="JS/type_of_ad.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var adLocationInput = document.getElementById('adLocation');
    var autocomplete = new google.maps.places.Autocomplete(adLocationInput);
});
</script>
</body>
</html>
