<?php
include 'connect.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/dashboard-feedback-content.css">
    <script>
        function confirmDelete(questionId) {
            var result = confirm("Are you sure you want to delete?");
            if (result) {
                window.location.href = 'feedback_operations/delete_question.php?question_id=' + questionId;
            }
        }
    </script>
</head>

<body>
    <div class="sidebar">
        <h2>Admin Dashboard</h2>
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
    </div>

    <div class="container">
        <!--
        <h2>Question Sections</h2>
        <button class="btn btn-primary m-5"><a href="feedback_operations/add_section.php" class="text-light">Add Section</a></button>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Section ID</th>
                    <th scope="col">Section Name</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $section_query = "SELECT * FROM Section";
                $section_result = mysqli_query($con, $section_query);
                if ($section_result) {
                    while ($row = mysqli_fetch_assoc($section_result)) {
                        $section_id = $row['section_ID'];
                        $section_name = $row['section_name'];
                        echo '<tr>
                            <th scope="row">' . $section_id . '</th>
                            <td>' . $section_name . '</td>
                            <td>
                            <button class="btn btn-primary"><a href="feedback_operations/update_section.php?section_id=' . $section_id . '" class="text-light">Update</a></button>
                            <button class="btn btn-danger"><a href="feedback_operations/delete_section.php?section_id=' . $section_id . '" class="text-light">Delete</a></button>
                            </td>
                        </tr>';
                    }
                }
                ?>
            </tbody>
        </table>
        -->

        <h2>Questions</h2>
        <button class="btn btn-primary m-5"><a href="feedback_operations/add_question.php" class="text-light">Add Question</a></button>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Question ID</th>
                    <th scope="col">Question Text</th>
                    <th scope="col">Section</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $question_query = "SELECT q.*, s.section_name FROM Question q INNER JOIN Section s ON q.section_ID = s.section_ID WHERE q.archive_value = 0";
                $question_result = mysqli_query($con, $question_query);
                if ($question_result) {
                    while ($row = mysqli_fetch_assoc($question_result)) {
                        $question_id = $row['question_ID'];
                        $question_text = $row['question_text'];
                        $section_name = $row['section_name'];
                        echo '<tr>
                            <th scope="row">' . $question_id . '</th>
                            <td>' . $question_text . '</td>
                            <td>' . $section_name . '</td>
                            <td>
                            <button class="btn btn-primary"><a href="feedback_operations/update_question.php?question_id=' . $question_id . '" class="text-light">Update</a></button>
                            <button class="btn btn-danger" onclick="confirmDelete(' . $question_id . ')"><a class="text-light">Delete</a></button>
                            </td>
                        </tr>';
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>