<?php
$host       ="localhost";
$database   ="webproject";
$username   ="root";
$password   ="";

$conn =new mysqli($host, $username, $password, $database);
if($conn->connect_error){
    die("Connection failed: " . $conn->connect_error);
}
?>