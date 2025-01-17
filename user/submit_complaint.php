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

    if (!empty($title) && !empty($description)) {
        // Insert the complaint into the database
        $stmt = $conn->prepare("INSERT INTO Complaint (title, description, status, dateSubmitted, userId) VALUES (?, ?, 'Pending', NOW(), ?)");
        $stmt->bind_param('ssi', $title, $description, $_SESSION['userId']);

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
    <style></style>
</head>

<body>
    <h1>Submit a Complaint</h1>

    <!-- Display feedback message -->
    <?php if (!empty($message)): ?>
    <div class="alert <?= strpos(htmlspecialchars($message), 'successfully') !== false ? '' : 'error' ?>">
        <?= htmlspecialchars($message) ?>
    </div>
    <?php endif; ?>

    <!-- Complaint Submission Form -->
    <form method="POST">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" placeholder="Enter the title" required aria-label="Complaint Title">

        <label for="description">Description:</label>
        <textarea id="description" name="description" placeholder="Enter the description" required
            aria-label="Complaint Description"></textarea>

        <button type="submit" aria-label="Submit Complaint">Submit Complaint</button>
    </form>

    <p><a href="dashboard.php" aria-label="Back to Dashboard">Back to Dashboard</a></p>
</body>

</html>