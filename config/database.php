<?php
$host = 'localhost';
$username = 'root'; // Update this if your MySQL username is different
$password = '';     // Update this if your MySQL password is not empty
$dbName = 'complaints_db';

// Connect to MySQL server
$conn = new mysqli($host, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if not exists
$dbCreateQuery = "CREATE DATABASE IF NOT EXISTS $dbName";
if (!$conn->query($dbCreateQuery)) {
    die("Database creation failed: " . $conn->error);
}

// Select the database
$conn->select_db($dbName);

// Create `Admin` table
$adminTable = "CREATE TABLE IF NOT EXISTS `Admin` (
  `adminId` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`adminId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";

$conn->query($adminTable);

// Insert data into `Admin`
$conn->query("INSERT IGNORE INTO `Admin` (`adminId`, `username`, `password`) VALUES
(1, 'admin', 'admin'),
(2, 'admin2', 'adminsecure')");

// Create `User` table
$userTable = "CREATE TABLE IF NOT EXISTS `User` (
  `userId` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`userId`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";

$conn->query($userTable);

// Insert data into `User`
$conn->query("INSERT IGNORE INTO `User` (`userId`, `username`, `password`, `email`) VALUES
(1, 'user1', '123456', 'user1@example.com'),
(2, 'user2', '123456', 'user2@example.com'),
(3, 'test', '123456', 'user3@example.com'),
(4, 'user', '123456', 'user4@example.com'),
(5, 'sa', '123456', 'user5@example.com'),
(6, 'med', '123456', 'user6@example.com'),
(7, 'medmed', '123456', 'user7@example.com'),
(8, 'testgmail', '123456', 'test@gmail.com')");

// Create `Complaint` table
$complaintTable = "CREATE TABLE IF NOT EXISTS `Complaint` (
  `complaintId` int NOT NULL AUTO_INCREMENT,
  `userId` int NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Pending',
  `dateSubmitted` date NOT NULL,
  `resolvedDate` date DEFAULT NULL,
  PRIMARY KEY (`complaintId`),
  KEY `userId` (`userId`),
  CONSTRAINT `Complaint_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `User` (`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";

$conn->query($complaintTable);

// Insert data into `Complaint`
$conn->query("INSERT IGNORE INTO `Complaint` (`complaintId`, `userId`, `title`, `description`, `status`, `dateSubmitted`, `resolvedDate`) VALUES
(1, 1, 'Broken AC', 'The AC in the main hall is not working.', 'Resolved', '2025-01-01', '2025-01-16'),
(2, 2, 'Noisy Neighbors', 'The neighbors are making too much noise at night.', 'Resolved', '2025-01-05', '2025-01-16'),
(3, 1, 'Leaking Roof', 'The roof is leaking during rain.', 'Resolved', '2025-01-10', '2025-01-16'),
(4, 6, 'Network Issue', 'Internet is not working in Room 101', 'Resolved', '2025-01-16', '2025-01-16'),
(5, 6, 'Leaking Tap', 'The tap in the kitchen is leaking', 'Resolved', '2025-01-16', '2025-01-16'),
(6, 6, 'Power Outage', 'Power outage in Building B', 'Pending', '2025-01-16', NULL),
(7, 6, 'Broken Chair', 'The chair in my office is broken', 'Pending', '2025-01-16', NULL),
(8, 6, 'AC Not Working', 'The air conditioner is not cooling properly', 'Resolved', '2025-01-16', '2025-01-16'),
(9, 6, 'first complaint', 'Yes my first complaint', 'Resolved', '2025-01-16', '2025-01-16'),
(10, 6, 'first complaint', 'Yes my first complaint', 'Resolved', '2025-01-16', '2025-01-16'),
(11, 6, 'hi there', 'Wow description', 'Pending', '2025-01-16', NULL),
(12, 6, 'The second complaint', 'Hi there', 'Pending', '2025-01-16', NULL),
(13, 6, 'The second complaint', 'Hi there', 'Resolved', '2025-01-16', '2025-01-16')");
?>