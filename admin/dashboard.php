<?php
session_start();
// Check if the user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // Redirect to login page if not logged in
    header('Location: ./admin/login.php');
    exit;
}

// Logout handler
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy(); // Destroy the session
    header('Location: ../'); // Redirect to home page
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