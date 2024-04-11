<?php

session_start(); // Start session to access session variables

// Check if the admin is logged in
if (!isset($_SESSION['admin_ID']) || !isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Check if the admin's login status is remembered via cookie
    if (!isset($_COOKIE['admin_logged_in']) || $_COOKIE['admin_logged_in'] !== 'true') {
        // If not logged in and no remembered login status, redirect to login page
        header("Location: login.php");
        exit();
    }
}

include 'connect.php';

if(isset($_POST['homeBtn'])){
    header("Location: home.php"); // Redirect to admin_home.php
    exit();
}

$query = "SELECT admin_email, admin_password, code_expiration FROM cafe_siena3.admin";
$result = mysqli_query($con, $query);

if(mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $admin_email = $row['admin_email'];
    $last_updated_timestamp = $row['code_expiration'];
    $parts = explode('@', $admin_email);
    $masked_email = substr($parts[0], 0, 3) . str_repeat('*', strlen($parts[0]) - 3) . '@' . $parts[1];
} else {
    // Handle the case where no admin record is found
    $admin_email = "N/A";
    $last_updated_timestamp = "N/A";
    $masked_email = "N/A";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/dashboard-menu.css">
    <link rel="stylesheet" href="../css/admin-tab.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <div class="sidebar">
        <h2>CAFÉ SIENA</h2>
        <form method="post"> 
            <ul>
                <li><a href="admin_home.php">Home Content</a></li>
                <li><a href="admin_menu.php">Menu Content</a></li>
                <li><a href="admin_contact_us.php">Contact Us Record</a></li>
                <li><a href="admin_feedback_content.php">Feedback Content</a></li>
                <li><a href="admin_feedback_record.php">Feedback Record</a></li>
                <li><a href="admin_feedback_statistics.php">Feedback Statistics</a></li>
                <li><a href="admin_about_us.php">About Us Content</a></li>
                <li><a href="admin_account.php">Account</a></li>
            </ul>
            <div class="homeBtn">
                <button type="submit" name="homeBtn"> Customer Dashboard</button>
            </div>
        </form>
    </div>

    <div class="container">
        <div id="admin_account" class="admin_account_content">
            <h2>Account Settings</h2>
            <div class="account-details">   
                <p><strong>Email:</strong> <?php echo $masked_email; ?></p>
            </div>
            <button class="btn btn-primary m-5"><a href="./change_email_operations/change_email.php" class="text-light">Change Email</a></button>
            
            <div>
            <button class="btn btn-primary m-5"><a href="./change_password_operations/change_password.php" class="text-l ight">Change Password</a></button>
            </div>
            
            <p><strong>Last Code Requested:</strong> <?php echo $last_updated_timestamp; ?></p>

        </div>
        <form method="post" action="logout.php">
            <button type="submit" class="btn btn-danger m-5" style="background-color: red;">Logout</button>
        </form>
    </div>
</body>

</html>
