<?php
session_start();
include('../config/database.php');

if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header('Location: ./login.php');
    exit;
}

$userId = $_SESSION['userId'];

$query = "SELECT complaintId, title, description, status, dateSubmitted, imageData FROM Complaint WHERE userId = ?";
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
    <?php if ($result->num_rows > 0): ?>
    <table>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Description</th>
            <th>Status</th>
            <th>Date Submitted</th>
            <th>Picture</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['complaintId']) ?></td>
            <td><?= htmlspecialchars($row['title']) ?></td>
            <td><?= htmlspecialchars($row['description']) ?></td>
            <td><?= htmlspecialchars($row['status']) ?></td>
            <td><?= htmlspecialchars($row['dateSubmitted']) ?></td>
            <td class="tableimg">
                <?php if (!empty($row['imageData'])): ?>
                <img src="data:image/jpeg;base64,<?= base64_encode($row['imageData']) ?>" alt="Complaint Image">
                <?php else: ?>
                No Image
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
    <?php else: ?>
    <p>You have not submitted any complaints yet.</p>
    <?php endif; ?>
    <p><a href="dashboard.php">Back to Dashboard</a></p>
</body>

</html>