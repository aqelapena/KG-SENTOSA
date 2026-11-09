<?php
$servername = "localhost";
$username = "root";       
$password = ""; // Change this from "root" to an empty string            
$dbname = "kg_sentosa";  

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
