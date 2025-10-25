<?php
$pageTitle = "My Profile - Medi-Care Health Information System";
require_once __DIR__ . '/../includes/header.php';

$auth->requireRole(['patient']);
$db = Database::getInstance();

$patientId = $auth->getPatientId();
$patient = $db->fetchOne("SELECT * FROM patients WHERE pat_id = :id", ['id' => $patientId]);

if (!$patient) {
    setFlashMessage('error', 'Patient profile not found.');
    redirect('/dashboard.php');
}
?>

<div class="container mx-auto max-w-4xl">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">
        <i class="fas fa-user mr-2"></i>My Profile
    </h1>

    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Personal Information</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-gray-500 text-sm font-semibold mb-1">First Name</label>
                <p class="text-gray-900 text-lg"><?php echo htmlspecialchars($patient['pat_first_name']); ?></p>
            </div>
            <div>
                <label class="block text-gray-500 text-sm font-semibold mb-1">Last Name</label>
                <p class="text-gray-900 text-lg"><?php echo htmlspecialchars($patient['pat_last_name']); ?></p>
            </div>
            <div>
                <label class="block text-gray-500 text-sm font-semibold mb-1">Email</label>
                <p class="text-gray-900 text-lg"><?php echo htmlspecialchars($patient['pat_email']); ?></p>
            </div>
            <div>
                <label class="block text-gray-500 text-sm font-semibold mb-1">Phone</label>
                <p class="text-gray-900 text-lg"><?php echo htmlspecialchars($patient['pat_phone'] ?? 'Not provided'); ?></p>
            </div>
            <div>
                <label class="block text-gray-500 text-sm font-semibold mb-1">Date of Birth</label>
                <p class="text-gray-900 text-lg"><?php echo $patient['pat_date_of_birth'] ? formatDate($patient['pat_date_of_birth'], 'M d, Y') : 'Not provided'; ?></p>
            </div>
            <div>
                <label class="block text-gray-500 text-sm font-semibold mb-1">Gender</label>
                <p class="text-gray-900 text-lg"><?php echo htmlspecialchars(ucfirst($patient['pat_gender'] ?? 'Not specified')); ?></p>
            </div>
            <div class="md:col-span-2">
                <label class="block text-gray-500 text-sm font-semibold mb-1">Address</label>
                <p class="text-gray-900 text-lg"><?php echo htmlspecialchars($patient['pat_address'] ?? 'Not provided'); ?></p>
            </div>
        </div>

        <h2 class="text-xl font-bold text-gray-800 mb-4 mt-6">Emergency Contact</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-gray-500 text-sm font-semibold mb-1">Contact Name</label>
                <p class="text-gray-900 text-lg"><?php echo htmlspecialchars($patient['pat_emergency_contact'] ?? 'Not provided'); ?></p>
            </div>
            <div>
                <label class="block text-gray-500 text-sm font-semibold mb-1">Contact Phone</label>
                <p class="text-gray-900 text-lg"><?php echo htmlspecialchars($patient['pat_emergency_phone'] ?? 'Not provided'); ?></p>
            </div>
        </div>

        <h2 class="text-xl font-bold text-gray-800 mb-4 mt-6">Medical Information</h2>
        <div class="grid grid-cols-1 gap-6 mb-6">
            <div>
                <label class="block text-gray-500 text-sm font-semibold mb-1">Medical History</label>
                <p class="text-gray-900"><?php echo nl2br(htmlspecialchars($patient['pat_medical_history'] ?? 'No medical history recorded')); ?></p>
            </div>
            <div>
                <label class="block text-gray-500 text-sm font-semibold mb-1">Allergies</label>
                <p class="text-gray-900"><?php echo nl2br(htmlspecialchars($patient['pat_allergies'] ?? 'No known allergies')); ?></p>
            </div>
        </div>

        <h2 class="text-xl font-bold text-gray-800 mb-4 mt-6">Insurance Information</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-gray-500 text-sm font-semibold mb-1">Insurance Provider</label>
                <p class="text-gray-900 text-lg"><?php echo htmlspecialchars($patient['pat_insurance_provider'] ?? 'Not provided'); ?></p>
            </div>
            <div>
                <label class="block text-gray-500 text-sm font-semibold mb-1">Insurance Number</label>
                <p class="text-gray-900 text-lg"><?php echo htmlspecialchars($patient['pat_insurance_number'] ?? 'Not provided'); ?></p>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
