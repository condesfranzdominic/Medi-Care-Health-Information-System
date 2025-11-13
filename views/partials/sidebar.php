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

// Get user information
$userName = 'User';
$userInitial = 'U';
$userTitle = 'User';

if (isset($_SESSION['user_email'])) {
    $userName = $_SESSION['user_email'];
    $userInitial = strtoupper(substr($_SESSION['user_email'], 0, 1));
}

// Try to get full name from session
if (isset($_SESSION['pat_first_name']) && isset($_SESSION['pat_last_name'])) {
    $userName = $_SESSION['pat_first_name'] . ' ' . $_SESSION['pat_last_name'];
    $userInitial = strtoupper(substr($_SESSION['pat_first_name'], 0, 1));
    $userTitle = 'Patient';
} elseif (isset($_SESSION['doc_first_name']) && isset($_SESSION['doc_last_name'])) {
    $userName = $_SESSION['doc_first_name'] . ' ' . $_SESSION['doc_last_name'];
    $userInitial = strtoupper(substr($_SESSION['doc_first_name'], 0, 1));
    $userTitle = 'Doctor';
} elseif (isset($_SESSION['staff_first_name']) && isset($_SESSION['staff_last_name'])) {
    $userName = $_SESSION['staff_first_name'] . ' ' . $_SESSION['staff_last_name'];
    $userInitial = strtoupper(substr($_SESSION['staff_first_name'], 0, 1));
    $userTitle = 'Staff';
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
        '🏠' => 'fas fa-home',
        '📖' => 'fas fa-book',
        '🔔' => 'fas fa-bell',
    ];
    return $iconMap[$emoji] ?? 'fas fa-circle';
}

// Define menu items for each role
$menus = [
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
    'staff' => [
        ['icon' => '📊', 'label' => 'Dashboard', 'url' => '/staff/dashboard'],
        ['icon' => '👔', 'label' => 'Staff', 'url' => '/staff/staff'],
        ['icon' => '🎓', 'label' => 'Specializations', 'url' => '/staff/specializations'],
        ['icon' => '📋', 'label' => 'Statuses', 'url' => '/staff/statuses'],
        ['icon' => '🔬', 'label' => 'Services', 'url' => '/staff/services'],
        ['icon' => '💳', 'label' => 'Payment Methods', 'url' => '/staff/payment-methods'],
        ['icon' => '💰', 'label' => 'Payment Statuses', 'url' => '/staff/payment-statuses'],
        ['icon' => '💵', 'label' => 'Payments', 'url' => '/staff/payments'],
        ['icon' => '📄', 'label' => 'Medical Records', 'url' => '/staff/medical-records'],
    ],
    'doctor' => [
        ['icon' => '📊', 'label' => 'Dashboard', 'url' => '/doctor/dashboard'],
        ['icon' => '📅', 'label' => 'Appointments', 'url' => '/doctor/appointments/today'],
        ['icon' => '⏰', 'label' => 'Schedules', 'url' => '/doctor/schedules'],
        ['icon' => '👨‍⚕️', 'label' => 'Doctors', 'url' => '/doctor/doctors'],
        ['icon' => '📄', 'label' => 'Medical Records', 'url' => '/doctor/medical-records'],
    ],
    'patient' => [
        ['icon' => '🏠', 'label' => 'Dashboard', 'url' => '/patient/dashboard'],
        ['icon' => '📅', 'label' => 'My Appointments', 'url' => '/patient/appointments'],
        ['icon' => '📖', 'label' => 'Book', 'url' => '/patient/book'],
        ['icon' => '📄', 'label' => 'Medical Records', 'url' => '/patient/medical-records'],
        ['icon' => '💳', 'label' => 'Payments', 'url' => '/patient/payments'],
        ['icon' => '🔔', 'label' => 'Notifications', 'url' => '/patient/notifications'],
    ],
];

$currentMenu = $menus[$role] ?? [];
$currentPath = $_SERVER['REQUEST_URI'];
?>

