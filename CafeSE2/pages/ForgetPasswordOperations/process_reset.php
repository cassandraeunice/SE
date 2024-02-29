<?php
include '../connect.php';

$email = "tstac098@gmail.com";  // Replace with your constant email
$adminID = 1;  // Replace with your constant admin_ID

$password = $_POST["psw"];
$confirmPassword = $_POST["confirm-psw"];

if (strlen($password) < 8 || !preg_match("/[a-z]/i", $password) || !preg_match("/[0-9]/", $password) || $password !== $confirmPassword) {
    die("Invalid password");
}

$updateSql = "UPDATE admin
        SET admin_password = ?,
            verification_code = NULL,
            code_expiration = NULL
        WHERE admin_email = ? AND admin_ID = ?";

$updateStmt = $con->prepare($updateSql);

$updateStmt->bind_param("ssi", $password, $email, $adminID);

$updateStmt->execute();

if ($updateStmt->affected_rows) {
    echo "<script>alert('Password updated successfully!'); window.location.replace('../index.html');</script>";
} else {
    echo "<script>alert('Failed to update password'); window.location.replace('../forgot-verify.php');</script>";
}

// Close prepared statements
$updateStmt->close();

// Close database connection
$con->close();
?>
