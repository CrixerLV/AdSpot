<?php
require "backend/db_con.php";
include("backend/authorization.php");
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
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand" href="dashboard.php">
            <img src="AdSpot.png" width="125" class="d-inline-block align-top" alt="AdSpot Logo">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="allads.php">Visi Sludinājumi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Par mums</a>
                </li>
            </ul>
            <div class="input-group me-2 w-50 px-5">
                <select class="form-select form-select-sm" id="filterDropdown" style="margin-right: 3px;">
                    <option value="All">Viss</option>
                    <option value="Cars">Automašīnas</option>
                    <option value="Furniture">Mēbeles</option>
                    <option value="Cits">Cits</option>
                </select>
                <input class="form-control w-50" type="search" placeholder="Meklē šeit" aria-label="Search">
                <button class="btn btn-light" style="margin-left: 2px;" type="submit">Search</button>
            </div>
        </div>
        <?php
          if (isset($_SESSION['name']) && isset($_SESSION['lastname'])) {
              echo '<div class="dropdown ms-auto px-2">';
              echo '<a href="#" class="text-light dropdown-toggle" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">' . $_SESSION['name'] . ' ' . $_SESSION['lastname'] . '</a>';
              echo '<ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">';
              echo '<li><a class="dropdown-item" href="profile.php">Profils</a></li>';
              echo '<li><a class="dropdown-item" href="#">Mani sludinājumi</a></li>';
              echo '<li><a class="dropdown-item" href="./backend/logout.php">Iziet</a></li>';
              echo '</ul>';
              echo '</div>';
          }
        ?>
    </div>
</nav>

<div class="container mt-4 text-white">
    <div class="row">
        <div class="col-md-6 mx-auto">
            <h2>Izveido sludinājumu</h2><br>
            <form action="create_ad_process.php" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="adName" class="form-label">Sludinājuma nosaukums</label>
                    <input type="text" class="form-control" id="adName" name="adName" required>
                </div>

                <div class="mb-3">
                    <label for="adPrice" class="form-label">Cena</label>
                    <input type="number" class="form-control" id="adPrice" name="adPrice" required>
                </div>

                <div class="mb-3">
                    <label for="adDescription" class="form-label">Apraksts</label>
                    <textarea class="form-control" id="adDescription" name="adDescription" rows="4" required></textarea>
                </div>

                <div class="mb-3">
                    <label for="adLocation" class="form-label">Atrašanās vieta</label>
                    <input type="text" class="form-control" id="adLocation" placeholder="Atrašanās vieta" name="adLocation" required>
                </div>


                <div class="mb-3">
                    <label for="adType" class="form-label">Tips</label>
                    <select class="form-select" id="adType" name="adType" required>
                        <option style="display:none">Tips</option>
                        <option value="Vehicle">Transports</option>
                        <option value="Job">Vakances</option>
                        <option value="Furniture">Mēbeles</option>
                        <option value="Pets">Dzīvnieki</option>
                        <option value="Other">Cits</option>
                    </select>
                </div>

                <div class="mb-3" id="vehicleTypeSection" style="display: none;">
                    <label for="vehicleType" class="form-label">Transports veids</label>
                    <select class="form-select" id="vehicleType" name="vehicleType">
                        <option style="display:none">Tips</option>
                        <option value="Cars">Vieglās automašīnas</option>
                        <option value="Heavy">Smagās automašīnas</option>
                        <option value="Motorcycles">Motocikli</option>
                        <option value="WaterTransport">Ūdens transports</option>
                        <option value="FarmEquipment">Lauksaimniecības tehnika</option>
                    </select>
                </div>

                <div class="mb-3" id="vehicleBrandSection" style="display: none;">
                    <label for="vehicleBrand" class="form-label">Marka</label>
                    <select class="form-select" id="vehicleBrand" name="vehicleBrand">
                        <option style="display:none">Marka</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="adImages" class="form-label">Bildes</label>
                    <input type="file" class="form-control" id="adImages" name="adImages[]" multiple accept="image/*">
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
