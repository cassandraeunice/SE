<?php

$con=new mysqli('localhost','root','','cafe_siena3');

if(!$con){
    die(mysqli_error($con));
}