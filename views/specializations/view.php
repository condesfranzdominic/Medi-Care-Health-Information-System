<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../includes/auth.php';

// Check if user has permission to view specializations
if (!isset($_SESSION['user_type'])) {
    header('Location: ../../../unauthorized.php');
    exit();
}

// Specialization view logic will go here
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Specializations - Medi-care Healthcare Information System</title>
</head>
<body>
    <h1>View Specializations</h1>
    <!-- Specialization details will go here -->
    <a href="../specializations/">Back to Specializations</a>
</body>
</html>