<div class="sidebar-modern" id="sidebar">
    <!-- Sidebar Header -->
    <div class="sidebar-header">
        <div class="sidebar-logo">
            <div class="logo-icon-sidebar">
                <i class="fas fa-heartbeat"></i>
            </div>
            <span class="logo-text">Medi-Care</span>
        </div>
        <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">
            <i class="fas fa-bars"></i>
        </button>
    </div>
    
    <!-- Search Bar -->
    <div class="sidebar-search">
        <i class="fas fa-search"></i>
        <input type="text" placeholder="Search..." class="search-input-sidebar" id="sidebarSearch">
    </div>
    
    <!-- Menu Items -->
    <div class="sidebar-menu">
        <?php foreach ($currentMenu as $item): ?>
            <?php 
            $isActive = strpos($currentPath, $item['url']) !== false;
            ?>
            <a href="<?= $item['url'] ?>" class="menu-item-modern <?= $isActive ? 'active' : '' ?>" 
               data-tooltip="<?= htmlspecialchars($item['label']) ?>">
                <i class="<?= getIcon($item['icon']) ?>"></i>
                <span class="menu-label"><?= htmlspecialchars($item['label']) ?></span>
            </a>
        <?php endforeach; ?>
    </div>
    
    <!-- User Profile Section -->
    <div class="sidebar-profile-modern">
        <div style="display: flex; align-items: center; gap: 0.5rem;">
            <div class="profile-info" onclick="toggleProfileMenu()">
                <div class="profile-avatar-modern"><?= $userInitial ?></div>
                <div class="profile-details">
                    <div class="profile-name-modern"><?= htmlspecialchars($userName) ?></div>
                    <div class="profile-title"><?= htmlspecialchars($userTitle) ?></div>
                </div>
            </div>
            <button class="profile-logout" onclick="toggleProfileMenu()" aria-label="Profile menu">
                <i class="fas fa-sign-out-alt"></i>
            </button>
        </div>
        
        <!-- Profile Dropdown -->
        <div class="profile-dropdown" id="profileDropdown">
            <a href="/<?= $role ?>/account" class="profile-dropdown-item">
                <i class="fas fa-user"></i>
                <span>Account</span>
            </a>
            <a href="/<?= $role ?>/settings" class="profile-dropdown-item">
                <i class="fas fa-cog"></i>
                <span>Settings</span>
            </a>
            <a href="/<?= $role ?>/privacy" class="profile-dropdown-item">
                <i class="fas fa-shield-alt"></i>
                <span>Privacy</span>
            </a>
            <div class="profile-dropdown-divider"></div>
            <div class="profile-dropdown-item dark-mode-toggle" onclick="toggleDarkMode(event)">
                <i class="fas fa-moon"></i>
                <span>Dark Mode</span>
                <div class="toggle-switch" id="darkModeToggle"></div>
            </div>
            <div class="profile-dropdown-divider"></div>
            <a href="/logout" class="profile-dropdown-item logout-item">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </div>
    </div>
</div>

<script>
// Sidebar toggle functionality
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('sidebarToggle');
    const mainContent = document.querySelector('.main-content');
    
    // Check localStorage for sidebar state
    const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
    if (isCollapsed) {
        sidebar.classList.add('collapsed');
        if (mainContent) mainContent.classList.add('sidebar-collapsed');
    }
    
    toggleBtn.addEventListener('click', function() {
        sidebar.classList.toggle('collapsed');
        if (mainContent) mainContent.classList.toggle('sidebar-collapsed');
        localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
    });
    
    // Tooltip functionality for collapsed sidebar
    const menuItems = document.querySelectorAll('.menu-item-modern');
    menuItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            if (sidebar.classList.contains('collapsed')) {
                const tooltip = document.createElement('div');
                tooltip.className = 'menu-tooltip';
                tooltip.textContent = this.dataset.tooltip;
                document.body.appendChild(tooltip);
                
                const rect = this.getBoundingClientRect();
                tooltip.style.left = rect.right + 10 + 'px';
                tooltip.style.top = rect.top + (rect.height / 2) - (tooltip.offsetHeight / 2) + 'px';
                
                this._tooltip = tooltip;
            }
        });
        
        item.addEventListener('mouseleave', function() {
            if (this._tooltip) {
                this._tooltip.remove();
                this._tooltip = null;
            }
        });
    });
});

// Profile menu toggle
function toggleProfileMenu() {
    const dropdown = document.getElementById('profileDropdown');
    dropdown.classList.toggle('active');
}

// Close profile dropdown when clicking outside
document.addEventListener('click', function(event) {
    const profileSection = document.querySelector('.sidebar-profile-modern');
    const dropdown = document.getElementById('profileDropdown');
    
    if (profileSection && dropdown && !profileSection.contains(event.target)) {
        dropdown.classList.remove('active');
    }
});

// Search functionality
document.getElementById('sidebarSearch')?.addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const menuItems = document.querySelectorAll('.menu-item-modern');
    
    menuItems.forEach(item => {
        const label = item.querySelector('.menu-label')?.textContent.toLowerCase() || '';
        if (label.includes(searchTerm)) {
            item.style.display = 'flex';
        } else {
            item.style.display = searchTerm ? 'none' : 'flex';
        }
    });
});
</script>
