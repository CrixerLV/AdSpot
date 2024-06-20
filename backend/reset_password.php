<?php
require "db_con.php";
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['emailreset'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => 'Nederīgs e-pasta formāts']);
        exit;
    }

    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("SELECT * FROM password_resets WHERE user_id IN (SELECT user_id FROM users WHERE email = :email) AND expires_at > NOW()");
        $stmt->execute(['email' => $email]);
        $existingRequest = $stmt->fetch();

        if ($existingRequest) {
            echo json_encode(['status' => 'error', 'message' => 'Tev jau ir aktīvs paroles atjaunošanas pieprasījums. Lūdzu, pārbaudiet savu e-pastu.']);
            exit;
        }

        $stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if ($user) {
            $token = bin2hex(random_bytes(50));
            $userId = $user['user_id'];

            $stmt = $pdo->prepare("INSERT INTO password_resets (user_id, token, created_at, expires_at) VALUES (:user_id, :token, NOW(), DATE_ADD(NOW(), INTERVAL 10 MINUTE))");
            $stmt->execute(['user_id' => $userId, 'token' => $token]);

            $resetLink = "http://adspot.website/reset_password_form.php?token=$token";
            $subject = "Paroles atjaunošana";

            $message = "
                <html>
                <body>
                    <p>Sveicināti,</p>
                    <p>Lai atjaunotu paroli, spiediet uz saites zemāk!</p>
                    <p><a href='$resetLink'>Spied šo</a></p>
                    <p style='color:red;'><strong>Ja šo pieprasījumu neveicāt Jūs, tad ignorējiet šo ziņojumu!</strong></p>
                    <hr>
                </body>
                </html>
            ";

            $mail = new PHPMailer(true);

            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'adspotlv.website@gmail.com';
            $mail->Password = 'cwxb gahw fglo kjzh';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->isHTML(true);

            $mail->setFrom('no-reply@adspot.website', 'No-reply-AdSpot');
            $mail->addAddress($email);

            $mail->Subject = $subject;
            $mail->Body = $message;

            $mail->SMTPDebug = 0;

            if ($mail->send()) {
                $pdo->commit();
                echo json_encode(['status' => 'success', 'message' => 'Paroles atjaunošanas saite tika veiksmīgi nosūtīta uz norādīto e-pastu!']);
            } else {
                throw new Exception('Email could not be sent.');
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Šāds e-pasts netika atrasts']);
        }
    } catch (Exception $e) {
        $pdo->rollBack();
        error_log('Mailer Error: ' . $mail->ErrorInfo);
        echo json_encode(['status' => 'error', 'message' => 'Notika kļūda, mēģiniet vēlreiz. Mailer Error: ' . $mail->ErrorInfo]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Nederīga pieprasījuma metode.']);
}
?>
