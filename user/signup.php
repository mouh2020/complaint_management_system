<?php
include('../config/database.php'); // Include the database connection
session_start();

$error_message = '';
if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    header('Location: /user/dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize user input
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Validate input
    if (!empty($username) && !empty($password)) {
        // Check if the username already exists
        $stmt = $conn->prepare("SELECT * FROM User WHERE username = ?");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error_message = 'Username already exists. Please try a different username.';
        } else {
            // Hash the password for security
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // Insert the new user into the database
            $stmt = $conn->prepare("INSERT INTO User (username, password) VALUES (?, ?)");
            $stmt->bind_param('ss', $username, $hashed_password);

            if ($stmt->execute()) {
                // Redirect to dashboard.php upon successful signup
                $_SESSION['user_logged_in'] = true;
                $_SESSION['username'] = $username;
                header("Location: /user/dashboard.php");
                exit();
            } else {
                $error_message = 'Error occurred during registration. Please try again.';
            }

            $stmt->close();
        }
    } else {
        $error_message = 'Both fields are required.';
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/styles/user/signup.css">
    <title>Sign Up</title>
</head>
<body>
    <div class="signup-card">
        <h1>Sign Up</h1>

        <?php if (!empty($error_message)): ?>
            <div class="error-message"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>

        <form method="post" action="signup.php">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" placeholder="Enter your username" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>

            <button type="submit">Sign Up</button>
        </form>

        <div class="login-link">
            Already have an account? <a href="login.php">Log in here</a>.
        </div>
    </div>
</body>
</html>
