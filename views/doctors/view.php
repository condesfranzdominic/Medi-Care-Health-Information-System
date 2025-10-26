<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../includes/auth.php';

// Check if user has permission to view doctors
if (!isset($_SESSION['user_type'])) {
    header('Location: /unauthorized.php');
    exit();
}

// Doctor view logic will go here
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Doctor - Medi-Care Health Information System></title> 
</head>
<body>
    <h1>View Doctor</h1>
    <!-- Doctor details will go here -->
    <a href="../doctors/">Back to Doctors</a>
</body>
</html>
