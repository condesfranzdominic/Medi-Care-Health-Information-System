<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../includes/auth.php';

// Check if user has permission to view statuses
if (!isset($_SESSION['user_type'])) {
    header('Location: ../../../unauthorized.php');
    exit();
}

// Status view logic will go here
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Statuses - <?php echo APP_NAME; ?></title>
</head>
<body>
    <h1>View Statuses</h1>
    <!-- Status details will go here -->
    <a href="../statuses/">Back to Statuses</a>
</body>
</html>
