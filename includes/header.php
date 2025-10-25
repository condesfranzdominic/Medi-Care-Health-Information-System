<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/functions.php';

$auth = new Auth();
$pageTitle = $pageTitle ?? "Medi-Care Health Information System";
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
        .sidebar-link:hover {
            background-color: rgba(59, 130, 246, 0.1);
        }
        .sidebar-link.active {
            background-color: rgba(59, 130, 246, 0.2);
            border-left: 4px solid #3B82F6;
        }
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
                                <?php echo htmlspecialchars($_SESSION['role']); ?>
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
                    <?php
                    $role = $auth->getRole();
                    $currentPage = basename($_SERVER['PHP_SELF']);
                    ?>
                    
                    <!-- Dashboard -->
                    <a href="/dashboard.php" class="sidebar-link flex items-center px-6 py-3 text-gray-700 <?php echo $currentPage === 'dashboard.php' ? 'active' : ''; ?>">
                        <i class="fas fa-tachometer-alt w-6"></i>
                        <span class="ml-3">Dashboard</span>
                    </a>

                    <?php if ($role === 'superadmin' || $role === 'staff'): ?>
                        <!-- Staff Management -->
                        <a href="/staff/index.php" class="sidebar-link flex items-center px-6 py-3 text-gray-700">
                            <i class="fas fa-user-tie w-6"></i>
                            <span class="ml-3">Staff</span>
                        </a>

                        <!-- Patient Management -->
                        <a href="/patients/index.php" class="sidebar-link flex items-center px-6 py-3 text-gray-700">
                            <i class="fas fa-user-injured w-6"></i>
                            <span class="ml-3">Patients</span>
                        </a>

                        <!-- Appointments -->
                        <a href="/appointments/index.php" class="sidebar-link flex items-center px-6 py-3 text-gray-700">
                            <i class="fas fa-calendar-check w-6"></i>
                            <span class="ml-3">Appointments</span>
                        </a>

                        <!-- Services -->
                        <a href="/services/index.php" class="sidebar-link flex items-center px-6 py-3 text-gray-700">
                            <i class="fas fa-concierge-bell w-6"></i>
                            <span class="ml-3">Services</span>
                        </a>

                        <!-- Payments -->
                        <a href="/payments/index.php" class="sidebar-link flex items-center px-6 py-3 text-gray-700">
                            <i class="fas fa-money-bill-wave w-6"></i>
                            <span class="ml-3">Payments</span>
                        </a>
                    <?php endif; ?>

                    <?php if ($role === 'superadmin'): ?>
                        <!-- Doctors -->
                        <a href="/doctors/index.php" class="sidebar-link flex items-center px-6 py-3 text-gray-700">
                            <i class="fas fa-user-md w-6"></i>
                            <span class="ml-3">Doctors</span>
                        </a>

                        <!-- Specializations -->
                        <a href="/specializations/index.php" class="sidebar-link flex items-center px-6 py-3 text-gray-700">
                            <i class="fas fa-stethoscope w-6"></i>
                            <span class="ml-3">Specializations</span>
                        </a>

                        <!-- Schedules -->
                        <a href="/schedules/index.php" class="sidebar-link flex items-center px-6 py-3 text-gray-700">
                            <i class="fas fa-clock w-6"></i>
                            <span class="ml-3">Schedules</span>
                        </a>

                        <!-- Status Management -->
                        <a href="/statuses/index.php" class="sidebar-link flex items-center px-6 py-3 text-gray-700">
                            <i class="fas fa-tags w-6"></i>
                            <span class="ml-3">Statuses</span>
                        </a>

                        <!-- Medical Records -->
                        <a href="/records/index.php" class="sidebar-link flex items-center px-6 py-3 text-gray-700">
                            <i class="fas fa-file-medical w-6"></i>
                            <span class="ml-3">Medical Records</span>
                        </a>

                        <!-- User Management -->
                        <a href="/users/index.php" class="sidebar-link flex items-center px-6 py-3 text-gray-700">
                            <i class="fas fa-users-cog w-6"></i>
                            <span class="ml-3">Users</span>
                        </a>
                    <?php endif; ?>

                    <?php if ($role === 'doctor'): ?>
                        <!-- Doctor's Appointments -->
                        <a href="/doctor/appointments.php" class="sidebar-link flex items-center px-6 py-3 text-gray-700">
                            <i class="fas fa-calendar-check w-6"></i>
                            <span class="ml-3">My Appointments</span>
                        </a>

                        <!-- Medical Records -->
                        <a href="/doctor/records.php" class="sidebar-link flex items-center px-6 py-3 text-gray-700">
                            <i class="fas fa-file-medical w-6"></i>
                            <span class="ml-3">Medical Records</span>
                        </a>
                    <?php endif; ?>

                    <?php if ($role === 'patient'): ?>
                        <!-- Book Appointment -->
                        <a href="/patient/book-appointment.php" class="sidebar-link flex items-center px-6 py-3 text-gray-700">
                            <i class="fas fa-calendar-plus w-6"></i>
                            <span class="ml-3">Book Appointment</span>
                        </a>

                        <!-- My Appointments -->
                        <a href="/patient/appointments.php" class="sidebar-link flex items-center px-6 py-3 text-gray-700">
                            <i class="fas fa-calendar-alt w-6"></i>
                            <span class="ml-3">My Appointments</span>
                        </a>

                        <!-- My Profile -->
                        <a href="/patient/profile.php" class="sidebar-link flex items-center px-6 py-3 text-gray-700">
                            <i class="fas fa-user w-6"></i>
                            <span class="ml-3">My Profile</span>
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
