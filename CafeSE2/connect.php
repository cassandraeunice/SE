<?php

$con=new mysqli('localhost','root','','cafe_siena');

if(!$con){
    die(mysqli_error($con));
}