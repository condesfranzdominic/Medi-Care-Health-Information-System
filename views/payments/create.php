<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../includes/auth.php';

// Check if user has permission to create payments
if (!isset($_SESSION['user_type']) || !in_array($_SESSION['user_type'], ['superadmin', 'staff'])) {
    header('Location: ../../../unauthorized.php');
    exit();
}

// Payment creation logic will go here
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Payment - Medi-Care Health Information System</title>
</head>
<body>
    <h1>Create New Payment</h1>
    <!-- Payment creation form will go here -->
    <a href="../payments/">Back to Payments</a>
</body>
</html>
