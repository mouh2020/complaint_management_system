<?php
session_start();
include('../config/database.php');

// Check if the user is logged in
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header('Location: ./login.php');
    exit;
}

$userId = $_SESSION['userId'];

// Fetch pending complaints
$query = "SELECT complaintId, title, description, imageData FROM Complaint WHERE userId = ? AND status = 'Pending'";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();

// Handle form submission
if (isset($_POST['update_complaint'])) {
    $complaintId = $_POST['complaintId'];
    $title = htmlspecialchars($_POST['title']);
    $description = htmlspecialchars($_POST['description']);

    // Handle image upload
    $imageData = null;
    if (isset($_FILES['image']['tmp_name']) && $_FILES['image']['tmp_name'] != '') {
        $imageInfo = getimagesize($_FILES['image']['tmp_name']);
        if ($imageInfo !== false) {
            $imageData = file_get_contents($_FILES['image']['tmp_name']);
        } else {
            die("Invalid image file.");
        }
    }

    // If no new image, keep the old one
    if ($imageData === null) {
        $stmt = $conn->prepare("SELECT imageData FROM Complaint WHERE complaintId = ?");
        $stmt->bind_param('i', $complaintId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $imageData = $row['imageData'];
        }
    }

    // Update the complaint
    $updateQuery = "UPDATE Complaint SET title = ?, description = ?, imageData = ? WHERE complaintId = ? AND userId = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param('sssii', $title, $description, $imageData, $complaintId, $userId);

    if ($updateStmt->execute()) {
        header('Location: view_complaints.php');
        exit;
    } else {
        die("Error updating complaint: " . $updateStmt->error);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/styles/user/edit_pending_complaints.css">
    <title>Edit Pending Complaints</title>
</head>

<body>
    <h1>Edit Pending Complaints</h1>

    <?php if ($result->num_rows > 0): ?>
    <form method="POST" enctype="multipart/form-data">
        <?php while ($row = $result->fetch_assoc()): ?>
        <div>
            <h3>Complaint ID: <?= htmlspecialchars($row['complaintId']) ?></h3>
            <label>Title:</label>
            <input type="text" name="title" value="<?= htmlspecialchars($row['title']) ?>" required>
            <br>
            <label>Description:</label>
            <textarea name="description" required><?= htmlspecialchars($row['description']) ?></textarea>
            <br>
            <label>Upload New Image (Optional):</label>
            <input type="file" name="image">
            <br>
            <?php if ($row['imageData']): ?>
            <p>Current Image:</p>
            <img src="data:image/jpeg;base64,<?= base64_encode($row['imageData']) ?>" alt="Complaint Image"
                style="max-width: 200px;">
            <?php endif; ?>
            <input type="hidden" name="complaintId" value="<?= htmlspecialchars($row['complaintId']) ?>">
            <button type="submit" name="update_complaint">Update Complaint</button>
        </div>
        <hr>
        <?php endwhile; ?>
    </form>
    <?php else: ?>
    <p>You have no pending complaints to edit.</p>
    <?php endif; ?>

    <p><a href="dashboard.php">Back to dashboard</a></p>
</body>

</html>