<?php
session_start();
include 'connect.php';

// Number of records per page
$records_per_page = 10;

// Get the current page number
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;

// Default date range to last week
$startDate = date('Y-m-d', strtotime('-1 week'));
$endDate = date('Y-m-d');

// Check if the date range form is submitted
if (isset($_POST['submit'])) {
    // Retrieve start and end dates from form
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];
    // Append time to end date to include records up to 11:59 PM
    $endDate .= ' 23:59:59';
    // Store the dates in the session
    $_SESSION['startDate'] = $startDate;
    $_SESSION['endDate'] = $endDate;
} elseif (isset($_SESSION['startDate']) && isset($_SESSION['endDate'])) {
    // Retrieve start and end dates from the session
    $startDate = $_SESSION['startDate'];
    $endDate = $_SESSION['endDate'];
}

// Calculate the offset for the SQL query
$offset = ($current_page - 1) * $records_per_page;

// Count total number of records with date range filtering
$countSql = "SELECT COUNT(*) AS total FROM ContactUs
             WHERE contact_timestamp BETWEEN '$startDate' AND '$endDate'";
$countResult = mysqli_query($con, $countSql);
$row = mysqli_fetch_assoc($countResult);
$total_records = $row['total'];

// Calculate total number of pages
$total_pages = ceil($total_records / $records_per_page);

// Check if the total number of records is less than the records per page
if ($total_records < $records_per_page) {
    // Reset the current page to 1
    $current_page = 1;
    // Recalculate the offset for the SQL query
    $offset = ($current_page - 1) * $records_per_page;
}

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
    // Toggle sorting between responded and not responded
    if ($_GET['sort'] == 'responded') {
        $sort_condition = "c.contact_respond_value DESC,";
        $button_text = "Sort by Responded";
        $next_sort_option = "not_responded";
    } else {
        $sort_condition = "c.contact_respond_value ASC,";
        $button_text = "Sort by Not Responded";
        $next_sort_option = "responded";
    }

    // Add ORDER BY clause to SQL query to order by status and timestamp
    $sql = "SELECT c.*, CONCAT(c.contact_first_name, ' ', c.contact_last_name) AS customer_name,
            c.contact_email AS email, c.contact_subject AS subject, c.contact_message AS message,
            c.contact_timestamp AS timestamp, c.contact_respond_value AS responded
            FROM ContactUs c
            WHERE c.contact_timestamp BETWEEN '$startDate' AND '$endDate'
            ORDER BY $sort_condition c.contact_timestamp DESC
            LIMIT $offset, $records_per_page";
} else {
    // Default SQL query
    $sql = "SELECT c.*, CONCAT(c.contact_first_name, ' ', c.contact_last_name) AS customer_name,
            c.contact_email AS email, c.contact_subject AS subject, c.contact_message AS message,
            c.contact_timestamp AS timestamp, c.contact_respond_value AS responded
            FROM ContactUs c
            WHERE c.contact_timestamp BETWEEN '$startDate' AND '$endDate'
            ORDER BY c.contact_timestamp DESC
            LIMIT $offset, $records_per_page";
    $button_text = "Sort by Not Responded";
    $next_sort_option = "not_responded";
}

$result = mysqli_query($con, $sql);

if(isset($_POST['homeBtn'])){
    header("Location: home.php"); // Redirect to admin_home.php
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/dashboard-contact-us.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script>
        function setupDateRange() {
            var startDateInput = document.getElementById('start_date');
            var endDateInput = document.getElementById('end_date');

            function updateEndDateMin() {
                endDateInput.min = startDateInput.value;
            }

            function updateStartDateMax() {
                startDateInput.max = endDateInput.value;
            }

            startDateInput.addEventListener('change', function() {
                updateEndDateMin();
            });

            endDateInput.addEventListener('change', function() {
                updateStartDateMax();
                if (endDateInput.value < startDateInput.value) {
                    startDateInput.value = endDateInput.value;
                }
            });

            updateEndDateMin();
            updateStartDateMax();
        }
    </script>
</head>

<body onload="setupDateRange()">
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
        <h2>Contact Us Record</h2>
        <form id="date_range_form" method="post" action="">
            <h3>Sort by Date</h3>
            <label for="start_date">Start Date:</label>
            <input type="date" id="start_date" name="start_date" value="<?php echo $startDate; ?>" max="<?php echo $endDate; ?>">
            <label for="end_date">End Date:</label>
            <input type="date" id="end_date" name="end_date" value="<?php echo date('Y-m-d', strtotime($endDate)); ?>" min="<?php echo $startDate; ?>" max="<?php echo date('Y-m-d'); ?>">
            <input type="submit" name="submit" value="Apply">
        </form></br>
        <div>
            <a href="?sort=<?php echo $next_sort_option; ?>" class="btn btn-primary" id="sort"><?php echo $button_text; ?></a>
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
            // Count total number of records with date range filtering
            $countSql = "SELECT COUNT(*) AS total FROM ContactUs
                         WHERE contact_timestamp BETWEEN '$startDate' AND '$endDate'";
            $countResult = mysqli_query($con, $countSql);
            $row = mysqli_fetch_assoc($countResult);
            $total_records = $row['total'];

            // Calculate total number of pages
            $total_pages = ceil($total_records / $records_per_page);

            // Display ellipsis pagination links
            echo '<div class="pagination">';

            // Previous button
            if ($current_page > 1) {
                echo '<a href="admin_contact_us.php?page=' . ($current_page - 1);
                if (isset($_GET['sort'])) {
                    echo '&sort=' . $_GET['sort'];
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
                        echo '&sort=' . $_GET['sort'];
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
                    echo '&sort=' . $_GET['sort'];
                }
                echo '">' . $total_pages . '</a>';
            }

            // Next button
            if ($current_page < $total_pages) {
                echo '<a href="admin_contact_us.php?page=' . ($current_page + 1);
                if (isset($_GET['sort'])) {
                    echo '&sort=' . $_GET['sort'];
                }
                echo '">Next</a>';
            }

            echo '</div>';
            ?>
        </div>


    </div>
</body>

</html>