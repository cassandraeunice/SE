<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cafesienadb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the item number from the POST request
$itemNo = isset($_POST['itemNo']) ? (int)$_POST['itemNo'] : 0;

// Sanitize the item number to prevent SQL injection
$itemNo = $conn->real_escape_string($itemNo);

// Perform the deletion
$sql = "DELETE FROM menu WHERE ItemNo = $itemNo";

if ($conn->query($sql) === TRUE) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Error executing query']);
}

$conn->close();
?>
