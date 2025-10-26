<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../includes/auth.php';

// Check if user has permission to edit medical records
if (!isset($_SESSION['user_type']) || !in_array($_SESSION['user_type'], ['superadmin', 'staff', 'doctor'])) {
    header('Location: ../../../unauthorized.php');
    exit();
}

// Medical record edit logic will go here
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Medical Record - Medi-Care Health Information System</title>
</head>
<body>
    <h1>Edit Medical Record</h1>
    <!-- Medical record edit form will go here -->
    <a href="../medical_records/">Back to Medical Records</a>
</body>
</html>
