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
    <!-- Bootstrap CSS -->
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
                    <a class="nav-link" href="#">Visi Sludinājumi</a>
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
                <input class="form-control w-50" type="search" placeholder="Nissan..., Dīvans.., Krēsls.." aria-label="Search">
                <button class="btn btn-light" style="margin-left: 2px;" type="submit">Search</button>
            </div>
        </div>
        <?php
          if (isset($_SESSION['name']) && isset($_SESSION['lastname'])) {
              echo '<div class="dropdown ms-auto px-2">';
              echo '<a href="#" class="text-light dropdown-toggle" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">' . $_SESSION['name'] . ' ' . $_SESSION['lastname'] . '</a>';
              echo '<ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">';
              echo '<li><a class="dropdown-item" href="#">Profils</a></li>';
              echo '<li><a class="dropdown-item" href="#">Mani sludinājumi</a></li>';
              echo '<li><a class="dropdown-item" href="./backend/logout.php">Iziet</a></li>';
              echo '</ul>';
              echo '</div>';
          }
        ?>
    </div>
</nav>

</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js"></script>
<script src="https://unpkg.com/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
</html>
