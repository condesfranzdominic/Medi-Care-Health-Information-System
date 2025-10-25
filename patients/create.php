<?php
$pageTitle = "Add New Patient - Medi-Care Health Information System";
require_once __DIR__ . '/../includes/header.php';

$auth->requireRole(['superadmin', 'staff']);
$db = Database::getInstance();

$errors = [];
$formData = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData = [
        'pat_first_name' => sanitize($_POST['pat_first_name'] ?? ''),
        'pat_last_name' => sanitize($_POST['pat_last_name'] ?? ''),
        'pat_email' => sanitize($_POST['pat_email'] ?? ''),
        'pat_phone' => sanitize($_POST['pat_phone'] ?? ''),
        'pat_date_of_birth' => sanitize($_POST['pat_date_of_birth'] ?? ''),
        'pat_gender' => sanitize($_POST['pat_gender'] ?? ''),
        'pat_address' => sanitize($_POST['pat_address'] ?? ''),
        'pat_emergency_contact' => sanitize($_POST['pat_emergency_contact'] ?? ''),
        'pat_emergency_phone' => sanitize($_POST['pat_emergency_phone'] ?? ''),
        'pat_medical_history' => sanitize($_POST['pat_medical_history'] ?? ''),
        'pat_allergies' => sanitize($_POST['pat_allergies'] ?? ''),
        'pat_insurance_provider' => sanitize($_POST['pat_insurance_provider'] ?? ''),
        'pat_insurance_number' => sanitize($_POST['pat_insurance_number'] ?? '')
    ];
    
    if (empty($formData['pat_first_name'])) $errors[] = 'First name is required.';
    if (empty($formData['pat_last_name'])) $errors[] = 'Last name is required.';
    if (empty($formData['pat_email'])) {
        $errors[] = 'Email is required.';
    } elseif (!isValidEmail($formData['pat_email'])) {
        $errors[] = 'Invalid email format.';
    }
    
    if (empty($errors)) {
        $existing = $db->fetchOne("SELECT pat_id FROM patients WHERE pat_email = :email", 
                                  ['email' => $formData['pat_email']]);
        if ($existing) {
            $errors[] = 'Email already exists.';
        }
    }
    
    if (empty($errors)) {
        try {
            $sql = "INSERT INTO patients (pat_first_name, pat_last_name, pat_email, pat_phone, 
                    pat_date_of_birth, pat_gender, pat_address, pat_emergency_contact, 
                    pat_emergency_phone, pat_medical_history, pat_allergies, 
                    pat_insurance_provider, pat_insurance_number) 
                    VALUES (:first_name, :last_name, :email, :phone, :dob, :gender, :address, 
                    :emergency_contact, :emergency_phone, :medical_history, :allergies, 
                    :insurance_provider, :insurance_number)";
            
            $db->execute($sql, [
                'first_name' => $formData['pat_first_name'],
                'last_name' => $formData['pat_last_name'],
                'email' => $formData['pat_email'],
                'phone' => $formData['pat_phone'] ?: null,
                'dob' => $formData['pat_date_of_birth'] ?: null,
                'gender' => $formData['pat_gender'] ?: null,
                'address' => $formData['pat_address'] ?: null,
                'emergency_contact' => $formData['pat_emergency_contact'] ?: null,
                'emergency_phone' => $formData['pat_emergency_phone'] ?: null,
                'medical_history' => $formData['pat_medical_history'] ?: null,
                'allergies' => $formData['pat_allergies'] ?: null,
                'insurance_provider' => $formData['pat_insurance_provider'] ?: null,
                'insurance_number' => $formData['pat_insurance_number'] ?: null
            ]);
            
            $patientId = $db->lastInsertId();
            setFlashMessage('success', 'Patient added successfully.');
            redirect("/users/create.php?patient_id=$patientId");
        } catch (Exception $e) {
            $errors[] = 'Failed to add patient: ' . $e->getMessage();
        }
    }
}
?>

<div class="container mx-auto max-w-4xl">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">
        <i class="fas fa-user-plus mr-2"></i>Add New Patient
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
            <h2 class="text-xl font-bold text-gray-800 mb-4">Personal Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">First Name *</label>
                    <input type="text" name="pat_first_name" required
                           value="<?php echo htmlspecialchars($formData['pat_first_name'] ?? ''); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Last Name *</label>
                    <input type="text" name="pat_last_name" required
                           value="<?php echo htmlspecialchars($formData['pat_last_name'] ?? ''); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Email *</label>
                    <input type="email" name="pat_email" required
                           value="<?php echo htmlspecialchars($formData['pat_email'] ?? ''); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Phone</label>
                    <input type="text" name="pat_phone"
                           value="<?php echo htmlspecialchars($formData['pat_phone'] ?? ''); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Date of Birth</label>
                    <input type="date" name="pat_date_of_birth"
                           value="<?php echo htmlspecialchars($formData['pat_date_of_birth'] ?? ''); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Gender</label>
                    <select name="pat_gender"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Gender</option>
                        <option value="male" <?php echo ($formData['pat_gender'] ?? '') === 'male' ? 'selected' : ''; ?>>Male</option>
                        <option value="female" <?php echo ($formData['pat_gender'] ?? '') === 'female' ? 'selected' : ''; ?>>Female</option>
                        <option value="other" <?php echo ($formData['pat_gender'] ?? '') === 'other' ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-gray-700 font-semibold mb-2">Address</label>
                    <textarea name="pat_address" rows="2"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"><?php echo htmlspecialchars($formData['pat_address'] ?? ''); ?></textarea>
                </div>
            </div>

            <h2 class="text-xl font-bold text-gray-800 mb-4">Emergency Contact</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Emergency Contact Name</label>
                    <input type="text" name="pat_emergency_contact"
                           value="<?php echo htmlspecialchars($formData['pat_emergency_contact'] ?? ''); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Emergency Contact Phone</label>
                    <input type="text" name="pat_emergency_phone"
                           value="<?php echo htmlspecialchars($formData['pat_emergency_phone'] ?? ''); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <h2 class="text-xl font-bold text-gray-800 mb-4">Medical Information</h2>
            <div class="grid grid-cols-1 gap-6 mb-6">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Medical History</label>
                    <textarea name="pat_medical_history" rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"><?php echo htmlspecialchars($formData['pat_medical_history'] ?? ''); ?></textarea>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Allergies</label>
                    <textarea name="pat_allergies" rows="2"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"><?php echo htmlspecialchars($formData['pat_allergies'] ?? ''); ?></textarea>
                </div>
            </div>

            <h2 class="text-xl font-bold text-gray-800 mb-4">Insurance Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Insurance Provider</label>
                    <input type="text" name="pat_insurance_provider"
                           value="<?php echo htmlspecialchars($formData['pat_insurance_provider'] ?? ''); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Insurance Number</label>
                    <input type="text" name="pat_insurance_number"
                           value="<?php echo htmlspecialchars($formData['pat_insurance_number'] ?? ''); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="flex justify-end space-x-4">
                <a href="/patients/index.php" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition duration-300">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-300">
                    <i class="fas fa-save mr-2"></i>Save Patient
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
