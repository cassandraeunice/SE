<?php
include '../connect.php';

if(isset($_GET['category_id'])){
    $id = $_GET['category_id'];

    $sql = "DELETE FROM Category WHERE category_ID=$id";
    $result = mysqli_query($con, $sql);

    if($result){
        header('location:../admin_menu.php');
    }else{
        die(mysqli_error($con));
    }
}
