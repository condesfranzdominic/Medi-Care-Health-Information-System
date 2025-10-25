<?php
$pageTitle = "Edit Staff - Medi-Care Health Information System";
require_once __DIR__ . '/../includes/header.php';

$auth->requireRole(['superadmin']);
$db = Database::getInstance();

$staffId = intval($_GET['id'] ?? 0);
$errors = [];
$staff = null;

// Fetch staff member
$staff = $db->fetchOne("SELECT * FROM staff WHERE staff_id = :id", ['id' => $staffId]);

if (!$staff) {
    setFlashMessage('error', 'Staff member not found.');
    redirect('/staff/index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData = [
        'staff_first_name' => sanitize($_POST['staff_first_name'] ?? ''),
        'staff_last_name' => sanitize($_POST['staff_last_name'] ?? ''),
        'staff_email' => sanitize($_POST['staff_email'] ?? ''),
        'staff_phone' => sanitize($_POST['staff_phone'] ?? ''),
        'staff_position' => sanitize($_POST['staff_position'] ?? ''),
        'staff_hire_date' => sanitize($_POST['staff_hire_date'] ?? ''),
        'staff_salary' => sanitize($_POST['staff_salary'] ?? ''),
        'staff_status' => sanitize($_POST['staff_status'] ?? 'active')
    ];
    
    if (empty($formData['staff_first_name'])) $errors[] = 'First name is required.';
    if (empty($formData['staff_last_name'])) $errors[] = 'Last name is required.';
    if (empty($formData['staff_email'])) {
        $errors[] = 'Email is required.';
    } elseif (!isValidEmail($formData['staff_email'])) {
        $errors[] = 'Invalid email format.';
    }
    
    // Check if email exists for another staff member
    if (empty($errors)) {
        $existing = $db->fetchOne("SELECT staff_id FROM staff WHERE staff_email = :email AND staff_id != :id", 
                                  ['email' => $formData['staff_email'], 'id' => $staffId]);
        if ($existing) {
            $errors[] = 'Email already exists.';
        }
    }
    
    if (empty($errors)) {
        try {
            $sql = "UPDATE staff SET staff_first_name = :first_name, staff_last_name = :last_name, 
                    staff_email = :email, staff_phone = :phone, staff_position = :position, 
                    staff_hire_date = :hire_date, staff_salary = :salary, staff_status = :status 
                    WHERE staff_id = :id";
            
            $db->execute($sql, [
                'first_name' => $formData['staff_first_name'],
                'last_name' => $formData['staff_last_name'],
                'email' => $formData['staff_email'],
                'phone' => $formData['staff_phone'] ?: null,
                'position' => $formData['staff_position'] ?: null,
                'hire_date' => $formData['staff_hire_date'] ?: null,
                'salary' => $formData['staff_salary'] ?: null,
                'status' => $formData['staff_status'],
                'id' => $staffId
            ]);
            
            setFlashMessage('success', 'Staff member updated successfully.');
            redirect('/staff/index.php');
        } catch (Exception $e) {
            $errors[] = 'Failed to update staff member: ' . $e->getMessage();
        }
    }
    
    $staff = array_merge($staff, $formData);
}
?>

<div class="container mx-auto max-w-2xl">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">
        <i class="fas fa-edit mr-2"></i>Edit Staff
    </h1>

    <?php if (!empty($errors)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc list-inside">
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="POST" action="">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">First Name *</label>
                    <input type="text" name="staff_first_name" required
                           value="<?php echo htmlspecialchars($staff['staff_first_name']); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Last Name *</label>
                    <input type="text" name="staff_last_name" required
                           value="<?php echo htmlspecialchars($staff['staff_last_name']); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Email *</label>
                    <input type="email" name="staff_email" required
                           value="<?php echo htmlspecialchars($staff['staff_email']); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Phone</label>
                    <input type="text" name="staff_phone"
                           value="<?php echo htmlspecialchars($staff['staff_phone'] ?? ''); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Position</label>
                    <input type="text" name="staff_position"
                           value="<?php echo htmlspecialchars($staff['staff_position'] ?? ''); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Hire Date</label>
                    <input type="date" name="staff_hire_date"
                           value="<?php echo htmlspecialchars($staff['staff_hire_date'] ?? ''); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Salary</label>
                    <input type="number" name="staff_salary" step="0.01" min="0"
                           value="<?php echo htmlspecialchars($staff['staff_salary'] ?? ''); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Status *</label>
                    <select name="staff_status" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="active" <?php echo $staff['staff_status'] === 'active' ? 'selected' : ''; ?>>Active</option>
                        <option value="inactive" <?php echo $staff['staff_status'] === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end space-x-4 mt-6">
                <a href="/staff/index.php" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition duration-300">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-300">
                    <i class="fas fa-save mr-2"></i>Update Staff
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
