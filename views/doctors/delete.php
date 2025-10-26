<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../includes/auth.php';

// Check if user has permission to delete doctors
if (!isset($_SESSION['user_type']) || !in_array($_SESSION['user_type'], ['superadmin', 'staff'])) {
    header('Location: /unauthorized.php');
    exit();
}

// Doctor deletion logic will go here
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Doctor - Medi-Care Health Information System</title>
</head>
<body>
    <h1>Delete Doctor</h1>
    <!-- Doctor deletion confirmation will go here -->
    <a href="../doctors/">Back to Doctors</a>
</body>
</html>
