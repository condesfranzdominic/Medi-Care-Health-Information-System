<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../includes/auth.php';

// Check if user has permission to create schedules
if (!isset($_SESSION['user_type']) || !in_array($_SESSION['user_type'], ['superadmin', 'staff', 'doctor'])) {
    header('Location: ../../../unauthorized.php');
    exit();
}

// Schedule creation logic will go here
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Schedule - Medi-Care Health Information System</title>
</head>
<body>
    <h1>Create New Schedule</h1>
    <!-- Schedule creation form will go here -->
    <a href="../schedules/">Back to Schedules</a>
</body>
</html>
