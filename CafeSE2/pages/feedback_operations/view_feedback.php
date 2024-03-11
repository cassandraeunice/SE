<?php
include '../connect.php';

// Check if feedback_id is provided in the URL
if(isset($_GET['feedback_id'])) {
    $feedback_id = $_GET['feedback_id'];
    
    // Fetch feedback details
    $feedback_query = "SELECT f.*, CONCAT(c.customer_first_name, ' ', c.customer_last_name) AS customer_name, c.customer_email
                        FROM Feedback f
                        INNER JOIN Customer c ON f.customer_ID = c.customer_ID
                        WHERE f.feedback_ID = $feedback_id";
    $feedback_result = mysqli_query($con, $feedback_query);
    $feedback_row = mysqli_fetch_assoc($feedback_result);
    
    // Fetch questions and ratings
    $questions_query = "SELECT q.question_text, r.rating_number, s.section_name
                        FROM Rating r
                        INNER JOIN Question q ON r.question_ID = q.question_ID
                        INNER JOIN Section s ON q.section_ID = s.section_ID
                        WHERE r.feedback_ID = $feedback_id";
    $questions_result = mysqli_query($con, $questions_query);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Feedback</title>
    <link rel="stylesheet" href="../../css/view-feedback.css">
</head>

<body>
    <div class="container">
        <h2>Feedback Details</h2>
        <table class="table">
            <tbody>
                <tr>
                    <th scope="row">Feedback ID</th>
                    <td><?php echo $feedback_row['feedback_ID']; ?></td>
                </tr>
                <tr>
                    <th scope="row">Customer Name</th>
                    <td><?php echo $feedback_row['customer_name']; ?></td>
                </tr>
                <tr>
                    <th scope="row">Email</th>
                    <td><?php echo $feedback_row['customer_email']; ?></td>
                </tr>
                <tr>
                    <th scope="row">Timestamp</th>
                    <td><?php echo $feedback_row['feedback_timestamp']; ?></td>
                </tr>
            </tbody>
        </table>

        <h2>Questions and Ratings</h2>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Question Section</th>
                    <th scope="col">Question</th>
                    <th scope="col">Rating</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($questions_result) {
                    while ($row = mysqli_fetch_assoc($questions_result)) {
                        echo '<tr>
                                <td>' . $row['section_name'] . '</td>
                                <td>' . $row['question_text'] . '</td>
                                <td>' . $row['rating_number'] . '</td>
                            </tr>';
                    }
                }
                ?>
            </tbody>
        </table>

        <div>
            <h2>Comments/Suggestions</h2>
            <p><?php echo $feedback_row['feedback_experience']; ?></p>
        </div>
        <div class="back">
            <a href="../admin_feedback_record.php">Back</a>
        </div>
    </div>
</body>

</html>
