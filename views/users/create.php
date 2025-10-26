<?php
$pageTitle = "Create User Account - Medi-Care Health Information System";
require_once __DIR__ . '/../../includes/header.php';

$auth->requireRole(['superadmin']);
$db = Database::getInstance();

$errors = [];
$formData = [];

// Get IDs from query params if redirected from staff/patient/doctor creation
$staffId = intval($_GET['staff_id'] ?? 0);
$patientId = intval($_GET['patient_id'] ?? 0);
$docId = intval($_GET['doc_id'] ?? 0);

// Fetch entity details
$entity = null;
$entityType = '';
if ($staffId > 0) {
    $entity = $db->fetchOne("SELECT staff_id, staff_first_name, staff_last_name, staff_email FROM staff WHERE staff_id = :id", ['id' => $staffId]);
    $entityType = 'staff';
} elseif ($patientId > 0) {
    $entity = $db->fetchOne("SELECT pat_id, pat_first_name, pat_last_name, pat_email FROM patients WHERE pat_id = :id", ['id' => $patientId]);
    $entityType = 'patient';
} elseif ($docId > 0) {
    $entity = $db->fetchOne("SELECT doc_id, doc_first_name, doc_last_name, doc_email FROM doctors WHERE doc_id = :id", ['id' => $docId]);
    $entityType = 'doctor';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData = [
        'user_email' => sanitize($_POST['user_email'] ?? ''),
        'user_password' => $_POST['user_password'] ?? '',
        'user_password_confirm' => $_POST['user_password_confirm'] ?? '',
        'user_is_superadmin' => isset($_POST['user_is_superadmin']) ? 1 : 0,
        'staff_id' => intval($_POST['staff_id'] ?? 0),
        'pat_id' => intval($_POST['pat_id'] ?? 0),
        'doc_id' => intval($_POST['doc_id'] ?? 0)
    ];
    
    if (empty($formData['user_email']) || !isValidEmail($formData['user_email'])) {
        $errors[] = 'Valid email is required.';
    }
    if (empty($formData['user_password'])) {
        $errors[] = 'Password is required.';
    } elseif (strlen($formData['user_password']) < 6) {
        $errors[] = 'Password must be at least 6 characters.';
    }
    if ($formData['user_password'] !== $formData['user_password_confirm']) {
        $errors[] = 'Passwords do not match.';
    }
    
    // Check if email already exists
    if (empty($errors)) {
        $existing = $db->fetchOne("SELECT user_id FROM users WHERE user_email = :email", ['email' => $formData['user_email']]);
        if ($existing) {
            $errors[] = 'Email already exists.';
        }
    }
    
    if (empty($errors)) {
        try {
            $hashedPassword = password_hash($formData['user_password'], PASSWORD_DEFAULT);
            
            $sql = "INSERT INTO users (user_email, user_password, user_is_superadmin, pat_id, staff_id, doc_id) 
                    VALUES (:email, :password, :is_superadmin, :pat_id, :staff_id, :doc_id)";
            
            $db->execute($sql, [
                'email' => $formData['user_email'],
                'password' => $hashedPassword,
                'is_superadmin' => $formData['user_is_superadmin'],
                'pat_id' => $formData['pat_id'] ?: null,
                'staff_id' => $formData['staff_id'] ?: null,
                'doc_id' => $formData['doc_id'] ?: null
            ]);
            
            setFlashMessage('success', 'User account created successfully.');
            
            // Redirect based on entity type
            if ($entityType === 'staff') redirect('/staff/index.php');
            elseif ($entityType === 'patient') redirect('/patients/index.php');
            elseif ($entityType === 'doctor') redirect('/doctors/index.php');
            else redirect('/users/index.php');
        } catch (Exception $e) {
            $errors[] = 'Failed to create user: ' . $e->getMessage();
        }
    }
}
?>

<div class="container mx-auto max-w-2xl">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">
        <i class="fas fa-user-plus mr-2"></i>Create User Account
    </h1>

    <?php if ($entity): ?>
        <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-4">
            Creating user account for: <strong><?php echo htmlspecialchars($entity[array_key_first($entity) + 1] . ' ' . $entity[array_key_first($entity) + 2]); ?></strong>
        </div>
    <?php endif; ?>

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
            <input type="hidden" name="staff_id" value="<?php echo $staffId; ?>">
            <input type="hidden" name="pat_id" value="<?php echo $patientId; ?>">
            <input type="hidden" name="doc_id" value="<?php echo $docId; ?>">

            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Email *</label>
                <input type="email" name="user_email" required
                       value="<?php echo htmlspecialchars($entity ? $entity[array_key_first($entity) + 3] : ($formData['user_email'] ?? '')); ?>"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Password *</label>
                <input type="password" name="user_password" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Confirm Password *</label>
                <input type="password" name="user_password_confirm" required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <?php if (!$entity): ?>
                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="user_is_superadmin" value="1" class="mr-2">
                        <span class="text-gray-700 font-semibold">Super Admin</span>
                    </label>
                </div>
            <?php endif; ?>

            <div class="flex justify-end space-x-4 mt-6">
                <a href="<?php echo $entityType === 'staff' ? '/staff/index.php' : ($entityType === 'patient' ? '/patients/index.php' : ($entityType === 'doctor' ? '/doctors/index.php' : '/users/index.php')); ?>" 
                   class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition duration-300">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-300">
                    <i class="fas fa-save mr-2"></i>Create User
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
