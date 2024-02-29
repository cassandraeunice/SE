<?php
include '../connect.php';

if(isset($_GET['section_id'])){
    $id = $_GET['section_id'];

    $sql = "DELETE FROM Section WHERE section_ID=$id";
    $result = mysqli_query($con, $sql);

    if($result){
        header('location:../admin_feedback_content.php');
    }else{
        die(mysqli_error($con));
    }
}
?>
