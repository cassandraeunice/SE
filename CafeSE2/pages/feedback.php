<?php

include 'connect.php';

function sanitize_input($data) {
    $data = trim($data); // Remove leading/trailing whitespace
    $data = stripslashes($data); // Remove backslashes
    $data = htmlspecialchars($data); // Convert special characters to HTML entities
    return $data;
}

// Retrieve questions from the database
$sql = "SELECT q.*, s.section_name 
        FROM Question q
        INNER JOIN Section s ON q.section_ID = s.section_ID";
$result = $con->query($sql);
$questions = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $questions[$row["section_name"]][] = array("id" => $row["question_ID"], "text" => $row["question_text"]);
    }
}

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input data
    $customer_first_name = isset($_POST['customer_first_name']) ? sanitize_input($_POST['customer_first_name']) : '';
    $customer_last_name = isset($_POST['customer_last_name']) ? sanitize_input($_POST['customer_last_name']) : '';
    $customer_email = isset($_POST['customer_email']) ? sanitize_input($_POST['customer_email']) : '';
    $feedback_experience = isset($_POST['feedback_experience']) ? sanitize_input($_POST['feedback_experience']) : '';
    $feedback_timestamp = date('Y-m-d H:i:s');

    // Insert customer if not exists
    $sql = "INSERT INTO Customer (customer_first_name, customer_last_name, customer_email)
            VALUES ('$customer_first_name', '$customer_last_name', '$customer_email')
            ON DUPLICATE KEY UPDATE customer_email='$customer_email'";
    $con->query($sql);

    // Get customer ID
    $customer_id = $con->insert_id;

    // Insert feedback
    $sql = "INSERT INTO Feedback (customer_ID, feedback_timestamp, feedback_experience)
            VALUES ('$customer_id', '$feedback_timestamp', '$feedback_experience')";
    $con->query($sql);

    // Get feedback ID
    $feedback_id = $con->insert_id;

    // Insert ratings
    foreach ($_POST['ratings'] as $question_id => $rating_number) {
        $sql = "INSERT INTO Rating (feedback_ID, question_ID, rating_number)
                VALUES ('$feedback_id', '$question_id', '$rating_number')";
        $con->query($sql);
    }

    // Close connection
    $con->close();

    // Redirect to thank you page or any other page
    header("Location: thank_you.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cafe Siena</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../css/feedback-form.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2 class="welcome-text">Share Your Experience</h2>
        <p class="message">We value your feedback! Help us enhance your cafe experience by sharing your thoughts on our offerings.</p>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label id="label" for="customer_first_name">First Name:</label>
                <input type="text" class="form-control" id="customer_first_name" name="customer_first_name" placeholder="Enter your first name">
            </div>
            <div class="form-group">
                <label id="label" for="customer_last_name">Last Name:</label>
                <input type="text" class="form-control" id="customer_last_name" name="customer_last_name" placeholder="Enter your last name">
            </div>
            <div class="form-group">
                <label id="label" for="customer_email">Email*:</label>
                <input type="email" class="form-control" id="customer_email" name="customer_email" required placeholder="Enter your email">
            </div>
            
            <?php foreach ($questions as $section => $section_questions): ?>
                <h3><?php echo $section; ?></h3>
                <?php foreach ($section_questions as $question): ?>
                    <div class="form-group">
                        <label><?php echo $question['text']; ?></label>
                        <div class="row">
                            <div class="col">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="ratings[<?php echo $question['id']; ?>]" id="rating_<?php echo $question['id']; ?>_5" value="5" required>
                                    <label class="form-check-label" for="rating_<?php echo $question['id']; ?>_5">Very Good</label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="ratings[<?php echo $question['id']; ?>]" id="rating_<?php echo $question['id']; ?>_4" value="4" required>
                                    <label class="form-check-label" for="rating_<?php echo $question['id']; ?>_4">Good</label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="ratings[<?php echo $question['id']; ?>]" id="rating_<?php echo $question['id']; ?>_3" value="3" required>
                                    <label class="form-check-label" for="rating_<?php echo $question['id']; ?>_3">Fair</label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="ratings[<?php echo $question['id']; ?>]" id="rating_<?php echo $question['id']; ?>_2" value="2" required>
                                    <label class="form-check-label" for="rating_<?php echo $question['id']; ?>_2">Poor</label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="ratings[<?php echo $question['id']; ?>]" id="rating_<?php echo $question['id']; ?>_1" value="1" required>
                                    <label class="form-check-label" for="rating_<?php echo $question['id']; ?>_1">Very Poor</label>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endforeach; ?>
            
            <div class="form-group">
                <label id="label" for="feedback_experience">Comments and Suggestions:</label>
                <textarea class="form-control" id="feedback_experience" name="feedback_experience"></textarea>
            </div>

            <button class="btn-submit" type="submit">Submit</button>
        </form><br>
    </div>
</body>
</html>





