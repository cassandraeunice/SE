<?php
include 'connect.php';

if(isset($_POST['homeBtn'])){
    header("Location: home.php"); // Redirect to admin_home.php
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/dashboard-feedback-content.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
                <button type="submit" name="homeBtn"><i class="fa fa-home"></i> Home</button>
            </div>
        </form>
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
                            <button class="btn-update"><a href="feedback_operations/update_question.php?question_id=' . $question_id . '" class="text-light"><svg width="20px" height="20px" viewBox="0 -0.5 21 21" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#FFF3E2"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <title>edit [#FFF3E2]</title> <desc>Created with Sketch.</desc> <defs> </defs> <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g id="Dribbble-Light-Preview" transform="translate(-99.000000, -400.000000)" fill="#FFF3E2"> <g id="icons" transform="translate(56.000000, 160.000000)"> <path d="M61.9,258.010643 L45.1,258.010643 L45.1,242.095788 L53.5,242.095788 L53.5,240.106431 L43,240.106431 L43,260 L64,260 L64,250.053215 L61.9,250.053215 L61.9,258.010643 Z M49.3,249.949769 L59.63095,240 L64,244.114985 L53.3341,254.031929 L49.3,254.031929 L49.3,249.949769 Z" id="edit-[#FFF3E2]"> </path> </g> </g> </g> </g></svg></a></button>
                            <button class="btn-delete" onclick="confirmDelete(' . $question_id . ')"><a class="text-light"><svg width="20px" height="20px" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg" fill="#FFF3E2"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"><path fill="#FFF3E2" d="M352 192V95.936a32 32 0 0 1 32-32h256a32 32 0 0 1 32 32V192h256a32 32 0 1 1 0 64H96a32 32 0 0 1 0-64h256zm64 0h192v-64H416v64zM192 960a32 32 0 0 1-32-32V256h704v672a32 32 0 0 1-32 32H192zm224-192a32 32 0 0 0 32-32V416a32 32 0 0 0-64 0v320a32 32 0 0 0 32 32zm192 0a32 32 0 0 0 32-32V416a32 32 0 0 0-64 0v320a32 32 0 0 0 32 32z"></path></g></svg></a></button>
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