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

if(isset($_GET['subcategory_id'])){
    $id = $_GET['subcategory_id'];

    // Check if there are any products referencing the subcategory
    $sql_check_product_reference = "SELECT COUNT(*) AS num_product_references FROM Product WHERE subcategory_ID=$id";
    $result_check_product_reference = mysqli_query($con, $sql_check_product_reference);
    $row_product_reference = mysqli_fetch_assoc($result_check_product_reference);
    $num_product_references = $row_product_reference['num_product_references'];

    if ($num_product_references > 0) {
        // If there are products referencing the subcategory, display an alert message for products
        echo '<script>setTimeout(function() { window.location.href = "../admin_menu.php#product_error2"; }, 50);</script>';
    } else {
        // If there are no products referencing the subcategory, proceed with deleting the subcategory
        $sql_delete_subcategory = "DELETE FROM Subcategory WHERE subcategory_ID=$id";
        $result_delete_subcategory = mysqli_query($con, $sql_delete_subcategory);
        if ($result_delete_subcategory) {
            header('location:../admin_menu.php');
        } else {
            die(mysqli_error($con));
        }
    }
}
?>
