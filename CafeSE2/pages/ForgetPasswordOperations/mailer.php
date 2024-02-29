<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . "/PHPMailer/PHPMailerAutoload.php";

$mail = new PHPMailer(true);

// $mail->SMTPDebug = SMTP::DEBUG_SERVER;

$mail->isSMTP();
$mail->SMTPAuth = true;

$mail->Host = "smtp.gmail.com";
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
$mail->Port = 587; 
$mail->Username = "tstac098@gmail.com";
$mail->Password = "QWErtyuiop!@#45";

$mail->isHtml(true);

// Sender information
$mail->setFrom("tstac098@gmail.com", "Cafe Siena-Admin");

// Recipient information - dynamically set based on user input
if (isset($_POST["email"])) {
    $recipientEmail = $_POST["email"];
    $mail->addAddress($recipientEmail);

    // Send the email
    $mail->send();

    // Redirect the user to forgot-verify.php

    exit(); // Ensure no further code is executed after the redirect
} else {
    // Handle the case when there's no email provided
    // You might want to redirect or display an error message
}

?>
