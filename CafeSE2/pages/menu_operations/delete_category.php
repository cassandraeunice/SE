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

if(isset($_GET['category_id'])){
    $id = $_GET['category_id'];

    // Check if there are any products referencing the category
    $sql_check_product_reference = "SELECT COUNT(*) AS num_product_references FROM Product WHERE category_ID=$id";
    $result_check_product_reference = mysqli_query($con, $sql_check_product_reference);
    $row_product_reference = mysqli_fetch_assoc($result_check_product_reference);
    $num_product_references = $row_product_reference['num_product_references'];

    // Check if there are any subcategories under the category
    $sql_check_subcategory_reference = "SELECT COUNT(*) AS num_subcategory_references FROM Subcategory WHERE category_ID=$id";
    $result_check_subcategory_reference = mysqli_query($con, $sql_check_subcategory_reference);
    $row_subcategory_reference = mysqli_fetch_assoc($result_check_subcategory_reference);
    $num_subcategory_references = $row_subcategory_reference['num_subcategory_references'];

    if ($num_product_references > 0) {
        // If there are products referencing the category, display an alert message for products
        echo '<script>setTimeout(function() { window.location.href = "../admin_menu.php#product_error"; }, 50);</script>';
    } elseif ($num_subcategory_references > 0) {
        // If there are subcategories referencing the category, display an alert message for subcategories
        echo '<script>setTimeout(function() { window.location.href="../admin_menu.php#subcategory_error"; }, 50)</script>';
    } else {
        // If there are no products or subcategories referencing the category, proceed with deleting the category
        $sql_delete_category = "DELETE FROM Category WHERE category_ID=$id";
        $result_delete_category = mysqli_query($con, $sql_delete_category);
        if ($result_delete_category) {
            header('location:../admin_menu.php');
        } else {
            die(mysqli_error($con));
        }
    }
}
?>
