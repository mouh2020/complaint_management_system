<?php
$link = mysqli_connect('localhost', 'root', '0550', '') or die("Erreur de connexion : " . mysqli_connect_error());

// Create database
$req = "CREATE DATABASE IF NOT EXISTS complaintssystem";
if (mysqli_query($link, $req)) {
    echo "Database 'complaintssystem' created successfully.<br>";
} else {
    die("Error creating database: " . mysqli_error($link));
}

// Select the database
mysqli_select_db($link, 'complaintssystem') or die("Error selecting database: " . mysqli_error($link));

// Create User table
$sqlUser = "CREATE TABLE IF NOT EXISTS User (
    userId INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE
)";
if (mysqli_query($link, $sqlUser)) {
    echo "Table 'User' created successfully.<br>";
} else {
    die("Error creating User table: " . mysqli_error($link));
}

// Create Admin table
$sqlAdmin = "CREATE TABLE IF NOT EXISTS Admin (
    adminId INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(100) NOT NULL
)";
if (mysqli_query($link, $sqlAdmin)) {
    echo "Table 'Admin' created successfully.<br>";
} else {
    die("Error creating Admin table: " . mysqli_error($link));
}

// Create Complaint table
$sqlComplaint = "CREATE TABLE IF NOT EXISTS Complaint (
    complaintId INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    userId INT NOT NULL,
    title VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'Pending',
    dateSubmitted DATE NOT NULL,
    resolvedDate DATE DEFAULT NULL,
    picture VARCHAR(255) DEFAULT NULL,
    imageData LONGBLOB DEFAULT NULL, -- Added this column
    FOREIGN KEY (userId) REFERENCES User(userId) ON DELETE CASCADE
)";
if (mysqli_query($link, $sqlComplaint)) {
    echo "Table 'Complaint' created successfully.<br>";
} else {
    die("Error creating Complaint table: " . mysqli_error($link));
}

// Check if 'imageData' column exists
$checkColumn = mysqli_query($link, "SHOW COLUMNS FROM Complaint LIKE 'imageData'");
if (mysqli_num_rows($checkColumn) == 0) {
    $alterTable = "ALTER TABLE Complaint ADD COLUMN imageData LONGBLOB DEFAULT NULL";
    if (mysqli_query($link, $alterTable)) {
        echo "Column 'imageData' added successfully.<br>";
    } else {
        die("Error adding 'imageData' column: " . mysqli_error($link));
    }
}

mysqli_close($link);
?>