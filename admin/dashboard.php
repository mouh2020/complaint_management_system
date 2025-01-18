<?php
session_start();
include('../config/database.php');

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ./login.php');
    exit;
}

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header('Location: ../index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/styles/admin/dashboard.css">
    <title>Admin Dashboard</title>
</head>

<body>
    <div class="dashboard-container">
        <h1>Welcome to the Admin Dashboard</h1>
        <ul>
            <li><a href="view_all_complaints.php">View All Complaints</a></li>
            <li><a href="resolve_complaints.php">Resolve Complaints</a></li>
            <li><a href="?action=logout" class="logout">Logout</a></li>
        </ul>
    </div>
</body>

</html>