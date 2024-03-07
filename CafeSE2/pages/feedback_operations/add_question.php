<?php
include '../connect.php';

// Fetch existing sections from the database
$section_query = "SELECT * FROM Section";
$section_result = mysqli_query($con, $section_query);
$sections = mysqli_fetch_all($section_result, MYSQLI_ASSOC);

if (isset($_POST['submit'])) {
    $section_ID = $_POST['section_ID'];
    $question_text = $_POST['question_text'];

    // Check if the question text already exists for the selected section and is not archived
    $check_query = "SELECT * FROM Question WHERE question_text = '$question_text' AND section_ID = '$section_ID' AND archive_value = 0";
    $check_result = mysqli_query($con, $check_query);
    $existing_question = mysqli_fetch_assoc($check_result);

    if ($existing_question) {
        // Question text already exists for the selected section and is not archived, display an error message
        echo "<script>window.onload = function() { alert('Question text already exists for the selected section.'); }</script>";
    } else {
        // Question text does not exist for the selected section or is archived, proceed with insertion
        $sql = "INSERT INTO Question (section_ID, question_text) VALUES ('$section_ID', '$question_text')";
        $result = mysqli_query($con, $sql);
        if ($result) {
            header('location:../admin_feedback_content.php');
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($con);
        }
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add Question</title>
    <link rel="stylesheet" href="../../css/add-question.css">
</head>

<body>
    <div class="container my-5">
        <h2>Add Question</h2>
        <form method="post">
            <div class="mb-3">
                <label>Section:</label>
                <select class="form-select" name="section_ID" required>
                    <option value="">Select Section</option>
                    <?php foreach ($sections as $section) : ?>
                        <option value="<?php echo $section['section_ID']; ?>"><?php echo $section['section_name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label>Question Text:</label>
                <input type="text" class="form-control" placeholder="Enter question text" name="question_text" required>
            </div>
            <div class="button-container">
                <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                <a href="../admin_feedback_content.php" class="btn btn-secondary">Back</a>
            </div>
            
        </form>
    </div>
</body>

</html>
