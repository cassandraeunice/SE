<?php
include '../connect.php';

if(isset($_GET['question_id'])){
    $question_id = $_GET['question_id'];

    // Update the archive_value flag for the question
    $sql = "UPDATE Question SET archive_value = 1 WHERE question_ID = $question_id";
    $result = mysqli_query($con, $sql);

    if($result){
        header('location:../admin_feedback_content.php');
    } else {
        die(mysqli_error($con));
    }
}
?>