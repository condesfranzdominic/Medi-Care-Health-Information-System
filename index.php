<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';

$auth = new Auth();

// helper to build absolute links using APP_URL when available
function url($path) {
    if (defined('APP_URL') && APP_URL) {
        return rtrim(APP_URL, '/') . '/' . ltrim($path, '/');
    }
    // ensure root-relative
    return (strpos($path, '/') === 0) ? $path : '/' . ltrim($path, '/');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Medi-Care — Temporary Landing</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-50 flex items-center justify-center">
    <div class="max-w-lg w-full text-center p-8 bg-white rounded-lg shadow">
        <h1 class="text-2xl font-bold mb-2">Medi-Care Health Information System</h1>
        <p class="text-gray-600 mb-6">This is a temporary landing page. Use the links below to continue.</p>

        <?php if ($auth->isLoggedIn()): 
            $role = $auth->getRole();
            $dash = url('/dashboard/index.php');
            if ($auth->isSuperAdmin()) $dash = url('/dashboard/superadmin.php');
            elseif ($auth->isStaff()) $dash = url('/dashboard/staff.php');
            elseif ($auth->isDoctor()) $dash = url('/dashboard/doctor.php');
            elseif ($auth->isPatient()) $dash = url('/dashboard/patient.php');
        ?>
            <p class="mb-4">Signed in as <strong><?php echo htmlspecialchars($_SESSION['user_email'] ?? ''); ?></strong></p>
            <a href="<?php echo htmlspecialchars($dash); ?>" class="inline-block px-6 py-2 bg-blue-600 text-white rounded mr-2">Go to Dashboard</a>
            <a href="<?php echo htmlspecialchars(url('/views/logout.php')); ?>" class="inline-block px-6 py-2 bg-gray-200 rounded">Logout</a>
        <?php else: ?>
            <a href="<?php echo htmlspecialchars(url('views/login.php')); ?>" class="inline-block px-6 py-2 bg-blue-600 text-white rounded mr-2">Login</a>
            <a href="<?php echo htmlspecialchars(url('/views/info.php')); ?>" class="inline-block px-6 py-2 bg-gray-200 rounded">About / Info</a>
        <?php endif; ?>

        <p class="text-xs text-gray-400 mt-6">Temporary page — replace with your real landin  g or marketing page later.</p>
    </div>
</body>
</html>
