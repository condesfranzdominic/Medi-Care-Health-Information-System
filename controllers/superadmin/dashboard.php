<?php
require_once __DIR__ . '/../classes/Auth.php';
require_once __DIR__ . '/../config/Database.php';

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
    
    // Recent appointments
    $stmt = $db->query("
        SELECT a.*, 
               p.pat_first_name, p.pat_last_name,
               d.doc_first_name, d.doc_last_name,
               s.status_name
        FROM appointments a
        LEFT JOIN patients p ON a.pat_id = p.pat_id
        LEFT JOIN doctors d ON a.doc_id = d.doc_id
        LEFT JOIN appointment_statuses s ON a.status_id = s.status_id
        ORDER BY a.created_at DESC
        LIMIT 5
    ");
    $recent_appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
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
}

// Include the view
require_once __DIR__ . '/../../views/superadmin/dashboard.view.php';
