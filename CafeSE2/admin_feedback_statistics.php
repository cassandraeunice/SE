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

// Calculate Average by Section within the date range
$sqlSectionAverageDateRange = "SELECT s.section_name, AVG(r.rating_number) AS weekly_average
                                    FROM Feedback f
                                    INNER JOIN Rating r ON f.feedback_ID = r.feedback_ID
                                    INNER JOIN Question q ON r.question_ID = q.question_ID
                                    INNER JOIN Section s ON q.section_ID = s.section_ID
                                    WHERE f.feedback_timestamp BETWEEN '$startDate' AND '$endDate'
                                    GROUP BY s.section_name";
$resultSectionAverageDateRange = mysqli_query($con, $sqlSectionAverageDateRange);

// Calculate Average by Question within the date range
$sqlQuestionAverageDateRange = "SELECT s.section_name, q.question_text, AVG(r.rating_number) AS weekly_average
                                      FROM Feedback f
                                      INNER JOIN Rating r ON f.feedback_ID = r.feedback_ID
                                      INNER JOIN Question q ON r.question_ID = q.question_ID
                                      INNER JOIN Section s ON q.section_ID = s.section_ID
                                      WHERE f.feedback_timestamp BETWEEN '$startDate' AND '$endDate'
                                      GROUP BY s.section_name, q.question_text
                                      ORDER BY s.section_name, q.question_text";
$resultQuestionAverageDateRange = mysqli_query($con, $sqlQuestionAverageDateRange);

if(isset($_POST['homeBtn'])){
    header("Location: home.php"); // Redirect to admin_home.php
    exit();
}

// // Calculate Weekly Average by Section
// $sqlWeeklySectionAverage = "SELECT s.section_name, AVG(r.rating_number) AS weekly_average
//                             FROM Feedback f
//                             INNER JOIN Rating r ON f.feedback_ID = r.feedback_ID
//                             INNER JOIN Question q ON r.question_ID = q.question_ID
//                             INNER JOIN Section s ON q.section_ID = s.section_ID
//                             WHERE f.feedback_timestamp >= DATE_SUB(NOW(), INTERVAL 1 WEEK)
//                             GROUP BY s.section_name";
// $resultWeeklySectionAverage = mysqli_query($con, $sqlWeeklySectionAverage);

// // Calculate Monthly Average by Section
// $sqlMonthlySectionAverage = "SELECT s.section_name, AVG(r.rating_number) AS monthly_average
//                              FROM Feedback f
//                              INNER JOIN Rating r ON f.feedback_ID = r.feedback_ID
//                              INNER JOIN Question q ON r.question_ID = q.question_ID
//                              INNER JOIN Section s ON q.section_ID = s.section_ID
//                              WHERE f.feedback_timestamp >= DATE_SUB(NOW(), INTERVAL 1 MONTH)
//                              GROUP BY s.section_name";
// $resultMonthlySectionAverage = mysqli_query($con, $sqlMonthlySectionAverage);

// // Calculate Weekly Average by Question
// $sqlWeeklyQuestionAverage = "SELECT s.section_name, q.question_text, AVG(r.rating_number) AS weekly_average
//                              FROM Feedback f
//                              INNER JOIN Rating r ON f.feedback_ID = r.feedback_ID
//                              INNER JOIN Question q ON r.question_ID = q.question_ID
//                              INNER JOIN Section s ON q.section_ID = s.section_ID
//                              WHERE f.feedback_timestamp >= DATE_SUB(NOW(), INTERVAL 1 WEEK)
//                              GROUP BY s.section_name, q.question_text
//                              ORDER BY s.section_name, q.question_text";
// $resultWeeklyQuestionAverage = mysqli_query($con, $sqlWeeklyQuestionAverage);

// // Calculate Monthly Average by Question
// $sqlMonthlyQuestionAverage = "SELECT s.section_name, q.question_text, AVG(r.rating_number) AS monthly_average
//                               FROM Feedback f
//                               INNER JOIN Rating r ON f.feedback_ID = r.feedback_ID
//                               INNER JOIN Question q ON r.question_ID = q.question_ID
//                               INNER JOIN Section s ON q.section_ID = s.section_ID
//                               WHERE f.feedback_timestamp >= DATE_SUB(NOW(), INTERVAL 1 MONTH)
//                               GROUP BY s.section_name, q.question_text
//                               ORDER BY s.section_name, q.question_text";
// $resultMonthlyQuestionAverage = mysqli_query($con, $sqlMonthlyQuestionAverage);



// Calculate Average by Section within the date range
$sqlSectionAverageDateRange1 = "SELECT DATE_FORMAT(f.feedback_timestamp, '%b %Y') AS month_year, s.section_name, AVG(r.rating_number) AS monthly_average
                                    FROM Feedback f
                                    INNER JOIN Rating r ON f.feedback_ID = r.feedback_ID
                                    INNER JOIN Question q ON r.question_ID = q.question_ID
                                    INNER JOIN Section s ON q.section_ID = s.section_ID
                                    WHERE f.feedback_timestamp BETWEEN '$startDate' AND '$endDate'
                                    GROUP BY month_year, s.section_name
                                    ORDER BY month_year, s.section_name";
$resultSectionAverageDateRange1 = mysqli_query($con, $sqlSectionAverageDateRange1);

// Store data for the chart
$labels = []; // Array to store labels (month_year)
$foodData = []; // Array to store average ratings for Food
$serviceData = []; // Array to store average ratings for Service

