<?php
include 'connect.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/dashboard-menu.css">
    <link rel="stylesheet" href="../css/admin-tab.css">
</head>

<body>
    <div class="sidebar">
        <h2>Admin Dashboard</h2>
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
    </div>

    <div class="container">
        <div id="admin_account" class="admin_account_content">
            <h2>Account Settings</h2>
            <div class="account-details">   
                <p><strong>Email:</strong> admin@example.com</p>
            </div>
            <button class="btn btn-primary m-5"><a href="./change_email_operations/change_email.php" class="text-light">Change Email</a></button>
            <p><strong>Last Updated Email:</strong> [Last Updated Timestamp]</p>
            <button class="btn btn-primary m-5"><a href="./change_password_operations/change_password.php" class="text-l ight">Change
                    Password</a></button>
            <p><strong>Last Updated Password:</strong> [Last Updated Timestamp]</p>

        </div>
        <button class="btn btn-danger m-5" style="background-color: red;"><a href="login.php" class="text-light">Logout</a></button>
    </div>
</body>

</html>