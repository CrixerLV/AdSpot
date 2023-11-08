<?php
require "backend/db_con.php";
include("backend/authorization.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AdSpot - Profile</title>
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
                    <a class="nav-link" href="profile.php">Profils</a> <!-- Added Profile Page Link -->
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
              echo '<li><a class="dropdown-item" href="#">Profils</a></li>';
              echo '<li><a class="dropdown-item" href="#">Mani sludinājumi</a></li>';
              echo '<li><a class="dropdown-item" href="./backend/logout.php">Iziet</a></li>';
              echo '</ul>';
              echo '</div>';
          }
        ?>
    </div>
</nav>
<div class="container-sm mt-4">
    <div class="row">
        <div class="col-md bg-white rounded m-2 p-4">
            <div class="text-center">
                <img src="AdSpot.png" alt="User Image" class="img-fluid" style="max-width: 200px; cursor: pointer;" data-bs-toggle="modal" data-bs-target="#imageModal">
                <h5 class="mt-3">Vārds Uzvārds</h5>
                <h5>Komentāri</h5>
                <div id="comments">

                </div>
            </div>
        </div>

        <div class="col-md bg-white rounded m-2 p-4">
            <h5>Labot profilu</h5>
            <form>
                <div class="mb-3">
                    <label for="userEmail" class="form-label">E-pasts</label>
                    <input type="email" class="form-control" id="userEmail" value="<?php echo $userEmail; ?>">
                </div>
                <div class="mb-3">
                    <label for="firstname" class="form-label">Vārds</label>
                    <input type="text" class="form-control" id="firstname" value="<?php echo $firstname; ?>">
                </div>
                <div class="mb-3">
                    <label for="lastname" class="form-label">Uzvārds</label>
                    <input type="text" class="form-control" id="lastname" value="<?php echo $lastname; ?>">
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-md bg-white rounded m-2 p-4">
            <h5>Tavi sludinājumi</h5>
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
