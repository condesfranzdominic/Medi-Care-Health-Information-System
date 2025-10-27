<?php
require_once __DIR__ . '/../../classes/Auth.php';
require_once __DIR__ . '/../../config/Database.php';

$auth = new Auth();
$auth->requirePatient();

$db = Database::getInstance();
$patient_id = $auth->getPatientId();
$error = '';

// Get patient info
try {
    $stmt = $db->prepare("SELECT * FROM patients WHERE pat_id = :patient_id");
    $stmt->execute(['patient_id' => $patient_id]);
    $patient = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = 'Failed to fetch patient info: ' . $e->getMessage();
    $patient = null;
}

// Get all appointments for this patient
try {
    $stmt = $db->prepare("
        SELECT a.*, 
               d.doc_first_name, d.doc_last_name, d.doc_specialization_id,
               s.service_name, s.service_price,
               st.status_name, st.status_color,
               sp.spec_name
        FROM appointments a
        LEFT JOIN doctors d ON a.doc_id = d.doc_id
        LEFT JOIN services s ON a.service_id = s.service_id
        LEFT JOIN appointment_statuses st ON a.status_id = st.status_id
        LEFT JOIN specializations sp ON d.doc_specialization_id = sp.spec_id
        WHERE a.pat_id = :patient_id
        ORDER BY a.appointment_date DESC, a.appointment_time DESC
    ");
    $stmt->execute(['patient_id' => $patient_id]);
    $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Separate into upcoming and past
    $today = date('Y-m-d');
    $upcoming_appointments = array_filter($appointments, function($apt) use ($today) {
        return $apt['appointment_date'] >= $today;
    });
    $past_appointments = array_filter($appointments, function($apt) use ($today) {
        return $apt['appointment_date'] < $today;
    });
    
} catch (PDOException $e) {
    $error = 'Failed to fetch appointments: ' . $e->getMessage();
    $appointments = [];
    $upcoming_appointments = [];
    $past_appointments = [];
}

require_once __DIR__ . '/../../views/patient/appointments.view.php';
