<?php
session_start();
include('../config/database.php'); // Include database connection file

// Check if the user is logged in
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header('Location: ./login.php'); // Redirect to login page if not logged in
    exit;
}

// Fetch complaints for the logged-in user
$userId = $_SESSION['userId'];
$query = "SELECT complaintId, title, description, status, dateSubmitted FROM Complaint WHERE userId = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/styles/user/view_complaints.css">
    <title>My Complaints</title>
</head>

<body>
    <h1>My Complaints</h1>

    <div class="table-container">
        <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Date Submitted</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['complaintId']) ?></td>
                    <td><?= htmlspecialchars($row['title']) ?></td>
                    <td><?= htmlspecialchars($row['description']) ?></td>
                    <td>
                        <span class="badge <?= strtolower($row['status']) ?>">
                            <?= htmlspecialchars($row['status']) ?>
                        </span>
                    </td>
                    <td><?= htmlspecialchars($row['dateSubmitted']) ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
        <p>You have not submitted any complaints yet.</p>
        <?php endif; ?>
    </div>

    <p><a href="dashboard.php" aria-label="Back to Dashboard">Back to Dashboard</a></p>
</body>

</html>