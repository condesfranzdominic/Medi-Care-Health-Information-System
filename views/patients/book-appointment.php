<?php
$pageTitle = "Book Appointment - Medi-Care Health Information System";
require_once __DIR__ . '/../../includes/header.php';

$auth->requireRole(['patient']);
$db = Database::getInstance();

$errors = [];
$formData = [];
$patientId = $auth->getPatientId();

// Fetch doctors, services, and statuses
$doctors = $db->fetchAll("SELECT d.*, s.spec_name FROM doctors d 
                          LEFT JOIN specializations s ON d.doc_specialization_id = s.spec_id 
                          WHERE d.doc_status = 'active' ORDER BY d.doc_first_name, d.doc_last_name");
$services = $db->fetchAll("SELECT service_id, service_name, service_price FROM services ORDER BY service_name");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData = [
        'doc_id' => intval($_POST['doc_id'] ?? 0),
        'service_id' => intval($_POST['service_id'] ?? 0),
        'appointment_date' => sanitize($_POST['appointment_date'] ?? ''),
        'appointment_time' => sanitize($_POST['appointment_time'] ?? ''),
        'appointment_notes' => sanitize($_POST['appointment_notes'] ?? '')
    ];
    
    if ($formData['doc_id'] <= 0) $errors[] = 'Please select a doctor.';
    if (empty($formData['appointment_date'])) $errors[] = 'Appointment date is required.';
    if (empty($formData['appointment_time'])) $errors[] = 'Appointment time is required.';
    
    if (empty($errors)) {
        try {
            $appointmentId = generateAppointmentId($db);
            $scheduledStatus = $db->fetchOne("SELECT status_id FROM appointment_statuses WHERE status_name = 'Scheduled'");
            
            $sql = "INSERT INTO appointments (appointment_id, pat_id, doc_id, service_id, status_id, 
                    appointment_date, appointment_time, appointment_notes) 
                    VALUES (:appointment_id, :pat_id, :doc_id, :service_id, :status_id, 
                    :appointment_date, :appointment_time, :notes)";
            
            $db->execute($sql, [
                'appointment_id' => $appointmentId,
                'pat_id' => $patientId,
                'doc_id' => $formData['doc_id'],
                'service_id' => $formData['service_id'] ?: null,
                'status_id' => $scheduledStatus['status_id'],
                'appointment_date' => $formData['appointment_date'],
                'appointment_time' => $formData['appointment_time'],
                'notes' => $formData['appointment_notes'] ?: null
            ]);
            
            setFlashMessage('success', "Appointment booked successfully! Your appointment ID is: $appointmentId");
            redirect('/patient/appointments.php');
        } catch (Exception $e) {
            $errors[] = 'Failed to book appointment: ' . $e->getMessage();
        }
    }
}
?>

<div class="container mx-auto max-w-3xl">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">
        <i class="fas fa-calendar-plus mr-2"></i>Book New Appointment
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
            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">Select Doctor *</label>
                <select name="doc_id" required id="doctor-select"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Choose a doctor</option>
                    <?php foreach ($doctors as $doctor): ?>
                        <option value="<?php echo $doctor['doc_id']; ?>"
                                <?php echo ($formData['doc_id'] ?? 0) == $doctor['doc_id'] ? 'selected' : ''; ?>>
                            Dr. <?php echo htmlspecialchars($doctor['doc_first_name'] . ' ' . $doctor['doc_last_name']); ?>
                            <?php if ($doctor['spec_name']): ?>
                                - <?php echo htmlspecialchars($doctor['spec_name']); ?>
                            <?php endif; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">Select Service (Optional)</label>
                <select name="service_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">No specific service</option>
                    <?php foreach ($services as $service): ?>
                        <option value="<?php echo $service['service_id']; ?>"
                                <?php echo ($formData['service_id'] ?? 0) == $service['service_id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($service['service_name']) . ' - ' . formatCurrency($service['service_price']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Appointment Date *</label>
                    <input type="date" name="appointment_date" required
                           min="<?php echo date('Y-m-d'); ?>"
                           value="<?php echo htmlspecialchars($formData['appointment_date'] ?? ''); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Appointment Time *</label>
                    <input type="time" name="appointment_time" required
                           value="<?php echo htmlspecialchars($formData['appointment_time'] ?? ''); ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2">Notes (Optional)</label>
                <textarea name="appointment_notes" rows="4" 
                          placeholder="Please describe your symptoms or reason for visit..."
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"><?php echo htmlspecialchars($formData['appointment_notes'] ?? ''); ?></textarea>
            </div>

            <div class="flex justify-end space-x-4">
                <a href="/patient/appointments.php" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition duration-300">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-300">
                    <i class="fas fa-check mr-2"></i>Book Appointment
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
