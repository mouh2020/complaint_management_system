<?php
session_start();
include('../config/database.php'); // Include database connection

// Check if the user is logged in
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header('Location: ./login.php'); // Redirect to login page if not logged in
    exit;
}

// Handle logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy(); // Destroy the session
    header('Location: ../index.php'); // Redirect to home page
    exit;
}

// Get the username from the session
$username = $_SESSION['user_username'] ?? 'User'; // Fallback to 'User' if username is not set
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/styles/user/dashboard.css">
    <title>User Dashboard</title>
</head>

<body>
    <div class="dashboard-container">
        <h1>Welcome <?= htmlspecialchars($username ?? 'Guest') ?> to your Dashboard</h1>

        <ul>
            <li><a href="submit_complaint.php">Submit Complaint</a></li>
            <li><a href="view_complaints.php">View Complaints</a></li>
            <li><a href="edit_pending_complaints.php">Edit Pending Complaints</a></li> <!-- New Link -->
            <li><a href="?action=logout" class="logout">Logout</a></li>
        </ul>
    </div>
</body>

</html>