while ($row = mysqli_fetch_assoc($resultSectionAverageDateRange1)) {
    // Add month_year to labels array if not already present
    $monthYear = $row['month_year'];
    if (!in_array($monthYear, $labels)) {
        $labels[] = $monthYear;
    }

    // Store average rating for Food and Service
    if ($row['section_name'] === 'Food') {
        $foodData[$monthYear] = round($row['monthly_average'], 2);
    } elseif ($row['section_name'] === 'Service') {
        $serviceData[$monthYear] = round($row['monthly_average'], 2);
    }
}

// Sort the labels array based on month and year
usort($labels, function($a, $b) {
    $aDate = strtotime($a);
    $bDate = strtotime($b);
    return $aDate - $bDate;
});

// Prepare datasets for Chart.js
$datasets = [
    [
        'label' => 'Food',
        'data' => array_values($foodData), // Extract the values (average ratings)
        'borderColor' => 'red', // Set color for Food data
        'fill' => false, // Do not fill the area under the line
    ],
    [
        'label' => 'Service',
        'data' => array_values($serviceData), // Extract the values (average ratings)
        'borderColor' => 'blue', // Set color for Service data
        'fill' => false, // Do not fill the area under the line
    ],
];

// Convert labels and datasets to JSON format for JavaScript
$labels_json = json_encode($labels);
$datasets_json = json_encode($datasets);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/dashboard-feedback-statistics.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
        <h2>Feedback Statistics</h2>
        <h3>Filter by Date</h3>
        <!-- Date Range Filter Form -->
        <form id="date_range_form" method="post" action="">
            <label for="start_date">Start Date:</label>
            <input type="date" id="start_date" name="start_date" value="<?php echo $startDate; ?>" max="<?php echo $endDate; ?>">
            <label for="end_date">End Date:</label>
            <input type="date" id="end_date" name="end_date" value="<?php echo date('Y-m-d', strtotime($endDate)); ?>" min="<?php echo $startDate; ?>" max="<?php echo date('Y-m-d'); ?>">
            <input type="submit" name="submit" value="Apply">
        </form>

        <h3>Chart: Average Ratings Over Time</h3>
    <div class="chart-container">
        <canvas id="myLineChart"></canvas>
    </div>
        <!-- Average Ratings by Section within Date Range -->
        <h3>Average Ratings by Section (Date Range: <?php echo $startDate; ?> to <?php echo $endDate; ?>)</h3>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Section</th>
                    <th scope="col">Average Rating</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($resultSectionAverageDateRange)) {
                    echo '<tr>
                            <th scope="row">' . $row['section_name'] . '</th>
                            <td>' . round($row['weekly_average'], 2) . '</td>
                          </tr>';
                }
                ?>
            </tbody>
        </table>

        <!-- Average Ratings by Question within Date Range -->
        <h3>Average Ratings by Question (Date Range: <?php echo $startDate; ?> to <?php echo $endDate; ?>)</h3>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Section</th>
                    <th scope="col">Question</th>
                    <th scope="col">Average Rating</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($resultQuestionAverageDateRange)) {
                    echo '<tr>
                            <th scope="row">' . $row['section_name'] . '</th>
                            <td>' . $row['question_text'] . '</td>
                            <td>' . round($row['weekly_average'], 2) . '</td>
                          </tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
        const ctxLine = document.getElementById('myLineChart');

// Parse the PHP-generated JSON data into JavaScript arrays
const labels = <?php echo $labels_json; ?>;
const datasets = <?php echo $datasets_json; ?>;

new Chart(ctxLine, {
    type: 'line',
    data: {
        labels: labels,
        datasets: datasets
    },
    options: {
        scales: {
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Average Rating'
                }
            },
            x: {
                title: {
                    display: true,
                    text: 'Months'
                }
            }
        }
    }
});

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
    

</html>

<!-- <h3>Weekly Average Ratings by Section</h3>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Section</th>
                    <th scope="col">Average Rating</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($resultWeeklySectionAverage)) {
                    echo '<tr>
                            <th scope="row">' . $row['section_name'] . '</th>
                            <td>' . round($row['weekly_average'], 2) . '</td>
                          </tr>';
                }
                ?>
            </tbody>
        </table>

        <h3>Monthly Average Ratings by Section</h3>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Section</th>
                    <th scope="col">Average Rating</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($resultMonthlySectionAverage)) {
                    echo '<tr>
                            <th scope="row">' . $row['section_name'] . '</th>
                            <td>' . round($row['monthly_average'], 2) . '</td>
                          </tr>';
                }
                ?>
            </tbody>
        </table>

        <h3>Weekly Average Ratings by Question</h3>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Section</th>
                    <th scope="col">Question</th>
                    <th scope="col">Average Rating</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($resultWeeklyQuestionAverage)) {
                    echo '<tr>
                            <th scope="row">' . $row['section_name'] . '</th>
                            <td>' . $row['question_text'] . '</td>
                            <td>' . round($row['weekly_average'], 2) . '</td>
                          </tr>';
                }
                ?>
            </tbody>
        </table>

        <h3>Monthly Average Ratings by Question</h3>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Section</th>
                    <th scope="col">Question</th>
                    <th scope="col">Average Rating</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($resultMonthlyQuestionAverage)) {
                    echo '<tr>
                            <th scope="row">' . $row['section_name'] . '</th>
                            <td>' . $row['question_text'] . '</td>
                            <td>' . round($row['monthly_average'], 2) . '</td>
                          </tr>';
                }
                ?>
            </tbody>
        </table> -->
<!-- </div>
</body>

</html> -->