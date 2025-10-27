<?php
require_once __DIR__ . '/../../classes/Auth.php';
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../includes/functions.php';

$auth = new Auth();
$auth->requirePatient();

$db = Database::getInstance();
$patient_id = $auth->getPatientId();
$error = '';
$success = '';
$appointment_id = '';

// Handle appointment creation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $doctor_id = (int)$_POST['doctor_id'];
    $service_id = !empty($_POST['service_id']) ? (int)$_POST['service_id'] : null;
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    $notes = sanitize($_POST['notes'] ?? '');
    
    if (empty($doctor_id) || empty($appointment_date) || empty($appointment_time)) {
        $error = 'Doctor, date, and time are required';
    } else {
        try {
            // Generate appointment ID
            $appointment_id = generateAppointmentId($db);
            
            // Default status is usually 1 (Scheduled/Pending)
            $status_id = 1;
            
            // Get service duration or default to 30
            $duration = 30;
            if ($service_id) {
                $stmt = $db->prepare("SELECT service_duration_minutes FROM services WHERE service_id = :service_id");
                $stmt->execute(['service_id' => $service_id]);
                $service = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($service) {
                    $duration = $service['service_duration_minutes'] ?? 30;
                }
            }
            
            $stmt = $db->prepare("
                INSERT INTO appointments (appointment_id, pat_id, doc_id, service_id, status_id, 
                                         appointment_date, appointment_time, appointment_duration, 
                                         appointment_notes, created_at) 
                VALUES (:appointment_id, :patient_id, :doctor_id, :service_id, :status_id,
                       :appointment_date, :appointment_time, :duration, :notes, NOW())
            ");
            $stmt->execute([
                'appointment_id' => $appointment_id,
                'patient_id' => $patient_id,
                'doctor_id' => $doctor_id,
                'service_id' => $service_id,
                'status_id' => $status_id,
                'appointment_date' => $appointment_date,
                'appointment_time' => $appointment_time,
                'duration' => $duration,
                'notes' => $notes
            ]);
            
            $success = "Appointment created successfully! Your appointment ID is: <strong>$appointment_id</strong>. Please keep this for your reference.";
        } catch (PDOException $e) {
            $error = 'Database error: ' . $e->getMessage();
        }
    }
}

// Fetch doctors with specializations
try {
    $stmt = $db->query("
        SELECT d.*, s.spec_name 
        FROM doctors d
        LEFT JOIN specializations s ON d.doc_specialization_id = s.spec_id
        WHERE d.doc_status = 'active'
        ORDER BY d.doc_first_name, d.doc_last_name
    ");
    $doctors = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $doctors = [];
}

// Fetch services
try {
    $stmt = $db->query("SELECT * FROM services ORDER BY service_name");
    $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $services = [];
}

require_once __DIR__ . '/../../views/patient/create-appointment.view.php';
