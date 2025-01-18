<?php
session_start();
include('../config/database.php');

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!empty($username) && !empty($password)) {
        $stmt = $conn->prepare("SELECT * FROM Admin WHERE username = ? AND password = ?");
        $stmt->bind_param('ss', $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $_SESSION['admin_logged_in'] = true;
            $admin = $result->fetch_assoc();
            $_SESSION['adminId'] = $admin['adminId'];
            $_SESSION['admin_username'] = $admin['username'];
            header('Location: ./dashboard.php');
            exit;
        } else {
            $error_message = 'Invalid username or password.';
        }
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
    <link rel="stylesheet" href="../assets/styles/admin/login.css">
    <title>Admin Login</title>
</head>

<body>
    <div class="login-card">
        <h1>Admin Login</h1>
        <?php if (!empty($error_message)): ?>
        <div class="error-message"><?= htmlspecialchars($error_message) ?></div>
        <?php endif; ?>
        <form method="POST">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Login</button>
        </form>
    </div>
</body>

</html>