<?php
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
        echo "<script>window.onload = function() { alert('Product cannot be deleted because it is marked as popular.'); }</script>";
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