<?php

require __DIR__ . "/PHPMailer/PHPMailerAutoload.php";
require __DIR__ . "/PHPMailer/PHPMailer.php";
require __DIR__ . "/PHPMailer/SMTP.php";
require __DIR__ . "/PHPMailer/Exception.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

include '../connect.php';
date_default_timezone_set('Asia/Manila');


if (isset($_GET["email"])) {
    $email = $_GET["email"];
    $verificationCode = sprintf('%06d', mt_rand(0, 999999));
    $expiry = date("Y-m-d H:i:s", strtotime("+3 minutes"));

    $sql = "UPDATE admin
            SET verification_code = ?,
                code_expiration = ?
            WHERE admin_email = ?";

    $stmt = $con->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ssi", $verificationCode, $expiry, $email);
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
            $mail->Password = "ojevzblvurthqkdo";  // Replace with your generated App Password
            $mail->isHtml(true);

            $senderEmail = "cafesiena@gmail.com";
            $senderName = "Cafe Siena";

            $mail->setFrom($senderEmail, $senderName);
            $mail->addAddress($email);
            $mail->Subject = "Change Email Verification Code";
            $mail->Body = "
                    <html>
                    <head>
                        <style>
                            *{
                                font-family: 'Montserrat', sans-serif;
                            }
                            body {
                                font-family: Montserrat, sans-serif;
                                background-color: #f4f4f4;
                                padding: 20px;
                            }
                            .container {
                                max-width: 600px;
                                margin: 0 auto;
                                background-color: #fff;
                                padding: 20px;
                                border-radius: 5px;
                                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                                background-color: white;
                                color: #8F5E36;
                            }
                            h2 {
                                color: white;
                                font-size: 20pt;
                            }
                            p {
                                color: #666;
                            }
                            h3{
                                font-size: 15pt;
                            }
                            .headerChange{
                                color:white;
                                border-radius: 5px;
                                background-color: #271300;
                                padding: 15px;
                            }
                        </style>
                    </head>
                    <body>
                        <div class='container'>
                        <div class='headerChange'>
                            <h2>Change Email Verification</h2>
                        </div>    
                            <p>Greetings, Admin!</p>
                            <p>Your verification code is:</p>
                            <h3>$verificationCode</h3>
                            <hr></hr>
                            <p>Please use this code to verify your email address.</p>
                            <p>If you didn't request this change, you can ignore this email.</p>
                            <p>Regards,<br/><strong>Cafe Siena Team</strong></p>
                        </div>
                    </body>
                    </html>
                ";

            try {
                $mail->send();
                header("Location: ./change_verify.php");
                exit();
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer error: {$mail->ErrorInfo}";
            }
        } else {
            echo "<script>alert('No existing account found.'); window.location.href = './change_email.php';</script>"; 
        }

        $stmt->close();
    } else {
        echo json_encode(['error' => 'Error preparing statement: ' . $con->error]);
    }

    $con->close();
} else {
    echo "<script>alert('Error: \"email\" or \"oldPass\" key is not set in the GET data.');</script>";
}
?>
