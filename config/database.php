<?php
// Database configuration
$host = "localhost";
$username = "root";
$password = "0550";
$dbname = "ComplaintsSystem";

// Create a connection using MySQLi
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>