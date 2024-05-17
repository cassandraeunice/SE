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

if(isset($_POST['submit'])){
    $section_name = mysqli_real_escape_string($con, $_POST['section_name']);

    // Check if the section name already exists
    $check_query = "SELECT * FROM Section WHERE section_name = '$section_name'";
    $check_result = mysqli_query($con, $check_query);
    $existing_section = mysqli_fetch_assoc($check_result);

    if($existing_section) {
        // Section name already exists, display an error message
        echo "Section name already exists.";
    } else {
        // Section name does not exist, proceed with insertion
        $sql = "INSERT INTO Section (section_name) VALUES ('$section_name')";
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
    <title>Add Section</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container my-5">
        <h2>Add Section</h2>
        <form method="post">
            <div class="mb-3">
                <label>Section Name</label>
                <input type="text" class="form-control" placeholder="Enter section name" name="section_name" required>
            </div>
            <button type="submit" class="btn btn-primary" name="submit">Submit</button>
            <a href="../admin_feedback_content.php" class="btn btn-secondary">Back</a>
        </form>
    </div>
</body>

</html>
