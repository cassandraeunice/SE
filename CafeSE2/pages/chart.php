<?php
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
$sqlSectionAverageDateRange = "SELECT DATE_FORMAT(f.feedback_timestamp, '%b %Y') AS month_year, s.section_name, AVG(r.rating_number) AS monthly_average
                                    FROM Feedback f
                                    INNER JOIN Rating r ON f.feedback_ID = r.feedback_ID
                                    INNER JOIN Question q ON r.question_ID = q.question_ID
                                    INNER JOIN Section s ON q.section_ID = s.section_ID
                                    WHERE f.feedback_timestamp BETWEEN '$startDate' AND '$endDate'
                                    GROUP BY month_year, s.section_name
                                    ORDER BY month_year, s.section_name";
$resultSectionAverageDateRange = mysqli_query($con, $sqlSectionAverageDateRange);

// Store data for the chart
$labels = []; // Array to store labels (month_year)
$foodData = []; // Array to store average ratings for Food
$serviceData = []; // Array to store average ratings for Service

while ($row = mysqli_fetch_assoc($resultSectionAverageDateRange)) {
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
    <title>Chart</title>
    <link rel="stylesheet" href="../css/chart.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body onload="setupDateRange()">
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

    <div class="chart-container">
        <canvas id="myLineChart"></canvas>
    </div>

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

</body>

</html>
