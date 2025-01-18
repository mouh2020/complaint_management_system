<?php
session_start();
include('../config/database.php'); // Include the database connection

// Check if the user is logged in
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header('Location: ./user/login.php'); // Redirect to login page if not logged in
    exit;
}

$message = ''; // Message to display feedback to the user

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $imageData = null; // Default value for imageData

    if (isset($_FILES['complainPic']) && $_FILES['complainPic']['error'] === UPLOAD_ERR_OK) {
        $picture = $_FILES['complainPic']['tmp_name'];
        $imageData = file_get_contents($picture); // Read file contents as binary data
    }

    if (!empty($title) && !empty($description)) {
        // Insert the complaint into the database
        $stmt = $conn->prepare("INSERT INTO Complaint (userId, title, description, status, dateSubmitted, imageData) VALUES (?, ?, ?, 'Pending', NOW(), ?)");
        $stmt->bind_param('isss', $_SESSION['userId'], $title, $description, $imageData);

        if ($stmt->execute()) {
            $message = "Complaint submitted successfully.";
        } else {
            $message = "Failed to submit complaint. Please try again.";
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

    <!-- Display feedback message -->
    <?php if (!empty($message)): ?>
    <div class="alert <?= strpos(htmlspecialchars($message), 'successfully') !== false ? 'success' : 'error' ?>">
        <?= htmlspecialchars($message) ?>
    </div>
    <?php endif; ?>

    <!-- Complaint Submission Form -->
    <form method="POST" enctype="multipart/form-data">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" placeholder="Enter the title" required>

        <label for="description">Description:</label>
        <textarea id="description" name="description" placeholder="Enter the description" required></textarea>
        <div class="complainPicContainer">
            <label for="complainPic">Picture for the situation (optional):</label>
            <input type="file" id="complainPic" name="complainPic" accept="image/*">
        </div>
        <button type="submit">Submit Complaint</button>
    </form>

    <p><a href="dashboard.php">Back to Dashboard</a></p>
</body>

</html>