<?php
session_start();
include('../config/database.php');

if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header('Location: ./login.php');
    exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $imageData = null;

    if (isset($_FILES['complainPic']) && $_FILES['complainPic']['error'] === UPLOAD_ERR_OK) {
        $imageInfo = getimagesize($_FILES['complainPic']['tmp_name']);
        if ($imageInfo !== false) {
            $imageData = file_get_contents($_FILES['complainPic']['tmp_name']);
        } else {
            $message = "Invalid image file.";
        }
    }

    if (!empty($title) && !empty($description)) {
        // Ensure userId is set in the session
        if (isset($_SESSION['userId'])) {
            $userId = $_SESSION['userId'];
            $stmt = $conn->prepare("INSERT INTO Complaint (userId, title, description, status, dateSubmitted, imageData) VALUES (?, ?, ?, 'Pending', NOW(), ?)");
            $stmt->bind_param('isss', $userId, $title, $description, $imageData);

            if ($stmt->execute()) {
                // Set success message
                $message = "Your complaint has been submitted successfully!";
            } else {
                $message = "Failed to submit complaint.";
            }

            $stmt->close();
        } else {
            $message = "User ID is missing. Please log in again.";
        }
    } else {
        $message = "Both title and description are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/styles/user/submit_complaint.css">
    <title>Submit Complaint</title>
</head>

<body>
    <h1>Submit a Complaint</h1>
    <?php if (!empty($message)): ?>
    <div class="alert <?= strpos($message, 'successfully') !== false ? 'success' : 'error' ?>">
        <?= htmlspecialchars($message) ?>
    </div>
    <?php endif; ?>
    <form method="POST" enctype="multipart/form-data">
        <label>Title:</label>
        <input type="text" name="title" required>
        <br>
        <label>Description:</label>
        <textarea name="description" required></textarea>
        <br>
        <div class="upload-section">
            <label class="upload-label" for="complainPic">Choose a Picture (Optional)</label>
            <input type="file" name="complainPic" id="complainPic">
        </div>
        <br>
        <button type="submit">Submit Complaint</button>
    </form>
    <p><a href="dashboard.php">Back to Dashboard</a></p>
</body>

</html>