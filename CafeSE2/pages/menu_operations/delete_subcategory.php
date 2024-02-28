<?php
include '../connect.php';

if(isset($_GET['subcategory_id'])){
    $id = $_GET['subcategory_id'];

    $sql = "DELETE FROM Subcategory WHERE subcategory_ID=$id";
    $result = mysqli_query($con, $sql);

    if($result){
        header('location:../admin_menu.php');
    }else{
        die(mysqli_error($con));
    }
}
