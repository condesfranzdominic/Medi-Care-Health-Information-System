<?php
require_once __DIR__ . '/../classes/Auth.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../includes/functions.php';

$auth = new Auth();
$auth->requireSuperAdmin();

$db = Database::getInstance();
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'create') {
        $patient_id = (int)$_POST['patient_id'];
        $doctor_id = (int)$_POST['doctor_id'];
        $service_id = !empty($_POST['service_id']) ? (int)$_POST['service_id'] : null;
        $status_id = !empty($_POST['status_id']) ? (int)$_POST['status_id'] : 1; // Default to first status
        $appointment_date = $_POST['appointment_date'];
        $appointment_time = $_POST['appointment_time'];
        $duration = !empty($_POST['duration']) ? (int)$_POST['duration'] : 30;
        $notes = sanitize($_POST['notes'] ?? '');
        
        if (empty($patient_id) || empty($doctor_id) || empty($appointment_date)) {
            $error = 'Patient, doctor, and date are required';
        } else {
            try {
                $appointment_id = generateAppointmentId($db);
                
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
                $success = 'Appointment created successfully with ID: ' . $appointment_id;
            } catch (PDOException $e) {
                $error = 'Database error: ' . $e->getMessage();
            }
        }
    }
    
    if ($action === 'update') {
        $id = sanitize($_POST['id']);
        $patient_id = (int)$_POST['patient_id'];
        $doctor_id = (int)$_POST['doctor_id'];
        $service_id = !empty($_POST['service_id']) ? (int)$_POST['service_id'] : null;
        $status_id = !empty($_POST['status_id']) ? (int)$_POST['status_id'] : 1;
        $appointment_date = $_POST['appointment_date'];
        $appointment_time = $_POST['appointment_time'];
        $duration = !empty($_POST['duration']) ? (int)$_POST['duration'] : 30;
        $notes = sanitize($_POST['notes'] ?? '');
        
        try {
            $stmt = $db->prepare("
                UPDATE appointments 
                SET pat_id = :patient_id, doc_id = :doctor_id, service_id = :service_id, 
                    status_id = :status_id, appointment_date = :appointment_date,
                    appointment_time = :appointment_time, appointment_duration = :duration,
                    appointment_notes = :notes, updated_at = NOW()
                WHERE appointment_id = :id
            ");
            $stmt->execute([
                'patient_id' => $patient_id,
                'doctor_id' => $doctor_id,
                'service_id' => $service_id,
                'status_id' => $status_id,
                'appointment_date' => $appointment_date,
                'appointment_time' => $appointment_time,
                'duration' => $duration,
                'notes' => $notes,
                'id' => $id
            ]);
            $success = 'Appointment updated successfully';
        } catch (PDOException $e) {
            $error = 'Database error: ' . $e->getMessage();
        }
    }
    
    if ($action === 'delete') {
        $id = sanitize($_POST['id']);
        try {
            $stmt = $db->prepare("DELETE FROM appointments WHERE appointment_id = :id");
            $stmt->execute(['id' => $id]);
            $success = 'Appointment deleted successfully';
        } catch (PDOException $e) {
            $error = 'Database error: ' . $e->getMessage();
        }
    }
}

// Handle search
$search_query = '';
if (isset($_GET['search'])) {
    $search_query = sanitize($_GET['search']);
}

// Fetch appointments with patient, doctor, service, and status names
try {
    if (!empty($search_query)) {
        // Search by appointment ID
        $stmt = $db->prepare("
            SELECT a.*, 
                   p.pat_first_name, p.pat_last_name,
                   d.doc_first_name, d.doc_last_name,
                   s.service_name,
                   st.status_name, st.status_color
            FROM appointments a
            LEFT JOIN patients p ON a.pat_id = p.pat_id
            LEFT JOIN doctors d ON a.doc_id = d.doc_id
            LEFT JOIN services s ON a.service_id = s.service_id
            LEFT JOIN appointment_statuses st ON a.status_id = st.status_id
            WHERE a.appointment_id LIKE :search
            ORDER BY a.appointment_date DESC, a.appointment_time DESC
        ");
        $stmt->execute(['search' => '%' . $search_query . '%']);
        $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $stmt = $db->query("
            SELECT a.*, 
                   p.pat_first_name, p.pat_last_name,
                   d.doc_first_name, d.doc_last_name,
                   s.service_name,
                   st.status_name, st.status_color
            FROM appointments a
            LEFT JOIN patients p ON a.pat_id = p.pat_id
            LEFT JOIN doctors d ON a.doc_id = d.doc_id
            LEFT JOIN services s ON a.service_id = s.service_id
            LEFT JOIN appointment_statuses st ON a.status_id = st.status_id
            ORDER BY a.appointment_date DESC, a.appointment_time DESC
        ");
        $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    $error = 'Failed to fetch appointments: ' . $e->getMessage();
    $appointments = [];
}

// Fetch patients, doctors, services, and statuses for dropdowns
try {
    $patients = $db->query("SELECT pat_id, pat_first_name, pat_last_name FROM patients ORDER BY pat_first_name")->fetchAll(PDO::FETCH_ASSOC);
    $doctors = $db->query("SELECT doc_id, doc_first_name, doc_last_name FROM doctors ORDER BY doc_first_name")->fetchAll(PDO::FETCH_ASSOC);
    $services = $db->query("SELECT service_id, service_name FROM services ORDER BY service_name")->fetchAll(PDO::FETCH_ASSOC);
    $statuses = $db->query("SELECT status_id, status_name FROM appointment_statuses ORDER BY status_id")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $patients = [];
    $doctors = [];
    $services = [];
    $statuses = [];
}

require_once __DIR__ . '/../../views/superadmin/appointments.view.php';
