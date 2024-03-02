<?php
include 'connect.php';

// Check if contact_id is provided in the URL and toggle status
if(isset($_GET['contact_id'])) {
    $contact_id = $_GET['contact_id'];
    
    // Fetch current responded status
    $status_query = "SELECT contact_respond_value FROM ContactUs WHERE contact_id = $contact_id";
    $status_result = mysqli_query($con, $status_query);
    $status_row = mysqli_fetch_assoc($status_result);
    $current_status = $status_row['contact_respond_value'];

    // Toggle responded status
    $new_status = $current_status ? 0 : 1;

    // Update responded status
    $update_query = "UPDATE ContactUs SET contact_respond_value = $new_status WHERE contact_id = $contact_id";
    $update_result = mysqli_query($con, $update_query);

    // Redirect back to admin_contact_us.php
    header("Location: admin_contact_us.php");
    exit();
}

// Check if the button to arrange by "Not Responded" is clicked
if(isset($_GET['sort'])) {
    // Add ORDER BY clause to SQL query to order by "Not Responded" status first
    $sql = "SELECT c.*, CONCAT(c.contact_first_name, ' ', c.contact_last_name) AS customer_name,
            c.contact_email AS email, c.contact_subject AS subject, c.contact_message AS message,
            c.contact_timestamp AS timestamp, c.contact_respond_value AS responded
            FROM ContactUs c
            ORDER BY c.contact_respond_value ASC";
} else {
    // Default SQL query
    $sql = "SELECT c.*, CONCAT(c.contact_first_name, ' ', c.contact_last_name) AS customer_name,
            c.contact_email AS email, c.contact_subject AS subject, c.contact_message AS message,
            c.contact_timestamp AS timestamp, c.contact_respond_value AS responded
            FROM ContactUs c";
}

$result = mysqli_query($con, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/dashboard-contact-us.css">
</head>

<body>
<div class="sidebar">
        <h2>Admin Dashboard</h2>
        <ul>
            <li><a href="admin_menu.php">Menu Content</a></li>
            <li><a href="#">Home Content</a></li>
            <li><a href="admin_contact_us.php">Contact Us Record</a></li>
            <li><a href="admin_feedback_content.php">Feedback Content</a></li>
            <li><a href="admin_feedback_record.php">Feedback Record</a></li>
            <li><a href="admin_feedback_statistics.php">Feedback Statistics</a></li>
            <li><a href="#">About Content</a></li>
        </ul>
    </div>
    
    <div class="container">
        <h2>Contact Us Record</h2>
        <div>
            <a href="?sort=1" class="btn btn-primary" id="sort">Sort by Not Responded</a>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Contact ID</th>
                    <th scope="col">Customer Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Subject</th>
                    <th scope="col">Message</th>
                    <th scope="col">Timestamp</th>
                    <th scope="col">Status</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $contact_id = $row['contact_id'];
                        $customer_name = $row['customer_name'];
                        $email = $row['email'];
                        $subject = $row['subject'];
                        $timestamp = $row['timestamp'];
                        $status = $row['responded'] ? "Responded" : "Not Responded";
                        echo '<tr>
                            <td>' . $contact_id . '</td>
                            <td>' . $customer_name . '</td>
                            <td>' . $email . '</td>
                            <td>' . $subject . '</td>
                            <td><a href="contact_us_operations/view_contact_record.php?contact_id=' . $contact_id . '" class="btn btn-primary">View Message</a></td>
                            <td>' . $timestamp . '</td>
                            <td>' . $status . '</td>
                            <td>
                                <a href="?contact_id=' . $contact_id . '" class="btn btn-primary">Toggle Status</a>
                            </td>
                        </tr>';
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>
