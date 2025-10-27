<?php
require_once __DIR__ . '/../../classes/Auth.php';
require_once __DIR__ . '/../../config/Database.php';

$auth = new Auth();
$auth->requireDoctor();

$db = Database::getInstance();
$doctor_id = $auth->getDoctorId();

// Get previous appointments for this doctor only
try {
    $today = date('Y-m-d');
    
    $stmt = $db->prepare("
        SELECT a.*, 
               p.pat_first_name, p.pat_last_name, p.pat_phone,
               s.service_name,
               st.status_name, st.status_color
        FROM appointments a
        LEFT JOIN patients p ON a.pat_id = p.pat_id
        LEFT JOIN services s ON a.service_id = s.service_id
        LEFT JOIN appointment_statuses st ON a.status_id = st.status_id
        WHERE a.doc_id = :doctor_id AND a.appointment_date < :today
        ORDER BY a.appointment_date DESC, a.appointment_time DESC
    ");
    $stmt->execute([
        'doctor_id' => $doctor_id,
        'today' => $today
    ]);
    $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    error_log("Doctor previous appointments error: " . $e->getMessage());
    $appointments = [];
    $error = 'Failed to fetch appointments: ' . $e->getMessage();
}

require_once __DIR__ . '/../../views/doctor/appointments-previous.view.php';
