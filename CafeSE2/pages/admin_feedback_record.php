<?php
include 'connect.php';

// Number of records per page
$records_per_page = 5;

// Get the current page number
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;

// Calculate the offset for the SQL query
$offset = ($current_page - 1) * $records_per_page;

// SQL query to fetch records with pagination
$sql = "SELECT f.*, CONCAT(c.customer_first_name, ' ', c.customer_last_name) AS customer_name,
        c.customer_email, f.feedback_timestamp
        FROM Feedback f 
        INNER JOIN Customer c ON f.customer_ID = c.customer_ID
        ORDER BY f.feedback_timestamp DESC
        LIMIT $offset, $records_per_page";

$result = mysqli_query($con, $sql);
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
                if ($result) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $feedback_id = $row['feedback_ID'];
                        $customer_name = $row['customer_name'];
                        $customer_email = $row['customer_email'];
                        $feedback_timestamp = $row['feedback_timestamp'];
                        echo '<tr>
                            <th scope="row">' . $feedback_id . '</th>
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

        <div class="pagination">
            <?php
            // Get total number of records
            $sql = "SELECT COUNT(*) AS total FROM Feedback";
            $result = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($result);
            $total_records = $row['total'];

            // Calculate total number of pages
            $total_pages = ceil($total_records / $records_per_page);

            // Display ellipsis pagination links
            echo '<div class="pagination">';

            // Previous button
            if ($current_page > 1) {
                echo '<a href="admin_feedback_record.php?page=' . ($current_page - 1) . '">Previous</a>';
            }

            // Page numbers
            for ($i = max(1, $current_page - 2); $i <= min($current_page + 2, $total_pages); $i++) {
                if ($i == $current_page) {
                    echo '<span class="current">' . $i . '</span>';
                } else {
                    echo '<a href="admin_feedback_record.php?page=' . $i . '">' . $i . '</a>';
                }
            }

            // Ellipsis before the last page
            if ($current_page + 2 < $total_pages) {
                echo '<span class="ellipsis">...</span>';
            }

            // Last page (if not already included and if there are more than 5 pages)
            if ($current_page + 2 < $total_pages) {
                echo '<a href="admin_feedback_record.php?page=' . $total_pages . '">' . $total_pages . '</a>';
            }

            // Next button
            if ($current_page < $total_pages) {
                echo '<a href="admin_feedback_record.php?page=' . ($current_page + 1) . '">Next</a>';
            }

            echo '</div>';
            ?>
        </div>

    </div>
</body>

</html>