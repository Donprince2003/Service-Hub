<?php
$server = "localhost"; 
$username = "don"; 
$password = "don"; 
$dbname = "mini"; 

$conn = new mysqli($server, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
