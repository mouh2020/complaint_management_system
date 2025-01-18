<?php
session_start();
include('../config/database.php'); // Include database connection

// Check if the user is logged in
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header('Location: ./login.php'); // Redirect to login page if not logged in
    exit;
}

// Get the user ID from the session
$userId = $_SESSION['userId'];

// Fetch only pending complaints for this user
$query = "SELECT complaintId, title, description, imageData FROM Complaint WHERE userId = ? AND status = 'Pending'";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();

// Handle the editing of a complaint
if (isset($_POST['update_complaint'])) {
    $complaintId = $_POST['complaintId'];
    $title = htmlspecialchars($_POST['title']);
    $description = htmlspecialchars($_POST['description']);

    // Handle the image upload
    $imageData = null;
    if (isset($_FILES['image']['tmp_name']) && $_FILES['image']['tmp_name'] != '') {
        // Check if the uploaded file is an image
        $imageInfo = getimagesize($_FILES['image']['tmp_name']);
        if ($imageInfo !== false) {
            $imageData = file_get_contents($_FILES['image']['tmp_name']);
        } else {
            die("Invalid image file.");
        }
    }

    // If imageData is null, keep the old image
    if ($imageData === null) {
        $stmt = $conn->prepare("SELECT imageData FROM Complaint WHERE complaintId = ?");
        $stmt->bind_param('i', $complaintId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $imageData = $row['imageData']; // Keep the old image if no new one is uploaded
        }
    }

    // Prepare the update query
    $updateQuery = "UPDATE Complaint SET title = ?, description = ?, imageData = ? WHERE complaintId = ? AND userId = ?";
    $updateStmt = $conn->prepare($updateQuery);

    // Bind parameters
    $updateStmt->bind_param('sssii', $title, $description, $imageData, $complaintId, $userId);

    // Execute the update statement
    if ($updateStmt->execute()) {
        // Redirect to the view complaints page
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
    <title>Edit Pending Complaints</title>
    <link rel="stylesheet" href="../assets/styles/user/edit_pending_complaints.css">
</head>

<body>
    <h1>Edit Pending Complaints</h1>

    <?php if ($result->num_rows > 0): ?>
    <form action="edit_pending_complaints.php" method="POST" enctype="multipart/form-data">
        <?php while ($row = $result->fetch_assoc()): ?>
        <div class="complaint">
            <h3>Complaint ID: <?= htmlspecialchars($row['complaintId']) ?></h3>

            <label for="title">Title:</label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($row['title']) ?>" required>

            <label for="description">Description:</label>
            <textarea id="description" name="description"
                required><?= htmlspecialchars($row['description']) ?></textarea>

            <label for="image">Upload New Image (Optional):</label>
            <input type="file" id="image" name="image">

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

    <p><a href="view_complaints.php">Back to My Complaints</a></p>
</body>

</html>