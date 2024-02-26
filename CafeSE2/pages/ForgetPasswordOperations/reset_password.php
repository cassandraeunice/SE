<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cafe_siena";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['error' => 'Connection failed: ' . $conn->connect_error]);
    exit();
}

$verificationCode = $_POST["code"];

$sql = "SELECT * FROM admin
        WHERE verification_code = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("s", $verificationCode);

$stmt->execute();

$result = $stmt->get_result();

$user = $result->fetch_assoc();

if ($user === null) {
    // Verification code not found
    echo "<script>alert('Verification not found');";
    echo "window.location.href='../forgot-verify.php';</script>";
    exit();
} else {
    // Check if the code has expired
    $codeExpiration = strtotime($user["code_expiration"]);

    if ($codeExpiration <= time()) {
        // Code has expired
        echo "<script>alert('Code has expired');</script>";
        echo "<script>window.location.href='../forgot-verify.php';</script>";
        exit();
    } else {
        // Verification code is correct, redirect to password-change.html
        echo "<script>window.location.href='../password-change.html';</script>";
        exit();
    }
}

// Close database connection
$stmt->close();
$conn->close();
?>
