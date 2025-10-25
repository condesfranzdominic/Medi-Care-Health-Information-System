<?php
$pageTitle = "View Patient - Medi-Care Health Information System";
require_once __DIR__ . '/../includes/header.php';

$auth->requireRole(['superadmin', 'staff']);
$db = Database::getInstance();

$patientId = intval($_GET['id'] ?? 0);
$patient = $db->fetchOne("SELECT * FROM patients WHERE pat_id = :id", ['id' => $patientId]);

if (!$patient) {
    setFlashMessage('error', 'Patient not found.');
    redirect('/patients/index.php');
}
?>

<div class="container mx-auto max-w-4xl">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-user-injured mr-2"></i>Patient Details
        </h1>
        <div class="space-x-2">
            <a href="/patients/edit.php?id=<?php echo $patientId; ?>" 
               class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 transition duration-300">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
            <a href="/patients/index.php" 
               class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition duration-300">
                <i class="fas fa-arrow-left mr-2"></i>Back
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-gray-500 text-sm font-semibold mb-1">Patient ID</label>
                <p class="text-gray-900 text-lg"><?php echo htmlspecialchars($patient['pat_id']); ?></p>
            </div>
            <div>
                <label class="block text-gray-500 text-sm font-semibold mb-1">Full Name</label>
                <p class="text-gray-900 text-lg"><?php echo htmlspecialchars($patient['pat_first_name'] . ' ' . $patient['pat_last_name']); ?></p>
            </div>
            <div>
                <label class="block text-gray-500 text-sm font-semibold mb-1">Email</label>
                <p class="text-gray-900 text-lg"><?php echo htmlspecialchars($patient['pat_email']); ?></p>
            </div>
            <div>
                <label class="block text-gray-500 text-sm font-semibold mb-1">Phone</label>
                <p class="text-gray-900 text-lg"><?php echo htmlspecialchars($patient['pat_phone'] ?? 'N/A'); ?></p>
            </div>
            <div>
                <label class="block text-gray-500 text-sm font-semibold mb-1">Date of Birth</label>
                <p class="text-gray-900 text-lg"><?php echo $patient['pat_date_of_birth'] ? formatDate($patient['pat_date_of_birth'], 'M d, Y') : 'N/A'; ?></p>
            </div>
            <div>
                <label class="block text-gray-500 text-sm font-semibold mb-1">Gender</label>
                <p class="text-gray-900 text-lg"><?php echo htmlspecialchars(ucfirst($patient['pat_gender'] ?? 'N/A')); ?></p>
            </div>
            <div class="md:col-span-2">
                <label class="block text-gray-500 text-sm font-semibold mb-1">Address</label>
                <p class="text-gray-900 text-lg"><?php echo htmlspecialchars($patient['pat_address'] ?? 'N/A'); ?></p>
            </div>
            <div>
                <label class="block text-gray-500 text-sm font-semibold mb-1">Emergency Contact</label>
                <p class="text-gray-900 text-lg"><?php echo htmlspecialchars($patient['pat_emergency_contact'] ?? 'N/A'); ?></p>
            </div>
            <div>
                <label class="block text-gray-500 text-sm font-semibold mb-1">Emergency Phone</label>
                <p class="text-gray-900 text-lg"><?php echo htmlspecialchars($patient['pat_emergency_phone'] ?? 'N/A'); ?></p>
            </div>
            <div class="md:col-span-2">
                <label class="block text-gray-500 text-sm font-semibold mb-1">Medical History</label>
                <p class="text-gray-900 text-lg"><?php echo htmlspecialchars($patient['pat_medical_history'] ?? 'N/A'); ?></p>
            </div>
            <div class="md:col-span-2">
                <label class="block text-gray-500 text-sm font-semibold mb-1">Allergies</label>
                <p class="text-gray-900 text-lg"><?php echo htmlspecialchars($patient['pat_allergies'] ?? 'N/A'); ?></p>
            </div>
            <div>
                <label class="block text-gray-500 text-sm font-semibold mb-1">Insurance Provider</label>
                <p class="text-gray-900 text-lg"><?php echo htmlspecialchars($patient['pat_insurance_provider'] ?? 'N/A'); ?></p>
            </div>
            <div>
                <label class="block text-gray-500 text-sm font-semibold mb-1">Insurance Number</label>
                <p class="text-gray-900 text-lg"><?php echo htmlspecialchars($patient['pat_insurance_number'] ?? 'N/A'); ?></p>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
