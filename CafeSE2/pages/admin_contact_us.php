<?php
include 'connect.php';

// Number of records per page
$records_per_page = 5;

// Get the current page number
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;

// Calculate the offset for the SQL query
$offset = ($current_page - 1) * $records_per_page;

// Check if contact_id is provided in the URL and toggle status
if (isset($_GET['contact_id'])) {
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
if (isset($_GET['sort'])) {
    // Add ORDER BY clause to SQL query to order by "Not Responded" status first
    $sql = "SELECT c.*, CONCAT(c.contact_first_name, ' ', c.contact_last_name) AS customer_name,
            c.contact_email AS email, c.contact_subject AS subject, c.contact_message AS message,
            c.contact_timestamp AS timestamp, c.contact_respond_value AS responded
            FROM ContactUs c
            ORDER BY c.contact_respond_value ASC, c.contact_timestamp DESC
            LIMIT $offset, $records_per_page";
} else {
    // Default SQL query
    $sql = "SELECT c.*, CONCAT(c.contact_first_name, ' ', c.contact_last_name) AS customer_name,
            c.contact_email AS email, c.contact_subject AS subject, c.contact_message AS message,
            c.contact_timestamp AS timestamp, c.contact_respond_value AS responded
            FROM ContactUs c
            ORDER BY c.contact_timestamp DESC
            LIMIT $offset, $records_per_page";
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
                            <th scope="row">' . $contact_id . '</th>
                            <td>' . $customer_name . '</td>
                            <td>' . $email . '</td>
                            <td>' . $subject . '</td>
                            <td><a href="contact_us_operations/view_contact_record.php?contact_id=' . $contact_id . '" class="btn btn-primary">View Message</a></td>
                            <td>' . $timestamp . '</td>
                            <td>' . $status . '</td>
                            <td>
                                <a href="?contact_id=' . $contact_id . '" class="btn btn-primary">Change Status</a>
                            </td>
                        </tr>';
                    }
                }
                ?>
            </tbody>
        </table>

        <div class="pagination">
            <?php
            // Get total number of records
            $sql = "SELECT COUNT(*) AS total FROM ContactUs";
            $result = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($result);
            $total_records = $row['total'];

            // Calculate total number of pages
            $total_pages = ceil($total_records / $records_per_page);

            // Display ellipsis pagination links
            echo '<div class="pagination">';

            // Previous button
            if ($current_page > 1) {
                echo '<a href="admin_contact_us.php?page=' . ($current_page - 1);
                if (isset($_GET['sort'])) {
                    echo '&sort=1';
                }
                echo '">Previous</a>';
            }

            // Page numbers
            for ($i = max(1, $current_page - 2); $i <= min($current_page + 2, $total_pages); $i++) {
                if ($i == $current_page) {
                    echo '<span class="current">' . $i . '</span>';
                } else {
                    echo '<a href="admin_contact_us.php?page=' . $i;
                    if (isset($_GET['sort'])) {
                        echo '&sort=1';
                    }
                    echo '">' . $i . '</a>';
                }
            }

            // Ellipsis before the last page
            if ($current_page + 2 < $total_pages) {
                echo '<span class="ellipsis">...</span>';
            }

            // Last page (if not already included)
            if ($current_page + 2 < $total_pages) {
                echo '<a href="admin_contact_us.php?page=' . $total_pages;
                if (isset($_GET['sort'])) {
                    echo '&sort=1';
                }
                echo '">' . $total_pages . '</a>';
            }

            // Next button
            if ($current_page < $total_pages) {
                echo '<a href="admin_contact_us.php?page=' . ($current_page + 1);
                if (isset($_GET['sort'])) {
                    echo '&sort=1';
                }
                echo '">Next</a>';
            }

            echo '</div>';
            ?>
        </div>

    </div>
</body>

</html>