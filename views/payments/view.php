<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/auth.php';

// Check if user has permission to view payments
if (!isset($_SESSION['user_type'])) {
    header('Location: /unauthorized.php');
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Payment - Medi-Care Health Information System</title>
</head>
<body>
    <h1>View Payment</h1>
    <!-- Payment details will go here -->
    <a href="../payments/">Back to Payments</a>
</body>
</html>
