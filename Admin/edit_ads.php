<?php
require "../backend/db_con.php";
include("../backend/authorization.php");

$query = "SELECT * FROM ads";
try {
    $stmt = $pdo->query($query);
    $reports = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rediģēt sludinājumus - AdSpot Admin Panel</title>
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.2/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body class="bg-dark text-light">
    <div class="container-fluid">
        <h1 class="text-center">Rediģēt sludinājumus</h1>
        <table class="table table-dark table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nosaukums</th>
                    <th>Cena</th>
                    <th>Apraksts ID</th>
                    <th>Lokācija</th>
                    <th>Tips</th>
                    <th>Tirgotāja ID</th>
                    <th>Statuss</th>
                    <th>Darbības</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reports as $row): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['adId']); ?></td>
                    <td><?php echo htmlspecialchars($row['adName']); ?></td>
                    <td><?php echo htmlspecialchars($row['adPrice']); ?></td>
                    <td><?php echo htmlspecialchars($row['adDescription']); ?></td>
                    <td><?php echo htmlspecialchars($row['adLocation']); ?></td>
                    <td><?php echo htmlspecialchars($row['adType']); ?></td>
                    <td><?php echo htmlspecialchars($row['sellerId']); ?></td>
                    <td><?php echo htmlspecialchars($row['Status']); ?></td>
                    <td>
                        <a class="btn btn-outline-danger rounded-0 mx-2" onclick="return confirm('Vai tiešām vēlies dzēst šo sūdzību?');" href="backend/Delete_Ad.php?adId=<?php echo htmlspecialchars($row['adId']); ?>"><i class="fa-solid fa-trash-can"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js"></script>
    <script src="https://unpkg.com/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
</body>
</html>
