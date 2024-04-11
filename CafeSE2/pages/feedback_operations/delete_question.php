<?php

session_start(); // Start session to access session variables

// Check if the admin is logged in
if (!isset($_SESSION['admin_ID']) || !isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Check if the admin's login status is remembered via cookie
    if (!isset($_COOKIE['admin_logged_in']) || $_COOKIE['admin_logged_in'] !== 'true') {
        // If not logged in and no remembered login status, redirect to login page
        header("Location: ../login.php");
        exit();
    }
}

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