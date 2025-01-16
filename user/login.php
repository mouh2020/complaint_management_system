<?php
session_start();
include('../config/database.php'); // Include database connection file

$error_message = '';

// Check if the user is already logged in
if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    header('Location: /user/dashboard.php');
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get submitted username and password
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!empty($username) && !empty($password)) {
        // Prepare a SQL query to fetch user details
        $stmt = $conn->prepare("SELECT * FROM User WHERE username = ?");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if a matching record is found
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Verify the entered password against the hashed password in the database
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_logged_in'] = true;

                // Store user details in the session
                $_SESSION['userId'] = $user['userId'];
                $_SESSION['user_username'] = $user['username'];

                // Redirect to the user dashboard
                header('Location: /user/dashboard.php');
                exit;
            } else {
                $error_message = 'Invalid username or password.';
            }
        } else {
            $error_message = 'Invalid username or password.';
        }

        $stmt->close();
    } else {
        $error_message = 'Both username and password are required.';
    }
}
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/styles/user/login.css">
    <title>User Login</title>
</head>
<body>
    <div class="login-card">
        <h1>User Login</h1>
        
        <?php if (!empty($error_message)): ?>
            <div class="error-message"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" placeholder="Enter your username" required>
            
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>
            
            <button type="submit">Login</button>
        </form>
        
        <div class="signup-link">
            Don't have an account? <a href="signup.php">Sign up here</a>.
        </div>
    </div>
</body>
</html>
