<?php
include '../connect.php';

$verificationCode = $_POST["code"];

$sql = "SELECT * FROM admin
        WHERE verification_code = ?";

$stmt = $con->prepare($sql);

$stmt->bind_param("s", $verificationCode);

$stmt->execute();

$result = $stmt->get_result();

$user = $result->fetch_assoc();

if ($user === null) {
    // Verification code not found
    echo "<script>alert('Verification code is incorrect');";
    echo "window.location.href='../forgot_verify.php';</script>";
    exit();
} else {
    // Check if the code has expired
    $codeExpiration = strtotime($user["code_expiration"]);

    if ($codeExpiration <= time()) {
        // Code has expired
        echo "<script>alert('Code has expired');</script>";
        echo "<script>window.location.href='../forgot_verify.php';</script>";
        exit();
    } else {
        // Verification code is correct, redirect to password-change.html
        echo "<script>window.location.href='../password_change.html';</script>";
        exit();
    }
}

// Close database connection
$stmt->close();
$con->close();
?>
