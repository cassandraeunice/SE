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
$product_ID = isset($_POST['product_ID']) ? (int)$_POST['product_ID'] : 0;

// Sanitize the item number to prevent SQL injection
$product_ID = $conn->real_escape_string($product_ID);

// Perform the deletion
$sql = "DELETE FROM menu WHERE product_ID = $product_ID";

if ($conn->query($sql) === TRUE) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Error executing query']);
}

$conn->close();
?>
