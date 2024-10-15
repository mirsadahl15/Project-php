<?php

$servername = "127.0.0.1:3306";
$username = "root";  // your MySQL username
$password = "";  // your MySQL password
$dbname = "data_base";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
}
?>