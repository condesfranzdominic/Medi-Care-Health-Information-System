<?php
// Determine user role
$role = 'guest';
if (isset($_SESSION['is_superadmin']) && $_SESSION['is_superadmin'] === true) {
    $role = 'superadmin';
} elseif (isset($_SESSION['staff_id']) && $_SESSION['staff_id'] !== null) {
    $role = 'staff';
} elseif (isset($_SESSION['doc_id']) && $_SESSION['doc_id'] !== null) {
    $role = 'doctor';
} elseif (isset($_SESSION['pat_id']) && $_SESSION['pat_id'] !== null) {
    $role = 'patient';
}

// Define menu items for each role based on privileges
$menus = [
    // SUPER ADMIN - Full control over all modules and records
    'superadmin' => [
        ['icon' => '📊', 'label' => 'Dashboard', 'url' => '/superadmin/dashboard'],
        ['icon' => '👥', 'label' => 'Users', 'url' => '/superadmin/users'],
        ['icon' => '🏥', 'label' => 'Patients', 'url' => '/superadmin/patients'],
        ['icon' => '👨‍⚕️', 'label' => 'Doctors', 'url' => '/superadmin/doctors'],
        ['icon' => '👔', 'label' => 'Staff', 'url' => '/superadmin/staff'],
        ['icon' => '🎓', 'label' => 'Specializations', 'url' => '/superadmin/specializations'],
        ['icon' => '🗓️', 'label' => 'Schedules', 'url' => '/superadmin/schedules'],
        ['icon' => '📋', 'label' => 'Statuses', 'url' => '/superadmin/statuses'],
        ['icon' => '🔬', 'label' => 'Services', 'url' => '/superadmin/services'],
        ['icon' => '📅', 'label' => 'Appointments', 'url' => '/superadmin/appointments'],
        ['icon' => '📄', 'label' => 'Medical Records', 'url' => '/superadmin/medical-records'],
        ['icon' => '💳', 'label' => 'Payment Methods', 'url' => '/superadmin/payment-methods'],
        ['icon' => '💰', 'label' => 'Payment Statuses', 'url' => '/superadmin/payment-statuses'],
        ['icon' => '💵', 'label' => 'Payments', 'url' => '/superadmin/payments'],
    ],
    
    // STAFF - Manages operational data and payments, view-only for medical records, no deletion rights
    'staff' => [
        ['icon' => '📊', 'label' => 'Dashboard', 'url' => '/staff/dashboard'],
        ['icon' => '👔', 'label' => 'Staff', 'url' => '/staff/staff'],
        ['icon' => '🎓', 'label' => 'Specializations', 'url' => '/staff/specializations'],
        ['icon' => '📋', 'label' => 'Statuses', 'url' => '/staff/statuses'],
        ['icon' => '🔬', 'label' => 'Services', 'url' => '/staff/services'],
        ['icon' => '💳', 'label' => 'Payment Methods', 'url' => '/staff/payment-methods'],
        ['icon' => '💰', 'label' => 'Payment Statuses', 'url' => '/staff/payment-statuses'],
        ['icon' => '💵', 'label' => 'Payments', 'url' => '/staff/payments'],
        ['icon' => '📄', 'label' => 'Medical Records (View)', 'url' => '/staff/medical-records'],
    ],
    
    // DOCTOR - Manages own appointments, schedules, and medical records; cannot access other doctors' data
    'doctor' => [
        ['icon' => '📊', 'label' => 'Today\'s Appointments', 'url' => '/doctor/appointments/today'],
        ['icon' => '📅', 'label' => 'Previous Appointments', 'url' => '/doctor/appointments/previous'],
        ['icon' => '🗓️', 'label' => 'Future Appointments', 'url' => '/doctor/appointments/future'],
        ['icon' => '⏰', 'label' => 'My Schedules', 'url' => '/doctor/schedules'],
        ['icon' => '📄', 'label' => 'Medical Records', 'url' => '/doctor/medical-records'],
        ['icon' => '👤', 'label' => 'My Profile', 'url' => '/doctor/profile'],
    ],
    
    // PATIENT - Manages own profile and appointments only; requires registration before booking
    'patient' => [
        ['icon' => '📊', 'label' => 'My Appointments', 'url' => '/patient/appointments'],
        ['icon' => '➕', 'label' => 'Book Appointment', 'url' => '/patient/appointments/create'],
        ['icon' => '👤', 'label' => 'My Profile', 'url' => '/patient/profile'],
    ],
];

$currentMenu = $menus[$role] ?? [];
$currentPath = $_SERVER['REQUEST_URI'];
?>

<style>
    .sidebar {
        position: fixed;
        left: 0;
        top: 0;
        width: 260px;
        height: 100vh;
        background: linear-gradient(180deg, #2c3e50 0%, #34495e 100%);
        color: white;
        overflow-y: auto;
        z-index: 1000;
        box-shadow: 2px 0 10px rgba(0,0,0,0.1);
    }
    
    .sidebar-header {
        padding: 25px 20px;
        background: rgba(0,0,0,0.2);
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }
    
    .sidebar-header h2 {
        margin: 0 0 5px 0;
        font-size: 20px;
        font-weight: 600;
    }
    
    .sidebar-header p {
        margin: 0;
        font-size: 13px;
        opacity: 0.8;
    }
    
    .sidebar-menu {
        padding: 20px 0;
    }
    
    .sidebar-menu a {
        display: flex;
        align-items: center;
        padding: 12px 20px;
        color: rgba(255,255,255,0.8);
        text-decoration: none;
        transition: all 0.3s;
        border-left: 3px solid transparent;
    }
    
    .sidebar-menu a:hover {
        background: rgba(255,255,255,0.1);
        color: white;
        border-left-color: #3498db;
    }
    
    .sidebar-menu a.active {
        background: rgba(52, 152, 219, 0.2);
        color: white;
        border-left-color: #3498db;
    }
    
    .sidebar-menu a .icon {
        font-size: 20px;
        margin-right: 12px;
        width: 24px;
        text-align: center;
    }
    
    .sidebar-footer {
        position: absolute;
        bottom: 0;
        width: 100%;
        padding: 20px;
        background: rgba(0,0,0,0.2);
        border-top: 1px solid rgba(255,255,255,0.1);
    }
    
    .logout-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        padding: 12px;
        background: #e74c3c;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        transition: background 0.3s;
        font-weight: 500;
    }
    
    .logout-btn:hover {
        background: #c0392b;
    }
    
    .main-content {
        margin-left: 260px;
        min-height: 100vh;
        background: #f5f6fa;
    }
    
    .top-bar {
        background: white;
        padding: 20px 30px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .user-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
    }
</style>

<div class="sidebar">
    <div class="sidebar-header">
        <h2>🏥 Medi-Care</h2>
        <p><?= ucfirst($role) ?> Portal</p>
    </div>
    
    <div class="sidebar-menu">
        <?php foreach ($currentMenu as $item): ?>
            <?php 
            $isActive = strpos($currentPath, $item['url']) !== false ? 'active' : '';
            ?>
            <a href="<?= $item['url'] ?>" class="<?= $isActive ?>">
                <span class="icon"><?= $item['icon'] ?></span>
                <span><?= $item['label'] ?></span>
            </a>
        <?php endforeach; ?>
    </div>
    
    <div class="sidebar-footer">
        <a href="/logout" class="logout-btn">
            <span style="margin-right: 8px;">🚪</span>
            Logout
        </a>
    </div>
</div>
