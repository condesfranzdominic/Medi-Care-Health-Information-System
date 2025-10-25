<?php
$pageTitle = "Add New Staff - Medi-Care Health Information System";
require_once __DIR__ . '/../includes/header.php';

$auth->requireRole(['superadmin']);
$db = Database::getInstance();

$errors = [];
$formData = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
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
    
    // Validation
    if (empty($formData['staff_first_name'])) $errors[] = 'First name is required.';
    if (empty($formData['staff_last_name'])) $errors[] = 'Last name is required.';
    if (empty($formData['staff_email'])) {
        $errors[] = 'Email is required.';
    } elseif (!isValidEmail($formData['staff_email'])) {
        $errors[] = 'Invalid email format.';
    }
    if (!empty($formData['staff_phone']) && !isValidPhone($formData['staff_phone'])) {
        $errors[] = 'Invalid phone number format.';
    }
    
    // Check if email already exists
    if (empty($errors)) {
        $existing = $db->fetchOne("SELECT staff_id FROM staff WHERE staff_email = :email", 
                                  ['email' => $formData['staff_email']]);
        if ($existing) {
            $errors[] = 'Email already exists.';
        }
    }
    
    // Insert if no errors
    if (empty($errors)) {
        try {
            $sql = "INSERT INTO staff (staff_first_name, staff_last_name, staff_email, staff_phone, 
                    staff_position, staff_hire_date, staff_salary, staff_status) 
                    VALUES (:first_name, :last_name, :email, :phone, :position, :hire_date, :salary, :status)";
            
            $db->execute($sql, [
                'first_name' => $formData['staff_first_name'],
                'last_name' => $formData['staff_last_name'],
                'email' => $formData['staff_email'],
                'phone' => $formData['staff_phone'] ?: null,
                'position' => $formData['staff_position'] ?: null,
                'hire_date' => $formData['staff_hire_date'] ?: null,
                'salary' => $formData['staff_salary'] ?: null,
                'status' => $formData['staff_status']
            ]);
            
            $staffId = $db->lastInsertId();
            setFlashMessage('success', 'Staff member added successfully.');
            redirect("/users/create.php?staff_id=$staffId");
        } catch (Exception $e) {
            $errors[] = 'Failed to add staff member: ' . $e->getMessage();
        }
    }
}
?>

<div class="container mx-auto max-w-2xl">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">
        <i class="fas fa-user-plus mr-2"></i>Add New Staff
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
                           value="<?php echo htmlspecialchars($formData['staff_first_name'] ?? ''); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Last Name *</label>
                    <input type="text" name="staff_last_name" required
                           value="<?php echo htmlspecialchars($formData['staff_last_name'] ?? ''); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Email *</label>
                    <input type="email" name="staff_email" required
                           value="<?php echo htmlspecialchars($formData['staff_email'] ?? ''); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Phone</label>
                    <input type="text" name="staff_phone"
                           value="<?php echo htmlspecialchars($formData['staff_phone'] ?? ''); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Position</label>
                    <input type="text" name="staff_position"
                           value="<?php echo htmlspecialchars($formData['staff_position'] ?? ''); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Hire Date</label>
                    <input type="date" name="staff_hire_date"
                           value="<?php echo htmlspecialchars($formData['staff_hire_date'] ?? ''); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Salary</label>
                    <input type="number" name="staff_salary" step="0.01" min="0"
                           value="<?php echo htmlspecialchars($formData['staff_salary'] ?? ''); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Status *</label>
                    <select name="staff_status" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="active" <?php echo ($formData['staff_status'] ?? 'active') === 'active' ? 'selected' : ''; ?>>Active</option>
                        <option value="inactive" <?php echo ($formData['staff_status'] ?? '') === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end space-x-4 mt-6">
                <a href="/staff/index.php" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition duration-300">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-300">
                    <i class="fas fa-save mr-2"></i>Save Staff
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
