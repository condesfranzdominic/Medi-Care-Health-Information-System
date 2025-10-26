<?php
$pageTitle = "My Appointments - Medi-Care Health Information System";
require_once __DIR__ . '/../../includes/header.php';

$auth->requireRole(['patient']);
$db = Database::getInstance();

$patientId = $auth->getPatientId();

$appointments = $db->fetchAll("
    SELECT a.*, 
           d.doc_first_name, d.doc_last_name, d.doc_phone,
           s.status_name, s.status_color,
           srv.service_name, srv.service_price
    FROM appointments a
    JOIN doctors d ON a.doc_id = d.doc_id
    JOIN appointment_statuses s ON a.status_id = s.status_id
    LEFT JOIN services srv ON a.service_id = srv.service_id
    WHERE a.pat_id = :pat_id
    ORDER BY a.appointment_date DESC, a.appointment_time DESC
", ['pat_id' => $patientId]);
?>

<div class="container mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-calendar-alt mr-2"></i>My Appointments
        </h1>
        <a href="/patient/book-appointment.php" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-300">
            <i class="fas fa-plus mr-2"></i>Book New Appointment
        </a>
    </div>

    <div class="space-y-4">
        <?php if (empty($appointments)): ?>
            <div class="bg-white rounded-lg shadow-md p-8 text-center text-gray-500">
                <i class="fas fa-calendar-times text-6xl mb-4"></i>
                <p class="mb-4">You don't have any appointments yet.</p>
                <a href="/patient/book-appointment.php" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition duration-300">
                    <i class="fas fa-calendar-plus mr-2"></i>Book Your First Appointment
                </a>
            </div>
        <?php else: ?>
            <?php foreach ($appointments as $appointment): ?>
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition duration-300">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center mb-2">
                                <h3 class="text-xl font-bold text-gray-800">
                                    Dr. <?php echo htmlspecialchars($appointment['doc_first_name'] . ' ' . $appointment['doc_last_name']); ?>
                                </h3>
                                <span class="ml-4 px-3 py-1 text-sm font-semibold rounded-full text-white" 
                                      style="background-color: <?php echo htmlspecialchars($appointment['status_color']); ?>">
                                    <?php echo htmlspecialchars($appointment['status_name']); ?>
                                </span>
                            </div>
                            <div class="space-y-1 text-gray-600">
                                <p><i class="fas fa-hashtag w-5"></i> <strong>ID:</strong> <?php echo htmlspecialchars($appointment['appointment_id']); ?></p>
                                <p><i class="fas fa-calendar w-5"></i> <?php echo formatDate($appointment['appointment_date'], 'l, F d, Y'); ?></p>
                                <p><i class="fas fa-clock w-5"></i> <?php echo date('h:i A', strtotime($appointment['appointment_time'])); ?></p>
                                <p><i class="fas fa-concierge-bell w-5"></i> <?php echo htmlspecialchars($appointment['service_name'] ?? 'N/A'); ?></p>
                                <?php if ($appointment['service_price']): ?>
                                    <p><i class="fas fa-money-bill-wave w-5"></i> <?php echo formatCurrency($appointment['service_price']); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="ml-4">
                            <a href="/appointments/view.php?id=<?php echo urlencode($appointment['appointment_id']); ?>" 
                               class="block text-center bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition duration-300">
                                <i class="fas fa-eye mr-1"></i>View Details
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
