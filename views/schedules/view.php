<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../includes/auth.php';

// Check if user has permission to view schedules
if (!isset($_SESSION['user_type'])) {
    header('Location: ../../../unauthorized.php');
    exit();
}

// Schedule view logic will go here
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Schedule - <?php echo APP_NAME; ?></title>
</head>
<body>
    <h1>View Schedule</h1>
    <!-- Schedule details will go here -->
    <a href="../schedules/">Back to Schedules</a>
</body>
</html>
