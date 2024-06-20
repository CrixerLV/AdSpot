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
    <title>Lietotāji - AdSpot Admin Panelis</title>
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.2/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        textarea, input {
            width: 100%;
            resize: none;
        }
    </style>
</head>
<body class="bg-dark text-light">
    <div class="container-fluid">
        <h1 class="text-center py-4">Lietotāji</h1>
        <div class="table-responsive">
            <table class="table table-dark table-striped table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>E-pasts</th>
                        <th>Talr. Nr.</th>
                        <th>Vārds</th>
                        <th>Uzvārds</th>
                        <th>Adrese</th>
                        <th>DOB</th>
                        <th>Novērtējums</th>
                        <th>Vērtētāju skaits</th>
                        <th>Administrators</th>
                        <th>Darbības</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reports as $row): ?>
                    <tr id="row_<?php echo htmlspecialchars($row['user_id']); ?>">
                        <td><?php echo htmlspecialchars($row['user_id']); ?></td>
                        <td><input type="text" class="form-control" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" readonly></td>
                        <td><input type="text" class="form-control" name="phone" value="<?php echo htmlspecialchars($row['phone']); ?>" readonly></td>
                        <td><input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($row['name']); ?>" readonly></td>
                        <td><input type="text" class="form-control" name="lastname" value="<?php echo htmlspecialchars($row['lastname']); ?>" readonly></td>
                        <td><input type="text" class="form-control" name="adress" value="<?php if(empty($row['adress'])){echo '-';} else{echo htmlspecialchars($row['adress']);} ?>" readonly></td>
                        <td><input type="text" class="form-control" name="dob" value="<?php if(empty($row['dob'])){echo '-';} else{echo htmlspecialchars($row['dob']);} ?>" readonly></td>
                        <td><?php echo htmlspecialchars($row['rating']); ?></td>
                        <td><?php echo htmlspecialchars($row['ratingamount']); ?></td>
                        <td><?php echo htmlspecialchars($row['Admin']); ?></td>
                        <td>
                            <a class="btn btn-outline-danger rounded-0 mx-2" onclick="return confirm('Vai tiešām vēlies dzēst šo lietotāju?');" href="backend/delete_user.php?user_id=<?php echo htmlspecialchars($row['user_id']); ?>"><i class="fa-solid fa-trash-can"></i></a>
                            <button class="btn btn-outline-warning rounded-0 mx-2 edit-btn" onclick="toggleEdit(<?php echo $row['user_id']; ?>);"><i class="fa-solid fa-pen-to-square"></i></button>
                            <button class="btn btn-outline-success rounded-0 mx-2 save-btn" style="display: none;" onclick="saveChanges(<?php echo $row['user_id']; ?>);"><i class="fa-solid fa-save"></i></button>
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
        
        if (editBtn.style.display !== 'none') {
            editBtn.style.display = 'none';
            saveBtn.style.display = 'block';
            inputs.forEach(function(input) {
                input.removeAttribute('readonly');
            });
        } else {
            editBtn.style.display = 'block';
            saveBtn.style.display = 'none';
            inputs.forEach(function(input) {
                input.setAttribute('readonly', true);
            });
        
        }
    }

    function saveChanges(rowId) {
        var row = document.getElementById('row_' + rowId);
        var editBtn = row.querySelector('.edit-btn');
        var saveBtn = row.querySelector('.save-btn');
        var inputs = row.querySelectorAll('input[type="text"]');
        var userId = rowId;

        var email = inputs[0].value;
        var phone = inputs[1].value;
        var name = inputs[2].value;
        var lastname = inputs[3].value;
        var adress = inputs[4].value;
        var dob = inputs[5].value;

        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                editBtn.style.display = 'block';
                saveBtn.style.display = 'none';
                inputs.forEach(function(input) {
                    input.setAttribute('readonly', true);
                });
            }
        };
        xhttp.open("POST", "backend/update_user.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("user_id=" + userId + "&email=" + email + "&phone=" + phone + "&name=" + name + "&lastname=" + lastname + "&adress=" + adress + "&dob=" + dob);
    }
    </script>
</body>
</html>
