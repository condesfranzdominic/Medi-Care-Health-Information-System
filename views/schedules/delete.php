<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../includes/auth.php';

// Check if user has permission to delete schedules
if (!isset($_SESSION['user_type']) || !in_array($_SESSION['user_type'], ['superadmin', 'staff', 'doctor'])) {
    header('Location: ../../../unauthorized.php');
    exit();
}

// Schedule deletion logic will go here
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Schedule - <?php echo APP_NAME; ?></title>
</head>
<body>
    <h1>Delete Schedule</h1>
    <!-- Schedule deletion confirmation will go here -->
    <a href="../schedules/">Back to Schedules</a>
</body>
</html>
