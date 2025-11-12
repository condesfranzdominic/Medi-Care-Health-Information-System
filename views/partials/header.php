<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medi-Care Health Portal</title>
    <link rel="stylesheet" href="/public/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body style="margin: 0; padding: 0;">

<div class="app-container">
<?php 
// Only show navigation if user is logged in
if (isset($_SESSION['user_id'])): 
    // Get user name for display
    $userName = 'User';
    $userInitial = 'U';
    
    if (isset($_SESSION['user_email'])) {
        $userName = $_SESSION['user_email'];
        $userInitial = strtoupper(substr($_SESSION['user_email'], 0, 1));
    }
    
    // Try to get full name from session
    if (isset($_SESSION['pat_first_name']) && isset($_SESSION['pat_last_name'])) {
        $userName = $_SESSION['pat_first_name'] . ' ' . $_SESSION['pat_last_name'];
        $userInitial = strtoupper(substr($_SESSION['pat_first_name'], 0, 1));
    } elseif (isset($_SESSION['doc_first_name']) && isset($_SESSION['doc_last_name'])) {
        $userName = $_SESSION['doc_first_name'] . ' ' . $_SESSION['doc_last_name'];
        $userInitial = strtoupper(substr($_SESSION['doc_first_name'], 0, 1));
    }
    
    include __DIR__ . '/sidebar.php';
?>

<!-- Top Navigation Bar -->
<nav class="top-nav">
    <div class="top-nav-left">
        <a href="/<?= $role ?>/dashboard" class="logo">
            <div class="logo-icon"><i class="fas fa-heartbeat"></i></div>
            <span>Medi-Care</span>
        </a>
        <ul class="nav-links">
            <li><a href="/<?= $role ?>/dashboard">Dashboard</a></li>
            <?php if ($role === 'patient'): ?>
                <li><a href="/patient/appointments">Appointments</a></li>
                <li><a href="/patient/appointments/create">Book Appointment</a></li>
            <?php elseif ($role === 'doctor'): ?>
                <li><a href="/doctor/appointments/today">Appointments</a></li>
                <li><a href="/doctor/schedules">Schedules</a></li>
            <?php elseif ($role === 'staff'): ?>
                <li><a href="/staff/services">Services</a></li>
                <li><a href="/staff/payments">Payments</a></li>
            <?php endif; ?>
        </ul>
    </div>
    <div class="top-nav-right">
        <div class="nav-icon" title="Search"><i class="fas fa-search"></i></div>
        <div class="nav-icon" title="Notifications"><i class="fas fa-bell"></i></div>
        <div class="nav-icon" title="Call"><i class="fas fa-phone"></i></div>
        <div class="user-menu">
            <div class="user-avatar-nav"><?= $userInitial ?></div>
            <i class="fas fa-chevron-down" style="font-size: 0.75rem; color: #6b7280;"></i>
        </div>
    </div>
</nav>

<div class="main-content">
    <div class="content-container">
<?php else: ?>
    <!-- No navigation for public pages -->
<?php endif; ?>
