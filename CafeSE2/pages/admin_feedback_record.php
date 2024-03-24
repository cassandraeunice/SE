<?php
session_start();
include 'connect.php';

// Number of records per page
$records_per_page = 20;

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
$countSql = "SELECT COUNT(*) AS total FROM Feedback
             WHERE feedback_timestamp BETWEEN '$startDate' AND '$endDate'";
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

// SQL query to fetch records with pagination and date range filtering
$sql = "SELECT f.*, CONCAT(c.customer_first_name, ' ', c.customer_last_name) AS customer_name,
        c.customer_email, f.feedback_timestamp
        FROM Feedback f 
        INNER JOIN Customer c ON f.customer_ID = c.customer_ID
        WHERE f.feedback_timestamp BETWEEN '$startDate' AND '$endDate'
        ORDER BY f.feedback_timestamp DESC
        LIMIT $offset, $records_per_page";

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
    <link rel="stylesheet" href="../css/dashboard-feedback-record.css">
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
        <h2>Admin Dashboard</h2>
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
                <button type="submit" name="homeBtn"><i class="fa fa-home"></i> Home</button>
            </div>
        </form>
    </div>

    <div class="container">
        <h2>Feedback and Ratings</h2>
        <form id="date_range_form" method="post" action="">
            <h3>Sort by Date</h3>
            <label for="start_date">Start Date:</label>
            <input type="date" id="start_date" name="start_date" value="<?php echo $startDate; ?>" max="<?php echo $endDate; ?>">
            <label for="end_date">End Date:</label>
            <input type="date" id="end_date" name="end_date" value="<?php echo date('Y-m-d', strtotime($endDate)); ?>" min="<?php echo $startDate; ?>" max="<?php echo date('Y-m-d'); ?>">
            <input type="submit" name="submit" value="Apply">
        </form><br>
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
            // Count total number of records with date range filtering
            $countSql = "SELECT COUNT(*) AS total FROM Feedback
                         WHERE feedback_timestamp BETWEEN '$startDate' AND '$endDate'";
            $countResult = mysqli_query($con, $countSql);
            $row = mysqli_fetch_assoc($countResult);
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