<?php
include '../connect.php';

if(isset($_GET['product_id'])){
    $id = $_GET['product_id'];

    // Check if the product references any category
    $sql_check_category_reference = "SELECT COUNT(*) AS num_references FROM Product WHERE product_ID=$id AND category_ID IS NOT NULL";
    $result_check_category_reference = mysqli_query($con, $sql_check_category_reference);
    $row = mysqli_fetch_assoc($result_check_category_reference);
    $num_references = $row['num_references'];

    if ($num_references > 0) {
        // If the product references a category, display an alert message
        echo '<script>window.location.href="../admin_menu.php#product_error";</script>';
    } else {
        // If the product does not reference any category, proceed with deleting the product
        $sql_delete_product = "DELETE FROM Product WHERE product_ID=$id";
        $result_delete_product = mysqli_query($con, $sql_delete_product);
        if ($result_delete_product) {
            header('location:../admin_menu.php');
        } else {
            die(mysqli_error($con));
        }
    }
}
?>
