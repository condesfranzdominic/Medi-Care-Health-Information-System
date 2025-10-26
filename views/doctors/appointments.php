<?php
$pageTitle = "My Appointments - Medi-Care Health Information System";
require_once __DIR__ . '/../../includes/header.php';

$auth->requireRole(['doctor']);
$db = Database::getInstance();

$filter = sanitize($_GET['filter'] ?? 'today');
$doctorId = $auth->getDoctorId();

// Build query based on filter
$whereClause = "WHERE a.doc_id = :doc_id";
$params = ['doc_id' => $doctorId];

switch ($filter) {
    case 'today':
        $whereClause .= " AND a.appointment_date = :date";
        $params['date'] = getCurrentDate();
        break;
    case 'previous':
        $whereClause .= " AND a.appointment_date < :date";
        $params['date'] = getCurrentDate();
        break;
    case 'future':
        $whereClause .= " AND a.appointment_date > :date";
        $params['date'] = getCurrentDate();
        break;
}

$appointments = $db->fetchAll("
    SELECT a.*, 
           p.pat_first_name, p.pat_last_name, p.pat_phone, p.pat_email,
           s.status_name, s.status_color,
           srv.service_name
    FROM appointments a
    JOIN patients p ON a.pat_id = p.pat_id
    JOIN appointment_statuses s ON a.status_id = s.status_id
    LEFT JOIN services srv ON a.service_id = srv.service_id
    $whereClause
    ORDER BY a.appointment_date DESC, a.appointment_time DESC
", $params);
?>

<div class="container mx-auto">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">
        <i class="fas fa-calendar-check mr-2"></i>My Appointments
    </h1>

    <!-- Filter Tabs -->
    <div class="bg-white rounded-lg shadow-md mb-6">
        <div class="flex border-b">
            <a href="?filter=previous" 
               class="px-6 py-3 <?php echo $filter === 'previous' ? 'border-b-2 border-blue-600 text-blue-600 font-semibold' : 'text-gray-600 hover:text-blue-600'; ?>">
                <i class="fas fa-history mr-2"></i>Previous
            </a>
            <a href="?filter=today" 
               class="px-6 py-3 <?php echo $filter === 'today' ? 'border-b-2 border-blue-600 text-blue-600 font-semibold' : 'text-gray-600 hover:text-blue-600'; ?>">
                <i class="fas fa-calendar-day mr-2"></i>Today
            </a>
            <a href="?filter=future" 
               class="px-6 py-3 <?php echo $filter === 'future' ? 'border-b-2 border-blue-600 text-blue-600 font-semibold' : 'text-gray-600 hover:text-blue-600'; ?>">
                <i class="fas fa-calendar-alt mr-2"></i>Upcoming
            </a>
        </div>
    </div>

    <!-- Appointments List -->
    <div class="space-y-4">
        <?php if (empty($appointments)): ?>
            <div class="bg-white rounded-lg shadow-md p-8 text-center text-gray-500">
                <i class="fas fa-calendar-times text-6xl mb-4"></i>
                <p>No appointments found for this period.</p>
            </div>
        <?php else: ?>
            <?php foreach ($appointments as $appointment): ?>
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition duration-300">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-gray-800 mb-2">
                                <?php echo htmlspecialchars($appointment['pat_first_name'] . ' ' . $appointment['pat_last_name']); ?>
                            </h3>
                            <div class="space-y-1 text-gray-600">
                                <p><i class="fas fa-hashtag w-5"></i> <?php echo htmlspecialchars($appointment['appointment_id']); ?></p>
                                <p><i class="fas fa-calendar w-5"></i> <?php echo formatDate($appointment['appointment_date'], 'l, F d, Y'); ?></p>
                                <p><i class="fas fa-clock w-5"></i> <?php echo date('h:i A', strtotime($appointment['appointment_time'])); ?> (<?php echo $appointment['appointment_duration']; ?> min)</p>
                                <p><i class="fas fa-concierge-bell w-5"></i> <?php echo htmlspecialchars($appointment['service_name'] ?? 'N/A'); ?></p>
                                <p><i class="fas fa-envelope w-5"></i> <?php echo htmlspecialchars($appointment['pat_email']); ?></p>
                                <p><i class="fas fa-phone w-5"></i> <?php echo htmlspecialchars($appointment['pat_phone'] ?? 'N/A'); ?></p>
                            </div>
                            <?php if ($appointment['appointment_notes']): ?>
                                <div class="mt-3 p-3 bg-gray-50 rounded">
                                    <p class="text-sm text-gray-700"><strong>Notes:</strong> <?php echo nl2br(htmlspecialchars($appointment['appointment_notes'])); ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="ml-4">
                            <span class="px-4 py-2 inline-flex text-sm font-semibold rounded-full text-white mb-4" 
                                  style="background-color: <?php echo htmlspecialchars($appointment['status_color']); ?>">
                                <?php echo htmlspecialchars($appointment['status_name']); ?>
                            </span>
                            <div class="mt-4 space-y-2">
                                <a href="/appointments/view.php?id=<?php echo urlencode($appointment['appointment_id']); ?>" 
                                   class="block text-center bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition duration-300">
                                    <i class="fas fa-eye mr-1"></i>View Details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
