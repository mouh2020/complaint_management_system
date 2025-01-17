<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo htmlspecialchars($_POST['role']);

    if (isset($_POST['role'])) {
        $role = $_POST['role'];

        if ($role === 'admin') {
            header('Location: ./admin/login.php');
            exit;
        } elseif ($role === 'client') {
            header('Location: ./user/login.php');
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/styles/home.css">
    <title>Role Selection</title>
</head>

<body>
    <div class="role-selection-card">
        <h1>Complaints System</h1>
        <p>Please select your role:</p>
        <form method="POST">
            <button type="submit" name="role" value="client">I am a Client</button>
            <button type="submit" name="role" value="admin">I am an Admin</button>
        </form>
    </div>
</body>

</html>