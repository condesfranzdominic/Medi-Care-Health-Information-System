<?php
$pageTitle = "Dashboard - Medi-care Health Information System";
require_once __DIR__ . '/includes/header.php';

$auth->requireLogin();
$db = Database::getInstance();
$role = $auth->getRole();

// Redirect based on role
if ($role === 'doctor') {
    redirect('/doctor/appointments.php?filter=today');
} elseif ($role === 'patient') {
    redirect('/patient/appointments.php');
}

// Dashboard stats for Super Admin and Staff
$stats = [];

if ($role === 'superadmin' || $role === 'staff') {
    // Total Patients
    $result = $db->fetchOne("SELECT COUNT(*) as count FROM patients");
    $stats['patients'] = $result['count'];
    
    // Total Doctors
    $result = $db->fetchOne("SELECT COUNT(*) as count FROM doctors WHERE doc_status = 'active'");
    $stats['doctors'] = $result['count'];
    
    // Today's Appointments
    $result = $db->fetchOne("SELECT COUNT(*) as count FROM appointments WHERE appointment_date = :date", 
                           ['date' => getCurrentDate()]);
    $stats['today_appointments'] = $result['count'];
    
    // Pending Payments
    $result = $db->fetchOne("SELECT COUNT(*) as count FROM payments p 
                            JOIN payment_statuses ps ON p.payment_status_id = ps.payment_status_id 
                            WHERE ps.status_name = 'Pending'");
    $stats['pending_payments'] = $result['count'];
    
    // Recent appointments
    $recentAppointments = $db->fetchAll("
        SELECT a.appointment_id, a.appointment_date, a.appointment_time,
               p.pat_first_name, p.pat_last_name,
               d.doc_first_name, d.doc_last_name,
               s.status_name, s.status_color,
               srv.service_name
        FROM appointments a
        JOIN patients p ON a.pat_id = p.pat_id
        JOIN doctors d ON a.doc_id = d.doc_id
        JOIN appointment_statuses s ON a.status_id = s.status_id
        LEFT JOIN services srv ON a.service_id = srv.service_id
        ORDER BY a.appointment_date DESC, a.appointment_time DESC
        LIMIT 10
    ");
}
?>

<div class="container mx-auto">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">
        <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
    </h1>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Patients -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm uppercase">Total Patients</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2"><?php echo $stats['patients']; ?></p>
                </div>
                <div class="bg-blue-100 rounded-full p-4">
                    <i class="fas fa-user-injured text-3xl text-blue-600"></i>
                </div>
            </div>
        </div>

        <!-- Total Doctors -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm uppercase">Active Doctors</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2"><?php echo $stats['doctors']; ?></p>
                </div>
                <div class="bg-green-100 rounded-full p-4">
                    <i class="fas fa-user-md text-3xl text-green-600"></i>
                </div>
            </div>
        </div>

        <!-- Today's Appointments -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm uppercase">Today's Appointments</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2"><?php echo $stats['today_appointments']; ?></p>
                </div>
                <div class="bg-purple-100 rounded-full p-4">
                    <i class="fas fa-calendar-check text-3xl text-purple-600"></i>
                </div>
            </div>
        </div>

        <!-- Pending Payments -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm uppercase">Pending Payments</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2"><?php echo $stats['pending_payments']; ?></p>
                </div>
                <div class="bg-yellow-100 rounded-full p-4">
                    <i class="fas fa-money-bill-wave text-3xl text-yellow-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Appointments -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">
            <i class="fas fa-clock mr-2"></i>Recent Appointments
        </h2>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Appointment ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Doctor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Service</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date & Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (empty($recentAppointments)): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">No appointments found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($recentAppointments as $appointment): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <?php echo htmlspecialchars($appointment['appointment_id']); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo htmlspecialchars($appointment['pat_first_name'] . ' ' . $appointment['pat_last_name']); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    Dr. <?php echo htmlspecialchars($appointment['doc_first_name'] . ' ' . $appointment['doc_last_name']); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo htmlspecialchars($appointment['service_name'] ?? 'N/A'); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php echo formatDate($appointment['appointment_date'], 'M d, Y') . ' ' . date('h:i A', strtotime($appointment['appointment_time'])); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full text-white" 
                                          style="background-color: <?php echo htmlspecialchars($appointment['status_color']); ?>">
                                        <?php echo htmlspecialchars($appointment['status_name']); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
