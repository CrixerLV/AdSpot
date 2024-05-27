<?php
require "backend/db_con.php";
include("backend/authorization.php");

$userId = $_SESSION['id'];

$sql = "SELECT conversations.conversation_id, users.name, users.lastname, user_images.path, messages.message, messages.sender_id, messages.sent_at 
        FROM conversations 
        JOIN users ON (conversations.user1_id = users.user_id OR conversations.user2_id = users.user_id)
        LEFT JOIN user_images ON users.user_id = user_images.user_id
        JOIN messages ON conversations.conversation_id = messages.conversation_id 
        WHERE (conversations.user1_id = :userId OR conversations.user2_id = :userId) 
        AND users.user_id != :userId
        ORDER BY messages.sent_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':userId', $userId);
$stmt->execute();

$conversations = $stmt->fetchAll(PDO::FETCH_ASSOC);

$uniqueConversations = [];
foreach ($conversations as $conversation) {
    $conversationId = $conversation['conversation_id'];
    if (!isset($uniqueConversations[$conversationId])) {
        $uniqueConversations[$conversationId] = $conversation;
    }
}
$conversations = array_values($uniqueConversations);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AdSpot - Ziņojumi</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.2/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="icon" type="image" href="favico.png">

    <style>
        .nav-link{
            color: white;
            font-weight: bold;
        }
        .nav-link:focus, .nav-link:hover {
            color: #0d6efd;
        }
        #externals:hover{
        transform: scale(1.1);
        transition: transform 0.1s ease-in-out;
        color: #0d6efd;
        cursor: pointer;
        }
        #convo_card:hover{
            background-color: whitesmoke;
        }
    </style>

</head>

