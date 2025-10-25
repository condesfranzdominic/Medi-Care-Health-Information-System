<?php
$pageTitle = "View Staff - Medi-Care Health Information System";
require_once __DIR__ . '/../includes/header.php';

$auth->requireRole(['superadmin', 'staff']);
$db = Database::getInstance();

$staffId = intval($_GET['id'] ?? 0);
$staff = $db->fetchOne("SELECT * FROM staff WHERE staff_id = :id", ['id' => $staffId]);

if (!$staff) {
    setFlashMessage('error', 'Staff member not found.');
    redirect('/staff/index.php');
}
?>

<div class="container mx-auto max-w-4xl">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-user-tie mr-2"></i>Staff Details
        </h1>
        <div class="space-x-2">
            <?php if ($auth->isSuperAdmin()): ?>
                <a href="/staff/edit.php?id=<?php echo $staffId; ?>" 
                   class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 transition duration-300">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>
            <?php endif; ?>
            <a href="/staff/index.php" 
               class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition duration-300">
                <i class="fas fa-arrow-left mr-2"></i>Back
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-gray-500 text-sm font-semibold mb-1">Staff ID</label>
                <p class="text-gray-900 text-lg"><?php echo htmlspecialchars($staff['staff_id']); ?></p>
            </div>

            <div>
                <label class="block text-gray-500 text-sm font-semibold mb-1">Status</label>
                <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                    <?php echo $staff['staff_status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                    <?php echo htmlspecialchars(ucfirst($staff['staff_status'])); ?>
                </span>
            </div>

            <div>
                <label class="block text-gray-500 text-sm font-semibold mb-1">First Name</label>
                <p class="text-gray-900 text-lg"><?php echo htmlspecialchars($staff['staff_first_name']); ?></p>
            </div>

            <div>
                <label class="block text-gray-500 text-sm font-semibold mb-1">Last Name</label>
                <p class="text-gray-900 text-lg"><?php echo htmlspecialchars($staff['staff_last_name']); ?></p>
            </div>

            <div>
                <label class="block text-gray-500 text-sm font-semibold mb-1">Email</label>
                <p class="text-gray-900 text-lg"><?php echo htmlspecialchars($staff['staff_email']); ?></p>
            </div>

            <div>
                <label class="block text-gray-500 text-sm font-semibold mb-1">Phone</label>
                <p class="text-gray-900 text-lg"><?php echo htmlspecialchars($staff['staff_phone'] ?? 'N/A'); ?></p>
            </div>

            <div>
                <label class="block text-gray-500 text-sm font-semibold mb-1">Position</label>
                <p class="text-gray-900 text-lg"><?php echo htmlspecialchars($staff['staff_position'] ?? 'N/A'); ?></p>
            </div>

            <div>
                <label class="block text-gray-500 text-sm font-semibold mb-1">Hire Date</label>
                <p class="text-gray-900 text-lg">
                    <?php echo $staff['staff_hire_date'] ? formatDate($staff['staff_hire_date'], 'M d, Y') : 'N/A'; ?>
                </p>
            </div>

            <div>
                <label class="block text-gray-500 text-sm font-semibold mb-1">Salary</label>
                <p class="text-gray-900 text-lg">
                    <?php echo $staff['staff_salary'] ? formatCurrency($staff['staff_salary']) : 'N/A'; ?>
                </p>
            </div>

            <div>
                <label class="block text-gray-500 text-sm font-semibold mb-1">Created At</label>
                <p class="text-gray-900 text-lg"><?php echo formatDateTime($staff['created_at'], 'M d, Y h:i A'); ?></p>
            </div>

            <div>
                <label class="block text-gray-500 text-sm font-semibold mb-1">Updated At</label>
                <p class="text-gray-900 text-lg"><?php echo formatDateTime($staff['updated_at'], 'M d, Y h:i A'); ?></p>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
