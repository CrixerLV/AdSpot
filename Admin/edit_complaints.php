<?php
require "../backend/db_con.php";
include("../backend/authorization.php");

$query = "SELECT * FROM reports";
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
    <title>Edit Complaints - AdSpot Admin Panel</title>
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.2/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body class="bg-dark text-light">
    <div class="container-fluid">
        <h1 class="text-center">Rediģēt sūdzības</h1>
        <table class="table table-dark table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Sudzība</th>
                    <th>Ziņotāja ID</th>
                    <th>Sūdzēta ID</th>
                    <th>Darbības</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reports as $row): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['report_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['report']); ?></td>
                    <td><?php echo htmlspecialchars($row['reporter_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['reported_id']); ?></td>
                    <td>
                        <a class="btn btn-outline-danger rounded-0 mx-2" onclick="return confirm('Vai tiešām vēlies dzēst šo sūdzību?');" href="backend/delete_report.php?id=<?php echo htmlspecialchars($row['report_id']); ?>"><i class="fa-solid fa-trash-can"></i></a>
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
