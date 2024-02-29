<?php
include '../connect.php';

if(isset($_GET['product_id'])){
    $id = $_GET['product_id'];

    $sql = "DELETE FROM product WHERE product_ID=$id";
    $result = mysqli_query($con, $sql);

    if($result){
        header('location:../admin_menu.php');
    }else{
        die(mysqli_error($con));
    }
}
