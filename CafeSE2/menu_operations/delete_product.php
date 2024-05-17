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

if (isset($_GET['product_id'])) {
    $id = $_GET['product_id'];

    // Prepare and execute the query to fetch the product_popular_value
    $sql = "SELECT product_popular_value FROM Product WHERE product_ID = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // Check if the product_popular_value is 1
    if ($row['product_popular_value'] == 1) {
        echo '<script>setTimeout(function() { window.location.href = "../admin_menu.php#popular_error"; }, 50);</script>';

    } else {
        // If product_popular_value is not 1, proceed with deletion
        $sql = "DELETE FROM Product WHERE product_ID = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();

        if ($result) {
            header('location:../admin_menu.php');
        } else {
            die(mysqli_error($con));
        }
    }
}
?>