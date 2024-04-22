<?php

include 'connect.php';

function sanitize_input($data)
{
    $data = trim($data); // Remove leading/trailing whitespace
    $data = stripslashes($data); // Remove backslashes
    $data = htmlspecialchars($data); // Convert special characters to HTML entities
    return $data;
}

// Retrieve questions from the database
$sql = "SELECT q.*, s.section_name 
        FROM Question q
        INNER JOIN Section s ON q.section_ID = s.section_ID WHERE q.archive_value = 0";
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
    <script>
        function validateForm() {
            var starRatings = document.querySelectorAll('.star-rating');
            for (var i = 0; i < starRatings.length; i++) {
                var stars = starRatings[i].querySelectorAll('.fa-star.fa-solid');
                if (stars.length === 0) {
                    alert("Please rate all questions before submitting.");
                    return false;
                }
            }
            return true;
        }
        document.addEventListener('DOMContentLoaded', function() {
        var starRatings = document.querySelectorAll('.star-rating');
        starRatings.forEach(function(starRating) {
            var stars = starRating.querySelectorAll('.fa-star');
            stars.forEach(function(star) {
                star.addEventListener('click', function() {
                    var rating = this.getAttribute('data-rating');
                    var questionId = this.id.split('_')[1];
                    document.getElementById('rating_' + questionId).value = rating;
                    stars.forEach(function(s) {
                        if (s.getAttribute('data-rating') <= rating) {
                            s.classList.remove('fa-regular');
                            s.classList.add('fa-solid');
                        } else {
                            s.classList.remove('fa-solid');
                            s.classList.add('fa-regular');
                        }
                    });
                });
            });
        });
    });
    </script>
</head>

<body>
    <div class="container">
        <h2 class="welcome-text">Share Your Experience</h2>
        <p class="message">We value your feedback! Help us enhance your cafe experience by sharing your thoughts on our offerings.</p>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="return validateForm();">
            <div class="center">
                <div class="form-group">
                    <label id="label" for="customer_first_name">First Name:</label><br></br>
                    <input type="text" class="form-control" id="customer_first_name" name="customer_first_name" placeholder="Enter your first name"><br></br>
                </div>
                <div class="form-group">
                    <label id="label" for="customer_last_name">Last Name:</label><br></br>
                    <input type="text" class="form-control" id="customer_last_name" name="customer_last_name" placeholder="Enter your last name"><br></br>
                </div>
                <div class="form-group">
                    <label id="label" for="customer_email">Email:</label><br></br>
                    <input type="email" class="form-control" id="customer_email" name="customer_email" placeholder="Enter your email"><br></br>
                </div>

                <?php foreach ($questions as $section => $section_questions) : ?>
                    <h3><?php echo $section; ?></h3>
                    <?php foreach ($section_questions as $question) : ?>
                        <div class="form-group">
                            <label class="question"><?php echo $question['text']; ?></label>
                            <div class="star-rating" id="star-rating-<?php echo $question['id']; ?>">
                                <?php for ($i = 1; $i <= 5; $i++) : ?>
                                    <i class="fa-star fa-regular" data-rating="<?php echo $i; ?>" id="star_<?php echo $question['id']; ?>_<?php echo $i; ?>"></i>
                                <?php endfor; ?>
                                <input type="hidden" name="ratings[<?php echo $question['id']; ?>]" id="rating_<?php echo $question['id']; ?>" value="0" />
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endforeach; ?>

                <div class="form-group">
                    <label id="label" for="feedback_experience">Comments and Suggestions:</label><br>
                    <textarea class="form-control" id="feedback_experience" name="feedback_experience"></textarea>
                </div>
            </div>
            <div class="button-container">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form><br>
    </div>
</body>

</html>