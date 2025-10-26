<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/auth.php';

// Check if user is doctor
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'doctor') {
    header('Location: ../unauthorized.php');
    exit();
}

// Doctor dashboard content will go here
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Dashboard - <?php echo APP_NAME; ?></title>
</head>
<body>
    <h1>Doctor Dashboard</h1>
    <p>Welcome, Dr. <?php echo $_SESSION['user_email']; ?>!</p>
    <a href="../logout.php">Logout</a>
</body>
</html>
