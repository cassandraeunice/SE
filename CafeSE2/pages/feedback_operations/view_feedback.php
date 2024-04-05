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
        <a href="../admin_feedback_record.php"><svg width="36px" height="36px" viewBox="0 0 1024 1024" fill="#271300" class="icon" version="1.1" xmlns="http://www.w3.org/2000/svg" stroke="#271300"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M669.6 849.6c8.8 8 22.4 7.2 30.4-1.6s7.2-22.4-1.6-30.4l-309.6-280c-8-7.2-8-17.6 0-24.8l309.6-270.4c8.8-8 9.6-21.6 2.4-30.4-8-8.8-21.6-9.6-30.4-2.4L360.8 480.8c-27.2 24-28 64-0.8 88.8l309.6 280z" fill=""></path></g></svg></a>
        
        <h2>View Feedback</h2>
        <h3>Feedback Details</h3>
        <hr></hr><br></br>
        <div class="group1">
            <div class="feedbackID">
                <label>Feedback ID:</label><br></br>
                <input type="text" value="<?php echo $feedback_row['feedback_ID']; ?>" disabled>
            </div>
            <div class="timestamp">
                <label>Timestamp:</label><br></br>
                <input type="text" value="<?php echo $feedback_row['feedback_timestamp']; ?>" disabled>
            </div>
        </div>
        <div class="group2">
            <div class="customerName">
                <label>Customer Name:</label><br></br>
                <input type="text" value="<?php echo $feedback_row['customer_name']; ?>" disabled>
            </div>
            <div class="email">
                <label>Email:</label><br></br>
                <input type="text" value="<?php echo $feedback_row['customer_email']; ?>" disabled>
            </div>
        </div>
        <h3>Questions and Ratings</h3>
        <hr></hr><br></br>
        <table class="table">
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

        <h3>Comments/Suggestions</h3>
        <hr></hr><br></br>
        <textarea disabled><?php echo $feedback_row['feedback_experience']; ?></textarea>
    </div>
</body>

</html>
