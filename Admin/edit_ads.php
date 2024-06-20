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
    <style>
        textarea {
            width: 100%;
            height: 100px;
            resize: none;
        }
    </style>
</head>
<body class="bg-dark text-light">
    <div class="container-fluid">
        <h1 class="text-center py-4">Rediģēt sludinājumus</h1>
        <div class="table-responsive">
            <table class="table table-dark table-striped table-bordered">
                <thead class="thead-dark">
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
                        <tr id="row_<?php echo $row['adId']; ?>">
                            <td><?php echo htmlspecialchars($row['adId']); ?></td>
                            <td><textarea class="form-control" readonly><?php echo htmlspecialchars($row['adName']); ?></textarea></td>
                            <td><input type="text" class="form-control" readonly value="<?php echo htmlspecialchars($row['adPrice']); ?>"></td>
                            <td><textarea class="form-control" readonly><?php echo htmlspecialchars($row['adDescription']); ?></textarea></td>
                            <td><input type="text" class="form-control" readonly value="<?php echo htmlspecialchars($row['adLocation']); ?>"></td>
                            <td><input type="text" class="form-control" readonly value="<?php echo htmlspecialchars($row['adType']); ?>"></td>
                            <td><input type="text" class="form-control" readonly value="<?php echo htmlspecialchars($row['sellerId']); ?>"></td>
                            <td>
                                <?php if($row['Status'] == 0): ?>
                                    <a class="btn btn-outline-success rounded-0 mx-2" onclick="return confirm('Vai tiešām vēlies apstiprināt šo sludinājumu?');" href="backend/approve_ad.php?adId=<?php echo htmlspecialchars($row['adId']); ?>"><i class="fa-solid fa-square-check"></i></a>
                                <?php else: ?>
                                    <?php echo $row['Status']; ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a class="btn btn-outline-danger rounded-0 mx-2" onclick="return confirm('Vai tiešām vēlies dzēst šo sludinājumu?');" href="backend/Delete_Ad.php?adId=<?php echo htmlspecialchars($row['adId']); ?>"><i class="fa-solid fa-trash-can"></i></a>
                                <a class="btn btn-outline-warning rounded-0 mx-2 edit-btn" onclick="toggleEdit(<?php echo $row['adId']; ?>);"><i class="fa-solid fa-pen-to-square"></i></a>
                                <a class="btn btn-outline-success rounded-0 mx-2 save-btn" style="display: none;" onclick="saveChanges(<?php echo $row['adId']; ?>);"><i class="fa-solid fa-save"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js"></script>
    <script src="https://unpkg.com/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>

    <script>
    function toggleEdit(rowId) {
        var row = document.getElementById('row_' + rowId);
        var editBtn = row.querySelector('.edit-btn');
        var saveBtn = row.querySelector('.save-btn');
        var inputs = row.querySelectorAll('input[type="text"]');
        var textareas = row.querySelectorAll('textarea');
        
        if (editBtn.style.display !== 'none') {
            editBtn.style.display = 'none';
            saveBtn.style.display = 'block';
            inputs.forEach(function(input) {
                input.removeAttribute('readonly');
            });
            textareas.forEach(function(textarea) {
                textarea.removeAttribute('readonly');
            });
        } else {
            editBtn.style.display = 'block';
            saveBtn.style.display = 'none';
            inputs.forEach(function(input) {
                input.setAttribute('readonly', true);
            });
            textareas.forEach(function(textarea) {
                textarea.setAttribute('readonly', true);
            });
        }
    }

    function saveChanges(rowId) {
        var row = document.getElementById('row_' + rowId);
        var editBtn = row.querySelector('.edit-btn');
        var saveBtn = row.querySelector('.save-btn');
        var inputs = row.querySelectorAll('input[type="text"]');
        var textareas = row.querySelectorAll('textarea');
        var adId = rowId;
        
        var adName = textareas[0].value;
        var adPrice = inputs[0].value;
        var adDescription = textareas[1].value;
        var adLocation = inputs[1].value;
        var adType = inputs[2].value;
        var sellerId = inputs[3].value;
        
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                editBtn.style.display = 'block';
                saveBtn.style.display = 'none';
                inputs.forEach(function(input) {
                    input.setAttribute('readonly', true);
                });
                textareas.forEach(function(input) {
                    input.setAttribute('readonly', true);
                });
            }
        };
        xhttp.open("POST", "backend/update_ad.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("adId=" + adId + "&adName=" + adName + "&adPrice=" + adPrice + "&adDescription=" + adDescription + "&adLocation=" + adLocation + "&adType=" + adType + "&sellerId=" + sellerId);
    }
    </script>
</body>
</html>
