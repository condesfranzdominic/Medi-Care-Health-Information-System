<?php
require_once __DIR__ . '/../../classes/Auth.php';
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../includes/functions.php';

$auth = new Auth();
$auth->requirePatient();

$db = Database::getInstance();
$patient_id = $auth->getPatientId();
$error = '';

// Handle search
$search_query = '';
if (isset($_GET['search'])) {
    $search_query = sanitize($_GET['search']);
}

// Get all payments for this patient
try {
    $where_conditions = ['a.pat_id = :patient_id'];
    $params = ['patient_id' => $patient_id];

    if (!empty($search_query)) {
        $where_conditions[] = "(p.payment_id LIKE :search OR a.appointment_id LIKE :search OR pm.method_name LIKE :search)";
        $params['search'] = '%' . $search_query . '%';
    }

    $where_clause = 'WHERE ' . implode(' AND ', $where_conditions);

    $stmt = $db->prepare("
        SELECT p.*, 
               a.appointment_id, a.appointment_date, a.appointment_time,
               pm.method_name,
               ps.status_name, ps.status_color
        FROM payments p
        LEFT JOIN appointments a ON p.appointment_id = a.appointment_id
        LEFT JOIN payment_methods pm ON p.payment_method_id = pm.method_id
        LEFT JOIN payment_statuses ps ON p.payment_status_id = ps.payment_status_id
        $where_clause
        ORDER BY p.payment_date DESC
    ");
    $stmt->execute($params);
    $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = 'Failed to fetch payments: ' . $e->getMessage();
    $payments = [];
}

// Calculate totals
$total_paid = 0;
$total_pending = 0;
foreach ($payments as $payment) {
    if (strtolower($payment['status_name']) === 'paid') {
        $total_paid += $payment['payment_amount'];
    } elseif (strtolower($payment['status_name']) === 'pending') {
        $total_pending += $payment['payment_amount'];
    }
}

require_once __DIR__ . '/../../views/patient/payments.view.php';

