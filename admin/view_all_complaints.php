<?php
include('../config/database.php'); // Include database connection
session_start();

// Check if the user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ./admin/login.php'); // Redirect to login if not logged in
    exit;
}

// Fetch all complaints from the Complaint table
$query = "
    SELECT 
        Complaint.complaintId, 
        User.username, 
        Complaint.title, 
        Complaint.description, 
        Complaint.status, 
        Complaint.imageData 
    FROM 
        Complaint
    INNER JOIN 
        User 
    ON 
        Complaint.userId = User.userId
";
$result = $conn->query($query);

// Check for errors in the query
if ($conn->error) {
    die("Database query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/styles/admin/view_all_complaints.css">
    <title>View All Complaints</title>
    <style>
    .table-container img {
        max-width: 100px;
        max-height: 100px;
        object-fit: cover;
    }
    </style>
</head>

<body>
    <h1>All Complaints</h1>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Picture</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['complaintId']) ?></td>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td><?= htmlspecialchars($row['title']) ?></td>
                    <td><?= htmlspecialchars($row['description']) ?></td>
                    <td>
                        <?php if ($row['status'] === 'Pending'): ?>
                        <span class="badge pending">Pending</span>
                        <?php elseif ($row['status'] === 'Resolved'): ?>
                        <span class="badge resolved">Resolved</span>
                        <?php else: ?>
                        <span class="badge"><?= htmlspecialchars($row['status']) ?></span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if (!empty($row['imageData'])): ?>
                        <img src="data:image/jpeg;base64,<?= base64_encode($row['imageData']) ?>" alt="Complaint Image">
                        <?php else: ?>
                        No Image
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
                <?php else: ?>
                <tr>
                    <td colspan="6">No complaints found.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <a href="dashboard.php">Back to Dashboard</a>
</body>

</html>