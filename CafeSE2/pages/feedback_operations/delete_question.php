<?php
include '../connect.php';

if(isset($_GET['question_id'])){
    $question_id = $_GET['question_id'];

    // Delete the question from the database
    $sql = "DELETE FROM Question WHERE question_ID = $question_id";
    $result = mysqli_query($con, $sql);

    if($result){
        header('location:../admin_feedback_content.php');
    } else {
        die(mysqli_error($con));
    }
}
?>
