<?php
header('Content-Type: application/json');

$inputJSON = file_get_contents('php://input');
$input = json_decode($inputJSON, true);

include 'connect.php';

if ($input && isset($input['email']) && isset($input['psw'])) {
    $email = $input['email'];
    $password = $input['psw'];

    $sql = "SELECT * FROM admin WHERE admin_email = '$email'";
    $result = $con->query($sql);

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

$con->close(); // Move this line to the end of the script
?>
