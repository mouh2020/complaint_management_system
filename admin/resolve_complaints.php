<?php
session_start();
include('../config/database.php'); // Include database connection file

// Check if the user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ./admin/login.php'); // Redirect to login page if not logged in
    exit;
}

// Handle resolving a complaint
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['resolve_complaint_id'])) {
        $complaint_id = intval($_POST['resolve_complaint_id']); // Sanitize the complaint ID
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

    // Handle deleting a complaint
    if (isset($_POST['delete_complaint_id'])) {
        $complaint_id = intval($_POST['delete_complaint_id']); // Sanitize the complaint ID

        // Delete the complaint
        $stmt = $conn->prepare("DELETE FROM Complaint WHERE complaintId = ?");
        $stmt->bind_param('i', $complaint_id);

        if ($stmt->execute()) {
            $message = "Complaint #$complaint_id has been deleted.";
            $is_error = false; // Indicate a success message
        } else {
            $message = "Failed to delete complaint #$complaint_id. Please try again.";
            $is_error = true; // Indicate an error message
        }

        $stmt->close();
    }
}

// Fetch all pending complaints with image data
$query = "
    SELECT 
        Complaint.complaintId, 
        Complaint.title, 
        Complaint.description, 
        Complaint.status, 
        Complaint.imageData
    FROM 
        Complaint
    WHERE 
        status = 'Pending'
";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/styles/admin/resolve_complaints.css">
    <title>Resolve or Delete Complaints</title>
    <style>
    .table-container img {
        max-width: 100px;
        max-height: 100px;
        object-fit: cover;
    }
    </style>
</head>

<body>
    <h1>Resolve or Delete Complaints</h1>

    <?php if (isset($message)): ?>
    <div class="alert-box <?= isset($is_error) && $is_error ? 'error' : '' ?>">
        <?= htmlspecialchars($message) ?>
    </div>
    <?php endif; ?>

    <h2>Pending Complaints</h2>

    <?php if ($result->num_rows > 0): ?>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Picture</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['complaintId']) ?></td>
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
                    <td>
                        <!-- Action Buttons Container -->
                        <div class="action-buttons">
                            <!-- Resolve Complaint Form -->
                            <form method="POST">
                                <input type="hidden" name="resolve_complaint_id" value="<?= $row['complaintId'] ?>">
                                <button type="submit" class="resolve-btn">Mark as Resolved</button>
                            </form>
                            <!-- Delete Complaint Form -->
                            <form method="POST">
                                <input type="hidden" name="delete_complaint_id" value="<?= $row['complaintId'] ?>">
                                <button type="submit" class="delete-btn"
                                    onclick="return confirm('Are you sure you want to delete this complaint?');">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <p>No pending complaints found.</p>
    <?php endif; ?>

    <p><a href="dashboard.php">Back to Dashboard</a></p>
</body>

</html>