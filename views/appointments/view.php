<?php
$pageTitle = "View Appointment - Medi-Care Health Information System";
require_once __DIR__ . '/../../includes/header.php';

$auth->requireRole(['superadmin', 'staff', 'doctor', 'patient']);
$db = Database::getInstance();

$appointmentId = sanitize($_GET['id'] ?? '');

$appointment = $db->fetchOne("
    SELECT a.*, 
           p.pat_first_name, p.pat_last_name, p.pat_email, p.pat_phone,
           d.doc_first_name, d.doc_last_name, d.doc_email,
           s.status_name, s.status_color,
           srv.service_name, srv.service_price
    FROM appointments a
    JOIN patients p ON a.pat_id = p.pat_id
    JOIN doctors d ON a.doc_id = d.doc_id
    JOIN appointment_statuses s ON a.status_id = s.status_id
    LEFT JOIN services srv ON a.service_id = srv.service_id
    WHERE a.appointment_id = :id
", ['id' => $appointmentId]);

if (!$appointment) {
    setFlashMessage('error', 'Appointment not found.');
    redirect('/appointments/index.php');
}

// Check access for doctors and patients
if ($auth->isDoctor() && $appointment['doc_id'] != $auth->getDoctorId()) {
    redirect('/unauthorized.php');
}
if ($auth->isPatient() && $appointment['pat_id'] != $auth->getPatientId()) {
    redirect('/unauthorized.php');
}
?>

<div class="container mx-auto max-w-4xl">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-calendar-check mr-2"></i>Appointment Details
        </h1>
        <div class="space-x-2">
            <?php if ($auth->isSuperAdmin() || $auth->isStaff()): ?>
                <a href="/appointments/edit.php?id=<?php echo urlencode($appointmentId); ?>" 
                   class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 transition duration-300">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>
            <?php endif; ?>
            <a href="<?php echo $auth->isPatient() ? '/patient/appointments.php' : '/appointments/index.php'; ?>" 
               class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition duration-300">
                <i class="fas fa-arrow-left mr-2"></i>Back
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="block text-gray-500 text-sm font-semibold mb-1">Appointment ID</label>
                <p class="text-2xl font-bold text-blue-600"><?php echo htmlspecialchars($appointment['appointment_id']); ?></p>
            </div>

            <div>
                <label class="block text-gray-500 text-sm font-semibold mb-1">Patient Name</label>
                <p class="text-gray-900 text-lg"><?php echo htmlspecialchars($appointment['pat_first_name'] . ' ' . $appointment['pat_last_name']); ?></p>
            </div>

            <div>
                <label class="block text-gray-500 text-sm font-semibold mb-1">Patient Email</label>
                <p class="text-gray-900 text-lg"><?php echo htmlspecialchars($appointment['pat_email']); ?></p>
            </div>

            <div>
                <label class="block text-gray-500 text-sm font-semibold mb-1">Patient Phone</label>
                <p class="text-gray-900 text-lg"><?php echo htmlspecialchars($appointment['pat_phone'] ?? 'N/A'); ?></p>
            </div>

            <div>
                <label class="block text-gray-500 text-sm font-semibold mb-1">Doctor</label>
                <p class="text-gray-900 text-lg">Dr. <?php echo htmlspecialchars($appointment['doc_first_name'] . ' ' . $appointment['doc_last_name']); ?></p>
            </div>

            <div>
                <label class="block text-gray-500 text-sm font-semibold mb-1">Service</label>
                <p class="text-gray-900 text-lg"><?php echo htmlspecialchars($appointment['service_name'] ?? 'N/A'); ?></p>
            </div>

            <div>
                <label class="block text-gray-500 text-sm font-semibold mb-1">Service Price</label>
                <p class="text-gray-900 text-lg"><?php echo $appointment['service_price'] ? formatCurrency($appointment['service_price']) : 'N/A'; ?></p>
            </div>

            <div>
                <label class="block text-gray-500 text-sm font-semibold mb-1">Appointment Date</label>
                <p class="text-gray-900 text-lg"><?php echo formatDate($appointment['appointment_date'], 'l, F d, Y'); ?></p>
            </div>

            <div>
                <label class="block text-gray-500 text-sm font-semibold mb-1">Appointment Time</label>
                <p class="text-gray-900 text-lg"><?php echo date('h:i A', strtotime($appointment['appointment_time'])); ?></p>
            </div>

            <div>
                <label class="block text-gray-500 text-sm font-semibold mb-1">Duration</label>
                <p class="text-gray-900 text-lg"><?php echo $appointment['appointment_duration']; ?> minutes</p>
            </div>

            <div>
                <label class="block text-gray-500 text-sm font-semibold mb-1">Status</label>
                <span class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-full text-white" 
                      style="background-color: <?php echo htmlspecialchars($appointment['status_color']); ?>">
                    <?php echo htmlspecialchars($appointment['status_name']); ?>
                </span>
            </div>

            <?php if ($appointment['appointment_notes']): ?>
                <div class="md:col-span-2">
                    <label class="block text-gray-500 text-sm font-semibold mb-1">Notes</label>
                    <p class="text-gray-900 text-lg"><?php echo nl2br(htmlspecialchars($appointment['appointment_notes'])); ?></p>
                </div>
            <?php endif; ?>

            <div>
                <label class="block text-gray-500 text-sm font-semibold mb-1">Created At</label>
                <p class="text-gray-900"><?php echo formatDateTime($appointment['created_at'], 'M d, Y h:i A'); ?></p>
            </div>

            <div>
                <label class="block text-gray-500 text-sm font-semibold mb-1">Updated At</label>
                <p class="text-gray-900"><?php echo formatDateTime($appointment['updated_at'], 'M d, Y h:i A'); ?></p>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
