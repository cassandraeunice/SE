<?php
include 'connect.php';

// Calculate Weekly Average by Section
$sqlWeeklySectionAverage = "SELECT s.section_name, AVG(r.rating_number) AS weekly_average
                            FROM Feedback f
                            INNER JOIN Rating r ON f.feedback_ID = r.feedback_ID
                            INNER JOIN Question q ON r.question_ID = q.question_ID
                            INNER JOIN Section s ON q.section_ID = s.section_ID
                            WHERE f.feedback_timestamp >= DATE_SUB(NOW(), INTERVAL 1 WEEK)
                            GROUP BY s.section_name";
$resultWeeklySectionAverage = mysqli_query($con, $sqlWeeklySectionAverage);

// Calculate Monthly Average by Section
$sqlMonthlySectionAverage = "SELECT s.section_name, AVG(r.rating_number) AS monthly_average
                             FROM Feedback f
                             INNER JOIN Rating r ON f.feedback_ID = r.feedback_ID
                             INNER JOIN Question q ON r.question_ID = q.question_ID
                             INNER JOIN Section s ON q.section_ID = s.section_ID
                             WHERE f.feedback_timestamp >= DATE_SUB(NOW(), INTERVAL 1 MONTH)
                             GROUP BY s.section_name";
$resultMonthlySectionAverage = mysqli_query($con, $sqlMonthlySectionAverage);

// Calculate Weekly Average by Question
$sqlWeeklyQuestionAverage = "SELECT s.section_name, q.question_text, AVG(r.rating_number) AS weekly_average
                             FROM Feedback f
                             INNER JOIN Rating r ON f.feedback_ID = r.feedback_ID
                             INNER JOIN Question q ON r.question_ID = q.question_ID
                             INNER JOIN Section s ON q.section_ID = s.section_ID
                             WHERE f.feedback_timestamp >= DATE_SUB(NOW(), INTERVAL 1 WEEK)
                             GROUP BY s.section_name, q.question_text
                             ORDER BY s.section_name, q.question_text";
$resultWeeklyQuestionAverage = mysqli_query($con, $sqlWeeklyQuestionAverage);

// Calculate Monthly Average by Question
$sqlMonthlyQuestionAverage = "SELECT s.section_name, q.question_text, AVG(r.rating_number) AS monthly_average
                              FROM Feedback f
                              INNER JOIN Rating r ON f.feedback_ID = r.feedback_ID
                              INNER JOIN Question q ON r.question_ID = q.question_ID
                              INNER JOIN Section s ON q.section_ID = s.section_ID
                              WHERE f.feedback_timestamp >= DATE_SUB(NOW(), INTERVAL 1 MONTH)
                              GROUP BY s.section_name, q.question_text
                              ORDER BY s.section_name, q.question_text";
$resultMonthlyQuestionAverage = mysqli_query($con, $sqlMonthlyQuestionAverage);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/dashboard-feedback-statistics.css">
</head>

<body>
    <div class="sidebar">
        <h2>Admin Dashboard</h2>
        <ul>
            <li><a href="admin_menu.php">Menu Content</a></li>
            <li><a href="#">Home Content</a></li>
            <li><a href="admin_feedback_content.php">Feedback Content</a></li>
            <li><a href="admin_feedback_record.php">Feedback Record</a></li>
            <li><a href="admin_feedback_statistics.php">Feedback Statistics</a></li>
            <li><a href="#">About Content</a></li>
        </ul>
    </div>

    <div class="container">
        <h2>Feedback Statistics</h2>

        <h3>Weekly Average Ratings by Section</h3>
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
                            <td>' . $row['section_name'] . '</td>
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
                            <td>' . $row['section_name'] . '</td>
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
                            <td>' . $row['section_name'] . '</td>
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
                            <td>' . $row['section_name'] . '</td>
                            <td>' . $row['question_text'] . '</td>
                            <td>' . round($row['monthly_average'], 2) . '</td>
                          </tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>
