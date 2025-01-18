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
        $imageData = file_get_contents($_FILES['complainPic']['tmp_name']);
    }

    if (!empty($title) && !empty($description)) {
        $stmt = $conn->prepare("INSERT INTO Complaint (userId, title, description, status, dateSubmitted, imageData) VALUES (?, ?, ?, 'Pending', NOW(), ?)");
        $stmt->bind_param('isss', $_SESSION['userId'], $title, $description, $imageData);

        if ($stmt->execute()) {
            $message = "Complaint submitted successfully.";
        } else {
            $message = "Failed to submit complaint.";
        }

        $stmt->close();
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
    <div class="alert"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <form method="POST" enctype="multipart/form-data">
        <label>Title:</label>
        <input type="text" name="title" required>
        <br>
        <label>Description:</label>
        <textarea name="description" required></textarea>
        <br>
        <label>Picture (Optional):</label>
        <input type="file" name="complainPic">
        <br>
        <button type="submit">Submit Complaint</button>
    </form>
    <p><a href="dashboard.php">Back to Dashboard</a></p>
</body>

</html>