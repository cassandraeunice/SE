<?php
include 'connect.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/dashboard-feedback-record.css">
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
        <h2>Feedback and Ratings</h2>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Feedback ID</th>
                    <th scope="col">Customer Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Timestamp</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT f.*, CONCAT(c.customer_first_name, ' ', c.customer_last_name) AS customer_name,
                        c.customer_email, f.feedback_timestamp
                        FROM Feedback f 
                        INNER JOIN Customer c ON f.customer_ID = c.customer_ID";
                $result = mysqli_query($con, $sql);
                if ($result) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $feedback_id = $row['feedback_ID'];
                        $customer_name = $row['customer_name'];
                        $customer_email = $row['customer_email'];
                        $feedback_timestamp = $row['feedback_timestamp'];
                        echo '<tr>
                            <td>' . $feedback_id . '</td>
                            <td>' . $customer_name . '</td>
                            <td>' . $customer_email . '</td>

                            <td>' . $feedback_timestamp . '</td>
                            <td><a href="feedback_operations/view_feedback.php?feedback_id=' . $feedback_id . '" class="btn btn-primary">View Feedback</a></td>
                        </tr>';
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>
