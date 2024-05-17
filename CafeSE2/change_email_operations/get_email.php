<?php
// Include database connection
include '../connect.php';

// Get Admin_ID
$admin_ID = 1;

// Fetch admin email from database
$sql = "SELECT admin_email FROM Admin WHERE admin_ID = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $admin_ID);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user) {
    // Output the email
    echo $user['admin_email'];
} else {
    // If admin email not found, output an empty string
    echo "";
}

// Close database connection
$stmt->close();
$con->close();
?>
