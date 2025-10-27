<?php
require_once __DIR__ . '/../../classes/Auth.php';
require_once __DIR__ . '/../../config/Database.php';

$auth = new Auth();
$auth->requireDoctor();

$db = Database::getInstance();
$doc_id = $_SESSION['doc_id'];

// Get doctor info
try {
    $stmt = $db->prepare("
        SELECT d.*, s.spec_name 
        FROM doctors d
        LEFT JOIN specializations s ON d.doc_specialization_id = s.spec_id
        WHERE d.doc_id = :doc_id
    ");
    $stmt->execute(['doc_id' => $doc_id]);
    $doctor = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $doctor = null;
}

// Get statistics
$stats = [
    'total_appointments' => 0,
    'today_appointments' => 0,
    'upcoming_appointments' => 0,
    'completed_appointments' => 0,
    'total_patients' => 0,
    'my_schedules' => 0,
    'all_schedules' => 0
];

try {
    // Total appointments for this doctor
    $stmt = $db->prepare("SELECT COUNT(*) as count FROM appointments WHERE doc_id = :doc_id");
    $stmt->execute(['doc_id' => $doc_id]);
    $stats['total_appointments'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    // Today's appointments
    $stmt = $db->prepare("
        SELECT COUNT(*) as count 
        FROM appointments 
        WHERE doc_id = :doc_id AND appointment_date = CURRENT_DATE
    ");
    $stmt->execute(['doc_id' => $doc_id]);
    $stats['today_appointments'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    // Upcoming appointments
    $stmt = $db->prepare("
        SELECT COUNT(*) as count 
        FROM appointments 
        WHERE doc_id = :doc_id AND appointment_date > CURRENT_DATE
    ");
    $stmt->execute(['doc_id' => $doc_id]);
    $stats['upcoming_appointments'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    // Completed appointments
    $stmt = $db->prepare("
        SELECT COUNT(*) as count 
        FROM appointments a
        JOIN statuses s ON a.status_id = s.status_id
        WHERE a.doc_id = :doc_id AND s.status_name = 'Completed'
    ");
    $stmt->execute(['doc_id' => $doc_id]);
    $stats['completed_appointments'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    // Total unique patients
    $stmt = $db->prepare("
        SELECT COUNT(DISTINCT pat_id) as count 
        FROM appointments 
        WHERE doc_id = :doc_id
    ");
    $stmt->execute(['doc_id' => $doc_id]);
    $stats['total_patients'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    // My schedules count
    $stmt = $db->prepare("SELECT COUNT(*) as count FROM schedules WHERE doc_id = :doc_id");
    $stmt->execute(['doc_id' => $doc_id]);
    $stats['my_schedules'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    // All schedules count
    $stmt = $db->query("SELECT COUNT(*) as count FROM schedules");
    $stats['all_schedules'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
} catch (PDOException $e) {
    // Keep default values
}

// Get today's appointments
try {
    $stmt = $db->prepare("
        SELECT a.*, 
               p.pat_first_name, p.pat_last_name, p.pat_email, p.pat_phone,
               s.status_name, s.status_color
        FROM appointments a
        JOIN patients p ON a.pat_id = p.pat_id
        JOIN statuses s ON a.status_id = s.status_id
        WHERE a.doc_id = :doc_id AND a.appointment_date = CURRENT_DATE
        ORDER BY a.appointment_time ASC
        LIMIT 5
    ");
    $stmt->execute(['doc_id' => $doc_id]);
    $today_appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $today_appointments = [];
}

// Get today's schedule
try {
    $stmt = $db->prepare("
        SELECT * FROM schedules 
        WHERE doc_id = :doc_id AND schedule_date = CURRENT_DATE
        ORDER BY start_time ASC
    ");
    $stmt->execute(['doc_id' => $doc_id]);
    $today_schedule = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $today_schedule = [];
}

// Get upcoming appointments
try {
    $stmt = $db->prepare("
        SELECT a.*, 
               p.pat_first_name, p.pat_last_name,
               s.status_name
        FROM appointments a
        JOIN patients p ON a.pat_id = p.pat_id
        JOIN statuses s ON a.status_id = s.status_id
        WHERE a.doc_id = :doc_id AND a.appointment_date > CURRENT_DATE
        ORDER BY a.appointment_date ASC, a.appointment_time ASC
        LIMIT 5
    ");
    $stmt->execute(['doc_id' => $doc_id]);
    $upcoming_appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $upcoming_appointments = [];
}

// Include the view
require_once __DIR__ . '/../../views/doctor/dashboard.view.php';