<body class="bg-light" style="font-family: 'Open Sans', sans-serif;">
<nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container-fluid ">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse bg-dark p-3 mt-2" id="navbarNav">
                <a class="navbar-brand d-flex justify-content-center align-items-center" href="dashboard.php">
                    <img src="LogoBetter.png" class="w-50" alt="Logo">
                </a>
                <ul class="navbar-nav d-flex d-sm-flex flex-sm-row flex-column align-items-center justify-content-center text-center">
                    <li class="nav-item">
                        <a class="nav-link text" href="dashboard.php">Sākums</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text" href="allads.php">Visi Sludinājumi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text" href="create_ad.php">Izveidot Sludinājumu</a>
                    </li>
                </ul>
                <?php
                if (isset($_SESSION['name']) && isset($_SESSION['lastname'])) {
                    echo '<div class="dropdown ms-auto me-5 d-flex flex-row align-items-center justify-content-center">';
                    echo '<a href="#" class="nav-link nav-item dropdown-toggle text-center" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">' . $_SESSION['name'] . ' ' . $_SESSION['lastname'] . '</a>';
                    echo '<ul class="dropdown-menu mx-0" aria-labelledby="dropdownMenuLink">';
                    echo '<li><a class="dropdown-item" href="profile.php">Profils</a></li>';
                    echo '<li><a class="dropdown-item" href="messages.php">Ziņojumi</a></li>';
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
        </div>
    </nav>
    <div class="container-fluid container-lg mt-5" style="min-height:100vh;">
        <div class="row text-center bg-white">
            <div class="col-md-3 border-end">
                <h1 class="h5 py-2"><strong>Sarakstes</strong></h1>
                <div class="w-100 mb-3">
                    <input class="form-control rounded py-2" type="search" value="Meklē..." id="search-input">
                </div>
                <div class="list-group user-list" id="user-list">
                <?php foreach ($conversations as $conversation) : ?>
                    <?php
                    $user_id = $_SESSION['id'];
                    $messager_id = $conversation['sender_id'];
                    $message = ($userId == $messager_id) ? "<<" : ">>";
                    ?>
                    <div class="card border-0 p-0 mt-2 conversation-card" data-conversation-id="<?php echo $conversation['conversation_id']; ?>" onclick="showMessages(<?php echo $conversation['conversation_id']; ?>)">
                        <div class="card-body p-2" style="cursor:pointer;">
                            <div class="row align-items-center">
                                <div class="col-3 p-0">
                                    <img src="User_Images/<?php echo $conversation['path'] ?? '../unknown.jpg'; ?>" style="width:50px; height:50px">
                                </div>
                                <div class="col-6 p-0 text-start">
                                    <h1 class="h5 m-0"><?php echo $conversation['name'] . ' ' . $conversation['lastname']; ?></h1>
                                    <p class="m-0 text-muted"><i class="text-primary fw-bold"><?php echo $message; ?></i> <?php echo $conversation['message']; ?></p>
                                </div>
                            </div>
                            <hr>
                        </div>
                    </div>
                <?php endforeach; ?>
                </div>
            </div>
            <div class="col-md-9">
                <div class="row p-3">
                    <div class="card message-box border-0 rounded-0 bg-light">
                        <div class="card-body p-3 overflow-auto" style="max-height: 50vh;" id="messages">
                            <h5 class="text-warning">Izvēlies saraksti!</h5>
                        </div>
                    </div>
                </div>
                <div class="row p-3">
                    <form action="./backend/send_message.php" method="POST" id="sendMessageForm">
                        <input type="hidden" name="conversation_id" value="<?php echo $conversation['conversation_id']; ?>">
                        <div class="input-group mt-3">
                            <input type="text" name="message" id="messageInput" class="form-control" placeholder="Ievadi ziņu...">
                            <button type="submit" class="btn btn-primary">Sūtīt</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <footer class="row bg-dark p-sm-5 p-1 text-white mt-5 m-0 static-bottom">
    <div class="col-md-3">
        <div class="row">
            <div class="p-0">
                <img src="LogoBetter.png" class="w-100">
            </div>
        </div>
        <div class="row g-0 mt-3">
            <label class="text-light text-opacity-25 mt-3 mb-3">AdSpot sludinājumu vietne ir labākais un efektīvākais veids, kā tev notirgot savu īpašumu vai piederīgo mantu.</label>
        </div>
    </div>
    <div class="col-md-6 d-sm-flex">
        <div class="w-50">

        </div>
        <div class="w-sm-25">
        <label classs="mb-3"><strong>Pārvietoties</strong></label>
            <li class="list-group-item"><a href="dashboard.php" class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover link-light mx-sm-1">Sākums</a></li>
            <li class="list-group-item"><a href="allads.php" class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover link-light mx-sm-1">Visi sludinājumi</a></li>
            <li class="list-group-item"><a href="create_ad.php" class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover link-light mx-sm-1">Izveidot sludinājumu</a></li>
            <li class="list-group-item"><a href="profile.php" class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover link-light mx-sm-1">Profils</a></li>
            <label class="mt-3 mb-1"><strong>Informācija</strong></label>
            <li class="list-group-item"><a href="#" class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover link-light mx-sm-1">Noteikumi</a></li>
            <li class="list-group-item"><a href="#" class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover link-light mx-sm-1">Par mums</a></li>
        </div>                              
    </div>
    <div class="col-md-3">
        <div class="">
            <div class="w-100">
                <div class="text-start">
                    <label><strong>Ar mums vari sazināties šeit!</strong></label>
                </div>
                <div class="d-flex">
                    <h3 class="m-1"><i id="externals" class="fab fa-instagram-square"></i></h3>
                    <h3 class="m-1"><i id="externals" class="fab fa-facebook-square"></i></h3>
                    <h3 class="m-1"><i id="externals" class="fab fa-youtube-square"></i></h3>
                    <h3 class="m-1"><i id="externals" class="fa-brands fa-square-x-twitter"></i></h3>
                </div>
            </div>
            <div class="mt-5">
                <div class="input-group">
                    <input type="text" class="form-control rounded-5 p-2" placeholder="Vieta ieteikumam..." aria-label="Vieta ieteikumiem" aria-describedby="button-addon2">
                    <button class="btn btn-primary rounded-5 mx-4" type="button" id="button-addon2">Iesniegt</button>
                </div>
            </div>
        </div>
    </div>
    <hr class="mt-5 mb-5">
    <p class="text-center text-light italic p-0 m-0">© 2024 AdSpot</p>
</footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js"></script>
    <script src="https://unpkg.com/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <script>
        function showMessages(conversationId) {
            $('[name="conversation_id"]').val(conversationId);
            
            $.get(`./backend/fetch_messages.php?conversationId=${conversationId}`, function(data) {
                let messageHtml = '';
                let name = '';
                
                const conversation = <?php echo json_encode($conversations); ?>.find(convo => convo.conversation_id === conversationId);
                
                if (conversation) {
                    name = `${conversation.name} ${conversation.lastname}`;
                } else {
                    name = 'Unknown User';
                }

                messageHtml += `
                    <div class="text-start mb-3 sticky-top">
                        <h5><strong>${name}</strong></h5>
                    </div>
                `;

                data.forEach(message => {
                    const messageClass = (message.sender_id === <?php echo $_SESSION['id']; ?>) ? 'sent' : 'received';
                    const messageAlignment = (message.sender_id === <?php echo $_SESSION['id']; ?>) ? 'justify-content-end' : 'justify-content-start';
                    const messageBackground = (message.sender_id === <?php echo $_SESSION['id']; ?>) ? 'bg-primary text-white' : 'bg-light border';
                    messageHtml += `
                        <div class="message ${messageClass} d-flex ${messageAlignment} mb-3 p-2">
                            <div class="p-3 rounded-3 ${messageBackground}" style="max-width: 70%;">
                                ${message.message}
                            </div>
                        </div>`;
                });
                document.getElementById('messages').innerHTML = messageHtml;
            });
        }
</script>
<script>
    $(document).ready(function() {
        $('#sendMessageForm').submit(function(event) {
            event.preventDefault();
            
            var formData = $(this).serialize();

            $.ajax({
                type: 'POST',
                url: './backend/send_message.php',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        var conversationId = $('[name="conversation_id"]').val();
                        reloadMessages(conversationId);
                    } else {
                        alert(response.error);
                    }
                },
            });
        });

        function reloadMessages(conversationId) {
            $.get(`backend/fetch_messages.php?conversationId=${conversationId}`, function(data) {
                $('#messages').html(data);
                $('#messageInput').val("");
            });
        }
    });
</script>

</body>

</html>