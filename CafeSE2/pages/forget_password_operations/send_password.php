<?php

require __DIR__ . "/PHPMailer/PHPMailerAutoload.php";
require __DIR__ . "/PHPMailer/PHPMailer.php";
require __DIR__ . "/PHPMailer/SMTP.php";
require __DIR__ . "/PHPMailer/Exception.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

include '../connect.php';

if (isset($_GET["email"])) {
    $email = $_GET["email"];
    $verificationCode = sprintf('%06d', mt_rand(0, 999999));
    $expiry = date("Y-m-d H:i:s", time() + 5 * 60);

    $sql = "UPDATE admin
            SET verification_code = ?,
                code_expiration = ?
            WHERE admin_email = ?";

    $stmt = $con->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("sss", $verificationCode, $expiry, $email);
        $stmt->execute();

        if ($stmt->error) {
            echo json_encode(['error' => 'SQL Error: ' . $stmt->error]);
        } elseif ($con->affected_rows) {
            // Database update was successful, proceed with sending the email

            $mail = new PHPMailer(true);

            $mail->isSMTP();
            $mail->SMTPAuth = true;
            $mail->Host = "smtp.gmail.com";
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->Username = "tstac098@gmail.com";
            $mail->Password = "kisbuznawvlgosrq";  // Replace with your generated App Password
            $mail->isHtml(true);

            $senderEmail = "cafesiena@gmail.com";
            $senderName = "Cafe Siena";

            $mail->setFrom($senderEmail, $senderName);
            $mail->addAddress($email);
            $mail->Subject = "Password Reset Verification Code";
            $mail->Body = "Your verification code is: $verificationCode";

            try {
                $mail->send();
                header("Location: ../forgot_verify.php");
                exit();
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer error: {$mail->ErrorInfo}";
            }
        } else {
            echo "<script>alert('No existing account found.'); window.location.href = '../forgot_password.php';</script>"; 
        }

        $stmt->close();
    } else {
        echo json_encode(['error' => 'Error preparing statement: ' . $con->error]);
    }

    $con->close();
} else {
    echo "<script>alert('Error: \"email\" key is not set in the GET data.');</script>";

}
?>
