<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../includes/auth.php';

// Check if user has permission to delete medical records
if (!isset($_SESSION['user_type']) || !in_array($_SESSION['user_type'], ['superadmin', 'staff', 'doctor'])) {
    header('Location: ../../../unauthorized.php');
    exit();
}

// Medical record deletion logic will go here
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Medical Record - <?php echo APP_NAME; ?></title>
</head>
<body>
    <h1>Delete Medical Record</h1>
    <!-- Medical record deletion confirmation will go here -->
    <a href="../medical_records/">Back to Medical Records</a>
</body>
</html>
