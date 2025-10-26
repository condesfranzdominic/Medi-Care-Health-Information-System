<?php
ob_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/functions.php';

$auth = new Auth();
$pageTitle = $pageTitle ?? "Medi-Care Health Information System";

// URI helpers
$currentUri = strtok($_SERVER['REQUEST_URI'], '?'); // path without query
$currentPage = basename($_SERVER['PHP_SELF']);
$currentDir = basename(dirname($_SERVER['PHP_SELF']));

/**
 * Return true when $path matches the current request URI.
 * - $path should be root-relative (start with '/'), or a specific file path.
 * - If $exactMatch is true, require exact path match (ignoring trailing slash).
 * - If $exactMatch is false, treat $path as a prefix for section highlighting.
 */
function isActive($path, $exactMatch = false) {
    $currentUri = strtok($_SERVER['REQUEST_URI'], '?');
    $path = strtok($path, '?');

    // Normalize trailing slashes
    $cur = rtrim($currentUri, '/');
    $p = rtrim($path, '/');

    if ($exactMatch) {
        return $cur === $p;
    }

    // Prefix match (section)
    return $p === '' ? false : strpos($cur, $p) === 0;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar-link:hover { background-color: rgba(59, 130, 246, 0.1); }
        .sidebar-link.active { background-color: rgba(59, 130, 246, 0.2); border-left: 4px solid #3B82F6; }
    </style>
</head>
<body class="bg-gray-50">
    <?php if ($auth->isLoggedIn()): ?>
        <!-- Navigation Bar -->
        <nav class="bg-blue-600 text-white shadow-lg">
            <div class="container mx-auto px-4">
                <div class="flex justify-between items-center py-4">
                    <div class="flex items-center space-x-4">
                        <i class="fas fa-hospital text-2xl"></i>
                        <span class="text-xl font-bold">Medi-Care Health Information System</span>
                    </div>
                    <div class="flex items-center space-x-6">
                        <span class="text-sm">
                            <i class="fas fa-user-circle mr-2"></i>
                            <?php echo htmlspecialchars($_SESSION['user_email']); ?>
                            <span class="ml-2 px-2 py-1 bg-blue-500 rounded text-xs uppercase">
                                <?php echo htmlspecialchars($_SESSION['role'] ?? ''); ?>
                            </span>
                        </span>
                        <a href="/logout.php" class="hover:text-blue-200">
                            <i class="fas fa-sign-out-alt mr-1"></i> Logout
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <div class="flex min-h-screen">
            <!-- Sidebar -->
            <aside class="w-64 bg-white shadow-lg">
                <nav class="py-4">
                    <?php $role = $auth->getRole(); ?>

                    <!-- Dashboard -->
                    <a href="/dashboard/<?php echo $role; ?>.php"
                       class="sidebar-link flex items-center px-6 py-3 text-gray-700 <?php echo isActive("/dashboard/{$role}.php", true) ? 'active' : ''; ?>">
                        <i class="fas fa-tachometer-alt w-6"></i>
                        <span class="ml-3">Dashboard</span>
                    </a>

                    <?php if ($auth->isSuperAdmin() || $auth->isStaff()): ?>
                        <a href="/views/staff/index.php" class="sidebar-link flex items-center px-6 py-3 text-gray-700 <?php echo isActive('/views/staff') ? 'active' : ''; ?>">
                            <i class="fas fa-user-tie w-6"></i><span class="ml-3">Staff</span>
                        </a>

                        <a href="/views/patients/index.php" class="sidebar-link flex items-center px-6 py-3 text-gray-700 <?php echo isActive('/views/patients') ? 'active' : ''; ?>">
                            <i class="fas fa-user-injured w-6"></i><span class="ml-3">Patients</span>
                        </a>

                        <a href="/views/appointments/index.php" class="sidebar-link flex items-center px-6 py-3 text-gray-700 <?php echo isActive('/views/appointments') ? 'active' : ''; ?>">
                            <i class="fas fa-calendar-check w-6"></i><span class="ml-3">Appointments</span>
                        </a>

                        <a href="/views/services/index.php" class="sidebar-link flex items-center px-6 py-3 text-gray-700 <?php echo isActive('/views/services') ? 'active' : ''; ?>">
                            <i class="fas fa-concierge-bell w-6"></i><span class="ml-3">Services</span>
                        </a>

                        <a href="/views/payments/index.php" class="sidebar-link flex items-center px-6 py-3 text-gray-700 <?php echo isActive('/views/payments') ? 'active' : ''; ?>">
                            <i class="fas fa-money-bill-wave w-6"></i><span class="ml-3">Payments</span>
                        </a>
                    <?php endif; ?>

                    <?php if ($auth->isSuperAdmin()): ?>
                        <a href="/views/doctors/index.php" class="sidebar-link flex items-center px-6 py-3 text-gray-700 <?php echo isActive('/views/doctors') ? 'active' : ''; ?>">
                            <i class="fas fa-user-md w-6"></i><span class="ml-3">Doctors</span>
                        </a>

                        <a href="/views/specializations/index.php" class="sidebar-link flex items-center px-6 py-3 text-gray-700 <?php echo isActive('/views/specializations') ? 'active' : ''; ?>">
                            <i class="fas fa-stethoscope w-6"></i><span class="ml-3">Specializations</span>
                        </a>

                        <a href="/views/schedules/index.php" class="sidebar-link flex items-center px-6 py-3 text-gray-700 <?php echo isActive('/views/schedules') ? 'active' : ''; ?>">
                            <i class="fas fa-clock w-6"></i><span class="ml-3">Schedules</span>
                        </a>

                        <a href="/views/statuses/index.php" class="sidebar-link flex items-center px-6 py-3 text-gray-700 <?php echo isActive('/views/statuses') ? 'active' : ''; ?>">
                            <i class="fas fa-tags w-6"></i><span class="ml-3">Statuses</span>
                        </a>

                        <a href="/views/medical_records/index.php" class="sidebar-link flex items-center px-6 py-3 text-gray-700 <?php echo isActive('/views/medical_records') ? 'active' : ''; ?>">
                            <i class="fas fa-file-medical w-6"></i><span class="ml-3">Medical Records</span>
                        </a>

                        <a href="/views/users/index.php" class="sidebar-link flex items-center px-6 py-3 text-gray-700 <?php echo isActive('/views/users') ? 'active' : ''; ?>">
                            <i class="fas fa-users-cog w-6"></i><span class="ml-3">Users</span>
                        </a>
                    <?php endif; ?>

                    <?php if ($auth->isDoctor()): ?>
                        <a href="/views/doctors/appointments.php" class="sidebar-link flex items-center px-6 py-3 text-gray-700 <?php echo isActive('/views/doctors/appointments.php', true) ? 'active' : ''; ?>">
                            <i class="fas fa-calendar-check w-6"></i><span class="ml-3">My Appointments</span>
                        </a>

                        <a href="/views/medical_records/index.php" class="sidebar-link flex items-center px-6 py-3 text-gray-700 <?php echo isActive('/views/medical_records') ? 'active' : ''; ?>">
                            <i class="fas fa-file-medical w-6"></i><span class="ml-3">Medical Records</span>
                        </a>
                    <?php endif; ?>

                    <?php if ($auth->isPatient()): ?>
                        <a href="/views/patients/book-appointment.php" class="sidebar-link flex items-center px-6 py-3 text-gray-700 <?php echo isActive('/views/patients/book-appointment.php', true) ? 'active' : ''; ?>">
                            <i class="fas fa-calendar-plus w-6"></i><span class="ml-3">Book Appointment</span>
                        </a>

                        <a href="/views/patients/appointments.php" class="sidebar-link flex items-center px-6 py-3 text-gray-700 <?php echo isActive('/views/patients/appointments.php') ? 'active' : ''; ?>">
                            <i class="fas fa-calendar-alt w-6"></i><span class="ml-3">My Appointments</span>
                        </a>

                        <a href="/views/patients/profile.php" class="sidebar-link flex items-center px-6 py-3 text-gray-700 <?php echo isActive('/views/patients/profile.php', true) ? 'active' : ''; ?>">
                            <i class="fas fa-user w-6"></i><span class="ml-3">My Profile</span>
                        </a>
                    <?php endif; ?>
                </nav>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 p-8">
                <?php
                $flash = getFlashMessage();
                if ($flash):
                    $bgColor = $flash['type'] === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700';
                ?>
                    <div class="<?php echo $bgColor; ?> border px-4 py-3 rounded mb-4" role="alert">
                        <span class="block sm:inline"><?php echo htmlspecialchars($flash['message']); ?></span>
                    </div>
                <?php endif; ?>

                <!-- Your page content goes here -->
        <?php endif; ?>
