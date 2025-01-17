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

// Insert data into User table if not exists
$checkUser = "SELECT COUNT(*) AS count FROM User";
$resultUser = mysqli_query($link, $checkUser);
$rowUser = mysqli_fetch_assoc($resultUser);
if ($rowUser['count'] == 0) {
    $insertUser = "INSERT INTO User (username, password, email) VALUES
        ('user1', 'password123', 'user1@example.com'),
        ('user2', 'password456', 'user2@example.com'),
        ('test', '$2y$10$8G1SSfEiwVPiZHFWJxRyye4hhj3fX.nk/k6.w/U/L1Cwlf8QkdHAW', 'user3@example.com')";
    if (mysqli_query($link, $insertUser)) {
        echo "User data inserted successfully.<br>";
    } else {
        die("Error inserting User data: " . mysqli_error($link));
    }
} else {
    echo "User data already exists.<br>";
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

// Insert data into Admin table if not exists
$checkAdmin = "SELECT COUNT(*) AS count FROM Admin";
$resultAdmin = mysqli_query($link, $checkAdmin);
$rowAdmin = mysqli_fetch_assoc($resultAdmin);
if ($rowAdmin['count'] == 0) {
    $insertAdmin = "INSERT INTO Admin (username, password) VALUES
        ('admin', 'admin'),
        ('admin2', 'adminsecure')";
    if (mysqli_query($link, $insertAdmin)) {
        echo "Admin data inserted successfully.<br>";
    } else {
        die("Error inserting Admin data: " . mysqli_error($link));
    }
} else {
    echo "Admin data already exists.<br>";
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
    FOREIGN KEY (userId) REFERENCES User(userId) ON DELETE CASCADE
)";
if (mysqli_query($link, $sqlComplaint)) {
    echo "Table 'Complaint' created successfully.<br>";
} else {
    die("Error creating Complaint table: " . mysqli_error($link));
}

// Insert data into Complaint table if not exists
$checkComplaint = "SELECT COUNT(*) AS count FROM Complaint";
$resultComplaint = mysqli_query($link, $checkComplaint);
$rowComplaint = mysqli_fetch_assoc($resultComplaint);
if ($rowComplaint['count'] == 0) {
    $insertComplaint = "INSERT INTO Complaint (userId, title, description, status, dateSubmitted, resolvedDate) VALUES
        (1, 'Broken AC', 'The AC in the main hall is not working.', 'Resolved', '2025-01-01', '2025-01-16'),
        (2, 'Noisy Neighbors', 'The neighbors are making too much noise at night.', 'Resolved', '2025-01-05', '2025-01-16'),
        (1, 'Leaking Roof', 'The roof is leaking during rain.', 'Pending', '2025-01-10', NULL)";
    if (mysqli_query($link, $insertComplaint)) {
        echo "Complaint data inserted successfully.<br>";
    } else {
        die("Error inserting Complaint data: " . mysqli_error($link));
    }
} else {
    echo "Complaint data already exists.<br>";
}

mysqli_close($link);
?>