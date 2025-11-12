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

// Icon mapping function
function getIcon($emoji) {
    $iconMap = [
        '📊' => 'fas fa-chart-line',
        '👥' => 'fas fa-users',
        '🏥' => 'fas fa-hospital',
        '👨‍⚕️' => 'fas fa-user-md',
        '👔' => 'fas fa-user-tie',
        '🎓' => 'fas fa-graduation-cap',
        '🗓️' => 'fas fa-calendar-alt',
        '📋' => 'fas fa-clipboard-list',
        '🔬' => 'fas fa-flask',
        '📅' => 'fas fa-calendar-check',
        '📄' => 'fas fa-file-medical',
        '💳' => 'fas fa-credit-card',
        '💰' => 'fas fa-coins',
        '💵' => 'fas fa-money-bill-wave',
        '⏰' => 'fas fa-clock',
        '👤' => 'fas fa-user',
        '📜' => 'fas fa-scroll',
        '➕' => 'fas fa-plus-circle',
    ];
    return $iconMap[$emoji] ?? 'fas fa-circle';
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
    
    // DOCTOR - Manages own appointments, schedules, and medical records; can manage all doctors and schedules
    'doctor' => [
        ['icon' => '📊', 'label' => 'Dashboard', 'url' => '/doctor/dashboard'],
        [
            'icon' => '📅', 
            'label' => 'Appointments', 
            'submenu' => [
                ['icon' => '📊', 'label' => 'Today\'s Appointments', 'url' => '/doctor/appointments/today'],
                ['icon' => '📜', 'label' => 'Previous Appointments', 'url' => '/doctor/appointments/previous'],
                ['icon' => '🗓️', 'label' => 'Future Appointments', 'url' => '/doctor/appointments/future'],
            ]
        ],
        [
            'icon' => '⏰', 
            'label' => 'Schedules', 
            'submenu' => [
                ['icon' => '👤', 'label' => 'My Schedules', 'url' => '/doctor/schedules'],
                ['icon' => '🗓️', 'label' => 'All Schedules', 'url' => '/doctor/schedules/manage'],
            ]
        ],
        ['icon' => '👨‍⚕️', 'label' => 'Doctors', 'url' => '/doctor/doctors'],
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

<div class="sidebar">
    <div class="sidebar-profile">
        <div class="profile-avatar"><?= $userInitial ?></div>
        <div class="profile-name"><?= htmlspecialchars($userName) ?></div>
    </div>
    
    <div class="sidebar-menu">
        <?php foreach ($currentMenu as $item): ?>
            <?php if (isset($item['submenu'])): ?>
                <!-- Menu item with submenu -->
                <?php 
                $hasActiveSubmenu = false;
                foreach ($item['submenu'] as $subitem) {
                    if (strpos($currentPath, $subitem['url']) !== false) {
                        $hasActiveSubmenu = true;
                        break;
                    }
                }
                ?>
                <div class="menu-item <?= $hasActiveSubmenu ? 'active' : '' ?>" onclick="toggleSubmenu(this)">
                    <span class="icon"><i class="<?= getIcon($item['icon']) ?>"></i></span>
                    <span><?= $item['label'] ?></span>
                    <span class="arrow"><i class="fas fa-chevron-down"></i></span>
                </div>
                <div class="sidebar-submenu" style="display: <?= $hasActiveSubmenu ? 'block' : 'none' ?>;">
                    <?php foreach ($item['submenu'] as $subitem): ?>
                        <?php 
                        $isActive = strpos($currentPath, $subitem['url']) !== false ? 'active' : '';
                        ?>
                        <a href="<?= $subitem['url'] ?>" class="menu-item <?= $isActive ?>" style="padding-left: 3.5rem;">
                            <span class="icon"><i class="<?= getIcon($subitem['icon']) ?>"></i></span>
                            <span><?= $subitem['label'] ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <!-- Regular menu item -->
                <?php 
                $isActive = strpos($currentPath, $item['url']) !== false ? 'active' : '';
                ?>
                <a href="<?= $item['url'] ?>" class="menu-item <?= $isActive ?>">
                    <span class="icon"><i class="<?= getIcon($item['icon']) ?>"></i></span>
                    <span><?= $item['label'] ?></span>
                </a>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
    
    <div style="padding: 1rem 1.5rem; border-top: 1px solid #e5e7eb; margin-top: auto;">
        <a href="/logout" class="btn btn-danger" style="width: 100%; justify-content: center;">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
    </div>
</div>

<script>
function toggleSubmenu(element) {
    const submenu = element.nextElementSibling;
    if (submenu && submenu.classList.contains('sidebar-submenu')) {
        const isOpen = submenu.style.display === 'block';
        submenu.style.display = isOpen ? 'none' : 'block';
        element.classList.toggle('active', !isOpen);
    }
}

// Auto-open submenu if current page is in it
document.addEventListener('DOMContentLoaded', function() {
    const activeSubmenuLinks = document.querySelectorAll('.sidebar-submenu a.active');
    activeSubmenuLinks.forEach(link => {
        const submenu = link.closest('.sidebar-submenu');
        if (submenu) {
            submenu.style.display = 'block';
            const toggle = submenu.previousElementSibling;
            if (toggle) {
                toggle.classList.add('active');
            }
        }
    });
});
</script>
