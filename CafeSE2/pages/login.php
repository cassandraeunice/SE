<?php
header('Content-Type: application/json');

$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, true);


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cafe_siena";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['error' => 'Connection failed: ' . $conn->connect_error]);
    exit();
}


if ($input && isset($input['email']) && isset($input['psw'])) {
    $email = $input['email'];
    $password = $input['psw'];

    $sql = "SELECT * FROM admin WHERE admin_email = '$email'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();

        error_log("Entered Password: $password, Stored Password: {$user['admin_password']}");

        if ($password === $user['admin_password']) {
            echo json_encode(['success' => true, 'message' => 'Login successful']);
            exit();
        }
    }

    echo json_encode(['success' => false, 'message' => 'Login failed. Please check your email and password.']);
    exit();
} else {
    echo json_encode(['error' => 'Invalid data']);
    exit();
}

$conn->close();
?>
