<?php
$pageTitle = "Create Appointment - Medi-Care Health Information System";
require_once __DIR__ . '/../../includes/header.php';

$auth->requireRole(['superadmin', 'staff']);
require_once __DIR__ . '/../../classes/Appointment.php';
require_once __DIR__ . '/../../classes/Patient.php';
require_once __DIR__ . '/../../classes/Doctor.php';
require_once __DIR__ . '/../../classes/Service.php';

$appointment = new Appointment();
$patient = new Patient();
$doctor = new Doctor();
$service = new Service();
$db = Database::getInstance();

$errors = [];
$formData = [];

// Fetch patients, doctors, services, and statuses
$patients = $patient->getAll();
$doctors = $doctor->getAll();
$services = $service->getAll();
$statuses = $db->fetchAll("SELECT status_id, status_name FROM appointment_statuses ORDER BY status_id");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData = [
        'pat_id' => intval($_POST['pat_id'] ?? 0),
        'doc_id' => intval($_POST['doc_id'] ?? 0),
        'service_id' => intval($_POST['service_id'] ?? 0),
        'status_id' => intval($_POST['status_id'] ?? 0),
        'appointment_date' => sanitize($_POST['appointment_date'] ?? ''),
        'appointment_time' => sanitize($_POST['appointment_time'] ?? ''),
        'appointment_notes' => sanitize($_POST['appointment_notes'] ?? ''),
        'appointment_duration' => intval($_POST['appointment_duration'] ?? 30)
    ];
    
    if ($formData['pat_id'] <= 0) $errors[] = 'Please select a patient.';
    if ($formData['doc_id'] <= 0) $errors[] = 'Please select a doctor.';
    if ($formData['status_id'] <= 0) $errors[] = 'Please select a status.';
    if (empty($formData['appointment_date'])) $errors[] = 'Appointment date is required.';
    if (empty($formData['appointment_time'])) $errors[] = 'Appointment time is required.';
    
    if (empty($errors)) {
        $result = $appointment->create($formData);
        
        if ($result['success']) {
            setFlashMessage('success', "Appointment created successfully. ID: " . $result['id']);
            redirect('/views/appointments/view.php?id=' . urlencode($result['id']));
        } else {
            $errors = $result['errors'];
        }
    }
}
?>

<div class="container mx-auto max-w-3xl">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">
        <i class="fas fa-calendar-plus mr-2"></i>Create Appointment
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
                    <label class="block text-gray-700 font-semibold mb-2">Patient *</label>
                    <select name="pat_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Patient</option>
                        <?php foreach ($patients as $patient): ?>
                            <option value="<?php echo $patient['pat_id']; ?>" 
                                    <?php echo ($formData['pat_id'] ?? 0) == $patient['pat_id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($patient['pat_first_name'] . ' ' . $patient['pat_last_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Doctor *</label>
                    <select name="doc_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Doctor</option>
                        <?php foreach ($doctors as $doctor): ?>
                            <option value="<?php echo $doctor['doc_id']; ?>"
                                    <?php echo ($formData['doc_id'] ?? 0) == $doctor['doc_id'] ? 'selected' : ''; ?>>
                                Dr. <?php echo htmlspecialchars($doctor['doc_first_name'] . ' ' . $doctor['doc_last_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Service</label>
                    <select name="service_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Service</option>
                        <?php foreach ($services as $service): ?>
                            <option value="<?php echo $service['service_id']; ?>"
                                    <?php echo ($formData['service_id'] ?? 0) == $service['service_id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($service['service_name']) . ' - ' . formatCurrency($service['service_price']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Status *</label>
                    <select name="status_id" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Status</option>
                        <?php foreach ($statuses as $status): ?>
                            <option value="<?php echo $status['status_id']; ?>"
                                    <?php echo ($formData['status_id'] ?? 1) == $status['status_id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($status['status_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Appointment Date *</label>
                    <input type="date" name="appointment_date" required
                           value="<?php echo htmlspecialchars($formData['appointment_date'] ?? ''); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Appointment Time *</label>
                    <input type="time" name="appointment_time" required
                           value="<?php echo htmlspecialchars($formData['appointment_time'] ?? ''); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Duration (minutes)</label>
                    <input type="number" name="appointment_duration" min="15" step="15"
                           value="<?php echo htmlspecialchars($formData['appointment_duration'] ?? 30); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-gray-700 font-semibold mb-2">Notes</label>
                    <textarea name="appointment_notes" rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"><?php echo htmlspecialchars($formData['appointment_notes'] ?? ''); ?></textarea>
                </div>
            </div>

            <div class="flex justify-end space-x-4 mt-6">
                <a href="/appointments/index.php" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition duration-300">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-300">
                    <i class="fas fa-save mr-2"></i>Create Appointment
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
