<?php

$conn = new mysqli("localhost", "root", "", "phpsimplecurd");

if($conn->connect_error){
die("connection is failed" .mysqli_connect_error());
}else{
    // echo "connection is successfully connected";
}


?>