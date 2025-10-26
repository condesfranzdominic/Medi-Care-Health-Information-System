<?php
$pageTitle = "Patient Dashboard - Medi-care Health Information System";
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../classes/Patient.php';
require_once __DIR__ . '/../classes/Appointment.php';
require_once __DIR__ . '/../classes/Payment.php';

$auth->requireLogin();
$db = Database::getInstance();

// Ensure user is a patient
if (!$auth->isPatient()) {
    redirect('/unauthorized.php');
}

// Initialize classes
$patient = new Patient();
$appointment = new Appointment();
$payment = new Payment();

// Get patient ID and data
$patientId = $auth->getPatientId();
$patientData = $patient->getById($patientId);

if (!$patientData) {
    setFlashMessage('error', 'Patient data not found');
    redirect('/logout.php');
}

// Dashboard stats
$stats = [];

// Upcoming Appointments
$stats['upcoming_appointments'] = $db->fetchOne("
    SELECT COUNT(*) as count 
    FROM appointments 
    WHERE pat_id = :pat_id 
    AND appointment_date >= CURRENT_DATE 
    AND status_id NOT IN (SELECT status_id FROM appointment_statuses WHERE status_name IN ('Cancelled', 'Completed'))", 
    ['pat_id' => $patientId]
)['count'];

// Total Appointments
$stats['total_appointments'] = $db->fetchOne("
    SELECT COUNT(*) as count 
    FROM appointments 
    WHERE pat_id = :pat_id", 
    ['pat_id' => $patientId]
)['count'];

// Recent appointments
$recentAppointments = $db->fetchAll("
    SELECT a.appointment_id, a.appointment_date, a.appointment_time,
           d.doc_first_name, d.doc_last_name,
           s.status_name, s.status_color,
           srv.service_name
    FROM appointments a
    JOIN doctors d ON a.doc_id = d.doc_id
    JOIN appointment_statuses s ON a.status_id = s.status_id
    LEFT JOIN services srv ON a.service_id = srv.service_id
    WHERE a.pat_id = :pat_id
    ORDER BY a.appointment_date DESC, a.appointment_time DESC
    LIMIT 5
", ['pat_id' => $patientId]);

// Recent payments
$recentPayments = $db->fetchAll("
    SELECT p.*, 
           ps.status_name as payment_status,
           srv.service_name,
           a.appointment_date
    FROM payments p
    JOIN appointments a ON p.appointment_id = a.appointment_id
    JOIN payment_statuses ps ON p.status_id = ps.status_id
    LEFT JOIN services srv ON a.service_id = srv.service_id
    WHERE a.pat_id = :pat_id
    ORDER BY p.payment_date DESC
    LIMIT 5
", ['pat_id' => $patientId]);
?>

<div class="container mx-auto px-4 py-8">
    <!-- Welcome Message -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-user-circle mr-2"></i>Welcome, <?php echo htmlspecialchars($patientData['pat_first_name']); ?>!
        </h1>
        <p class="text-gray-600 mt-2">Here's an overview of your medical information and appointments.</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Upcoming Appointments -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm uppercase">Upcoming Appointments</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2"><?php echo $stats['upcoming_appointments']; ?></p>
                </div>
                <div class="bg-blue-100 rounded-full p-4">
                    <i class="fas fa-calendar-alt text-3xl text-blue-600"></i>
                </div>
            </div>
            <a href="/views/appointments/" class="text-blue-600 text-sm hover:underline mt-4 inline-block">
                View All Appointments →
            </a>
        </div>

        <!-- Total Appointments -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm uppercase">Total Appointments</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2"><?php echo $stats['total_appointments']; ?></p>
                </div>
                <div class="bg-green-100 rounded-full p-4">
                    <i class="fas fa-history text-3xl text-green-600"></i>
                </div>
            </div>
        </div>

        <!-- Patient Information -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-gray-500 text-sm uppercase">Patient Information</p>
                    <p class="text-lg font-semibold mt-1">ID: <?php echo htmlspecialchars($patientData['pat_id']); ?></p>
                </div>
                <div class="bg-purple-100 rounded-full p-4">
                    <i class="fas fa-id-card text-3xl text-purple-600"></i>
                </div>
            </div>
            <a href="/views/patients/profile.php" class="text-blue-600 text-sm hover:underline">
                View Profile Details →
            </a>
        </div>
    </div>

    <!-- Recent Activity Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Appointments -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-clock mr-2"></i>Recent Appointments
            </h2>
            <?php if (empty($recentAppointments)): ?>
                <p class="text-gray-500 text-center py-4">No recent appointments found.</p>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date & Time</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Doctor</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Service</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($recentAppointments as $apt): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm">
                                        <?php echo formatDate($apt['appointment_date']) . ' ' . 
                                             date('h:i A', strtotime($apt['appointment_time'])); ?>
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        Dr. <?php echo htmlspecialchars($apt['doc_first_name'] . ' ' . $apt['doc_last_name']); ?>
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        <?php echo htmlspecialchars($apt['service_name'] ?? 'N/A'); ?>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 text-xs rounded-full <?php echo getStatusColor($apt['status_name']); ?>">
                                            <?php echo htmlspecialchars($apt['status_name']); ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <!-- Recent Payments -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                <i class="fas fa-file-invoice-dollar mr-2"></i>Recent Payments
            </h2>
            <?php if (empty($recentPayments)): ?>
                <p class="text-gray-500 text-center py-4">No recent payments found.</p>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Service</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($recentPayments as $payment): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm">
                                        <?php echo formatDate($payment['payment_date']); ?>
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        <?php echo htmlspecialchars($payment['service_name'] ?? 'N/A'); ?>
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        <?php echo formatCurrency($payment['amount']); ?>
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        <span class="px-2 py-1 text-xs rounded-full <?php echo getStatusColor($payment['payment_status']); ?>">
                                            <?php echo htmlspecialchars($payment['payment_status']); ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
