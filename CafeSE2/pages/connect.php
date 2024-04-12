<?php

$con=new mysqli('localhost','root','','cafe_siena5');

if(!$con){
    die(mysqli_error($con));
}