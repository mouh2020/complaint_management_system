<?php
$host = "localhost";
$username = "root";
$password = "0550";
$dbname = "ComplaintsSystem";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>