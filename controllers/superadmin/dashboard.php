<?php
require_once __DIR__ . '/../../classes/Auth.php';
require_once __DIR__ . '/../../config/Database.php';

$auth = new Auth();
$auth->requireSuperAdmin();

$db = Database::getInstance();

// Get dashboard statistics
try {
    // Count users
    $stmt = $db->query("SELECT COUNT(*) as count FROM users");
    $stats['total_users'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    // Count patients
    $stmt = $db->query("SELECT COUNT(*) as count FROM patients");
    $stats['total_patients'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    // Count doctors
    $stmt = $db->query("SELECT COUNT(*) as count FROM doctors");
    $stats['total_doctors'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    // Count staff
    $stmt = $db->query("SELECT COUNT(*) as count FROM staff");
    $stats['total_staff'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    // Count appointments
    $stmt = $db->query("SELECT COUNT(*) as count FROM appointments");
    $stats['total_appointments'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    // Recent appointments with patient details
    $stmt = $db->query("
        SELECT a.*, 
               p.pat_first_name, p.pat_last_name, p.pat_date_of_birth, p.pat_insurance_provider,
               d.doc_first_name, d.doc_last_name,
               s.status_name
        FROM appointments a
        LEFT JOIN patients p ON a.pat_id = p.pat_id
        LEFT JOIN doctors d ON a.doc_id = d.doc_id
        LEFT JOIN appointment_statuses s ON a.status_id = s.status_id
        ORDER BY a.appointment_date DESC, a.appointment_time DESC
        LIMIT 6
    ");
    $recent_appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get doctor schedules for today and upcoming (including today)
    $stmt = $db->query("
        SELECT s.*,
               d.doc_first_name, d.doc_last_name,
               sp.spec_name
        FROM schedules s
        LEFT JOIN doctors d ON s.doc_id = d.doc_id
        LEFT JOIN specializations sp ON d.doc_specialization_id = sp.spec_id
        WHERE s.schedule_date >= CURRENT_DATE AND s.is_available = TRUE
        ORDER BY s.schedule_date ASC, s.start_time ASC
        LIMIT 7
    ");
    $doctors_schedule = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // If no future schedules, get recent schedules instead
    if (empty($doctors_schedule)) {
        $stmt = $db->query("
            SELECT s.*,
                   d.doc_first_name, d.doc_last_name,
                   sp.spec_name
            FROM schedules s
            LEFT JOIN doctors d ON s.doc_id = d.doc_id
            LEFT JOIN specializations sp ON d.doc_specialization_id = sp.spec_id
            ORDER BY s.schedule_date DESC, s.start_time ASC
            LIMIT 7
        ");
        $doctors_schedule = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Chart data - Patient health and death (mock data for now, can be replaced with real data)
    $chart_data = [
        'health' => [100, 150, 200, 180, 250, 300, 280],
        'death' => [10, 15, 20, 18, 25, 30, 28]
    ];
    
} catch (PDOException $e) {
    error_log("Dashboard error: " . $e->getMessage());
    $stats = [
        'total_users' => 0,
        'total_patients' => 0,
        'total_doctors' => 0,
        'total_staff' => 0,
        'total_appointments' => 0
    ];
    $recent_appointments = [];
    $doctors_schedule = [];
    $chart_data = [
        'health' => [0, 0, 0, 0, 0, 0, 0],
        'death' => [0, 0, 0, 0, 0, 0, 0]
    ];
}

// Set default values if not set
if (!isset($doctors_schedule)) {
    $doctors_schedule = [];
}
if (!isset($chart_data)) {
    $chart_data = [
        'health' => [0, 0, 0, 0, 0, 0, 0],
        'death' => [0, 0, 0, 0, 0, 0, 0]
    ];
}

// Include the view
require_once __DIR__ . '/../../views/superadmin/dashboard.view.php';
