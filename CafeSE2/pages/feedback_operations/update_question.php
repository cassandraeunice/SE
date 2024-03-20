<?php
include '../connect.php';

// Fetch question details based on the ID passed in the URL
if (isset($_GET['question_id'])) {
    $question_id = $_GET['question_id'];
    $sql = "SELECT q.*, s.section_ID, s.section_name FROM Question q LEFT JOIN Section s ON q.section_ID = s.section_ID WHERE question_ID = $question_id";
    $result = mysqli_query($con, $sql);
    $question = mysqli_fetch_assoc($result);
} else {
    // If no question ID is provided, redirect back to the admin menu page or handle the error accordingly
    header('location:../admin_menu.php');
    exit; // Stop further execution
}

// Fetch existing sections from the database
$section_query = "SELECT * FROM Section";
$section_result = mysqli_query($con, $section_query);
$sections = mysqli_fetch_all($section_result, MYSQLI_ASSOC);

if (isset($_POST['submit'])) {
    $new_question_text = $_POST['question_text'];
    $new_section_ID = $_POST['section_ID'];

    // Check if the updated question text already exists in the database for the selected section and is not archived
    $check_query = "SELECT * FROM Question WHERE question_text = '$new_question_text' AND section_ID = '$new_section_ID' AND question_ID != $question_id AND archive_value = 0";
    $check_result = mysqli_query($con, $check_query);
    $existing_question = mysqli_fetch_assoc($check_result);

    if ($existing_question) {
        // If the updated question text already exists for the selected section and is not archived, display an error message
        echo "<script>window.onload = function() { alert('Question text already exists for the selected section.'); }</script>";
    } else {
        // Update the question in the database
        $update_query = "UPDATE Question SET question_text = '$new_question_text', section_ID = '$new_section_ID' WHERE question_ID = $question_id";
        $update_result = mysqli_query($con, $update_query);

        if ($update_result) {
            header('location:../admin_feedback_content.php');
            exit; // Stop further execution
        } else {
            echo "Error: " . $update_query . "<br>" . mysqli_error($con);
        }
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Update Question</title>
    <link rel="stylesheet" href="../../css/update-question.css">
    <script>
        function confirmUpdate() {
            var result = confirm("Are you sure you want to update?");
            return result;
        }
    </script>
</head>

<body>
    <div class="container my-5">
    <a href="../admin_feedback_content.php" class="btn btn-secondary"><svg width="36px" height="36px" viewBox="0 0 1024 1024" fill="#FFF3E2" class="icon" version="1.1" xmlns="http://www.w3.org/2000/svg" stroke="#FFF3E2"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path d="M669.6 849.6c8.8 8 22.4 7.2 30.4-1.6s7.2-22.4-1.6-30.4l-309.6-280c-8-7.2-8-17.6 0-24.8l309.6-270.4c8.8-8 9.6-21.6 2.4-30.4-8-8.8-21.6-9.6-30.4-2.4L360.8 480.8c-27.2 24-28 64-0.8 88.8l309.6 280z" fill=""></path></g></svg></a>
        <h2>Update Question</h2>
        <form method="post" onsubmit="return confirmUpdate()">
            <div class="mb-3">
                <label>Section</label>
                <select class="form-select" name="section_ID" required>
                    <option value="">Select Section</option>
                    <?php foreach ($sections as $section) : ?>
                        <option value="<?php echo $section['section_ID']; ?>" <?php echo ($question['section_ID'] == $section['section_ID']) ? 'selected' : ''; ?>><?php echo $section['section_name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label>Question Text</label>
                <input type="text" class="form-control" placeholder="Enter question text" name="question_text" value="<?php echo $question['question_text']; ?>" required>
            </div>
            <div class="button-container">
                <button type="submit" class="btn btn-primary" name="submit">Submit</button>
                
            </div>
        </form>
    </div>
</body>

</html>
