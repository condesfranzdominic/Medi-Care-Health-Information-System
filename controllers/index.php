<?php
// Check if user is already logged in
session_start();
if (isset($_SESSION['user_id'])) {
    // Redirect based on role
    require_once __DIR__ . '/../classes/Auth.php';
    $auth = new Auth();
    
    if ($auth->isSuperAdmin()) {
        header('Location: /superadmin/dashboard');
    } elseif ($auth->isStaff()) {
        header('Location: /staff/dashboard');
    } elseif ($auth->isDoctor()) {
        header('Location: /doctor/appointments/today');
    } elseif ($auth->isPatient()) {
        header('Location: /patient/appointments');
    }
    exit;
}

require_once __DIR__ . '/../views/landing.php';