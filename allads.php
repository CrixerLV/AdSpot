<?php
require "backend/db_con.php";
include("backend/authorization.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AdSpot - Sludinājumi</title>
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
                    <a class="nav-link" href="allads.php">Visi Sludinājumi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="aboutus.php">Par mums</a>
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

<div class="container mt-4">
    <form method="post">
        <div class="row mb-3">
            <div class="col-md-3">
                <label class="text-white" for="priceRange">Max cena $</label>
                <input type="range" class="form-range" id="priceRange" name="priceRange" min="0" max="1000000">
                <div class="text-white" id="priceRangeValue"></div>
            </div>
            <div class="col-md-3">
                <label class="text-white" for="year">Gads</label>
                <input type="text" class="form-control" id="year" name="year">
            </div>
            <div class="col-md-3">
                <label>&nbsp;</label>
                <button type="submit" class="btn btn-primary btn-block">Filtrēt</button>
            </div>
        </div>
    </form>

    <?php
    try {
        $query = "SELECT * FROM ads WHERE 1";
        if (isset($_POST['priceFrom']) && $_POST['priceFrom'] != "") {
            $query .= " AND price >= " . intval($_POST['priceFrom']);
        }
        if (isset($_POST['priceTo']) && $_POST['priceTo'] != "") {
            $query .= " AND price <= " . intval($_POST['priceTo']);
        }
        if (isset($_POST['year']) && $_POST['year'] != "") {
            $query .= " AND year = " . intval($_POST['year']);
        }
        $stmt = $pdo->query($query);

        if ($stmt) {
            echo '
            <table class="table table-dark table-striped">
                <thead>
                    <tr>
                        <th>Nr.</th>
                        <th>Bilde</th>
                        <th>Nosaukums</th>
                        <th>Apraksts</th>
                        <th>Kategorija</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>';
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<tr>';
                echo '<td>' . $row['Ad_id'] . '</td>';
                echo '<td>' . $row['Img'] . '</td>';
                echo '<td>' . $row['Name'] . '</td>';
                echo '<td>' . $row['Description'] . '</td>';
                echo '<td>' . $row['Type'] . '</td>';
                echo '<td><button class="btn btn-outline-primary">Apskatīt</button></td>';
                echo '</tr>';
            }
            echo '</tbody>
            </table>';
        } else {
            echo "Error fetching ads from the database: " . print_r($pdo->errorInfo(), true);
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    ?>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js"></script>
<script src="https://unpkg.com/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
<script>
    const priceRange = document.getElementById('priceRange');
    const priceRangeValue = document.getElementById('priceRangeValue');
    priceRange.addEventListener('input', () => {
        priceRangeValue.innerText = priceRange.value;
    });
</script>
</body>
</html>
