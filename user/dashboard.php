<?php
session_start();
include('../config/database.php');

if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header('Location: ./login.php');
    exit;
}

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header('Location: ../index.php');
    exit;
}

$username = $_SESSION['user_username'] ?? 'User';
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
        <h1>Welcome <?= htmlspecialchars($username) ?> to your Dashboard</h1>
        <ul>
            <li><a href="submit_complaint.php">Submit Complaint</a></li>
            <li><a href="view_complaints.php">View Complaints</a></li>
            <li><a href="edit_pending_complaints.php">Edit Pending Complaints</a></li>
            <li><a href="?action=logout" class="logout">Logout</a></li>
        </ul>
    </div>
</body>

</html>