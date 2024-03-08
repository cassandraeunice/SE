<?php
include '../connect.php';

if(isset($_GET['category_id'])){
    $id = $_GET['category_id'];

    // Check if there are any subcategories under the category
    $sql_check_subcategories = "SELECT COUNT(*) AS num_subcategories FROM Subcategory WHERE category_ID=$id";
    $result_check_subcategories = mysqli_query($con, $sql_check_subcategories);
    $row = mysqli_fetch_assoc($result_check_subcategories);
    $num_subcategories = $row['num_subcategories'];

    if ($num_subcategories > 0) {
        // If there are subcategories, display an alert message
        echo '<script>window.location.href="../admin_menu.php#category_error";</script>';
    } else {
        // If there are no subcategories, proceed with deleting the category
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
