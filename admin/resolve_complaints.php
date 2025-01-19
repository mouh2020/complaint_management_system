<?php
session_start();
include('../config/database.php'); // Include database connection file

// Check if the user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ./login.php'); // Redirect to login page if not logged in
    exit;
}

// Handle resolving a complaint
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['complaint_id'])) {
    $complaint_id = intval($_POST['complaint_id']); // Sanitize the complaint ID
    $resolved_date = date('Y-m-d H:i:s'); // Get the current date and time in MySQL DATETIME format

    // Update the status to 'Resolved' and set the resolvedDate
    $stmt = $conn->prepare("UPDATE Complaint SET status = 'Resolved', resolvedDate = ? WHERE complaintId = ?");
    $stmt->bind_param('si', $resolved_date, $complaint_id);

    if ($stmt->execute()) {
        $message = "Complaint #$complaint_id has been resolved.";
        $is_error = false; // Indicate a success message
    } else {
        $message = "Failed to resolve complaint #$complaint_id. Please try again.";
        $is_error = true; // Indicate an error message
    }

    $stmt->close();
}

// Fetch all pending complaints
$query = "SELECT complaintId, title, description FROM Complaint WHERE status = 'Pending'";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/styles/admin/resolve_complaints.css">
    <title>Resolve Complaints</title>
</head>

<body>
    <h1>Resolve Complaints</h1>

    <?php if (isset($message)): ?>
    <div class="alert-box <?= isset($is_error) && $is_error ? 'error' : '' ?>">
        <?= htmlspecialchars($message) ?>
    </div>
    <?php endif; ?>

    <h2>Pending Complaints</h2>

    <?php if ($result->num_rows > 0): ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Description</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['complaintId']) ?></td>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td><?= htmlspecialchars($row['description']) ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="complaint_id" value="<?= $row['complaintId'] ?>">
                        <button type="submit">Mark as Resolved</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <?php else: ?>
    <p>No pending complaints found.</p>
    <?php endif; ?>

    <p><a href="dashboard.php">Back to Dashboard</a></p>
</body>

</html>