<?php
require "../backend/db_con.php";
include("../backend/authorization.php");

$query = "SELECT * FROM users";
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
        <h1 class="text-center">Rediģēt lietotājus</h1>
        <table class="table table-dark table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>e-pasts</th>
                    <th>Tel. Nr.</th>
                    <th>Vārds</th>
                    <th>Uzvārds</th>
                    <th>Adrese</th>
                    <th>Dzim. dati</th>
                    <th>Novērtējums</th>
                    <th>Novērtētāju skaits</th>
                    <th>Darbības</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reports as $row): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['user_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['phone']); ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['lastname']); ?></td>
                    <td><?php if(empty($row['adress'])){echo '-';} else{echo htmlspecialchars($row['adress']);} ?></td>
                    <td><?php if(empty($row['dob'])){echo '-';} else{echo htmlspecialchars($row['dob']);} ?></td>
                    <td><?php echo htmlspecialchars($row['rating']); ?></td>
                    <td><?php echo htmlspecialchars($row['ratingamount']); ?></td>
                    <td>
                        <a class="btn btn-outline-danger rounded-0 mx-2" onclick="return confirm('Vai tiešām vēlies dzēst šo lietotāju?');" href="../backend/delete_user.php?id=<?php echo htmlspecialchars($row['user_id']); ?>"><i class="fa-solid fa-trash-can"></i></a>
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
