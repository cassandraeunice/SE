<?php

$con=new mysqli('localhost','root','','cafe_siena2');

if(!$con){
    die(mysqli_error($con));
}