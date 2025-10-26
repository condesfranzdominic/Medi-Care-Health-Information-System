<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../includes/auth.php';

// Check if user has permission to delete payments
if (!isset($_SESSION['user_type']) || !in_array($_SESSION['user_type'], ['superadmin', 'staff'])) {
    header('Location: ../../../unauthorized.php');
    exit();
}

// Payment deletion logic will go here
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Payment - <?php echo APP_NAME; ?></title>
</head>
<body>
    <h1>Delete Payment</h1>
    <!-- Payment deletion confirmation will go here -->
    <a href="../payments/">Back to Payments</a>
</body>
</html>
