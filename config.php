<?php 

$server = "localhost";
$user = "root";
$pass = ""; // Leave this completely empty
$database = "kg_sentosa";

$conn = mysqli_connect($server, $user, $pass, $database);

if (!$conn) {
    die("<script>alert('Connection Failed.')</script>");
}

?>