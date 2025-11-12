<?php
require_once __DIR__ . '/../../classes/Auth.php';
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../includes/functions.php';

$auth = new Auth();
$auth->requireDoctor();

$db = Database::getInstance();
$doctor_id = $auth->getDoctorId();
$error = '';
$success = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'create') {
        $pat_id = (int)$_POST['pat_id'];
        $appointment_id = sanitize($_POST['appointment_id'] ?? '');
        $record_date = $_POST['record_date'];
        $diagnosis = sanitize($_POST['diagnosis']);
        $treatment = sanitize($_POST['treatment']);
        $prescription = sanitize($_POST['prescription'] ?? '');
        $notes = sanitize($_POST['notes'] ?? '');
        $follow_up_date = $_POST['follow_up_date'] ?? null;
        
        if (empty($pat_id) || empty($record_date) || empty($diagnosis)) {
            $error = 'Patient, date, and diagnosis are required';
        } else {
            try {
                $stmt = $db->prepare("
                    INSERT INTO medical_records (pat_id, doc_id, appointment_id, record_date, diagnosis, 
                                                 treatment, prescription, notes, follow_up_date, created_at) 
                    VALUES (:pat_id, :doc_id, :appointment_id, :record_date, :diagnosis, 
                           :treatment, :prescription, :notes, :follow_up_date, NOW())
                ");
                $stmt->execute([
                    'pat_id' => $pat_id,
                    'doc_id' => $doctor_id,
                    'appointment_id' => $appointment_id ?: null,
                    'record_date' => $record_date,
                    'diagnosis' => $diagnosis,
                    'treatment' => $treatment,
                    'prescription' => $prescription,
                    'notes' => $notes,
                    'follow_up_date' => $follow_up_date ?: null
                ]);
                $success = 'Medical record created successfully';
            } catch (PDOException $e) {
                $error = 'Database error: ' . $e->getMessage();
            }
        }
    }
    
    if ($action === 'update') {
        $id = (int)$_POST['id'];
        $diagnosis = sanitize($_POST['diagnosis']);
        $treatment = sanitize($_POST['treatment']);
        $prescription = sanitize($_POST['prescription'] ?? '');
        $notes = sanitize($_POST['notes'] ?? '');
        $follow_up_date = $_POST['follow_up_date'] ?? null;
        
        try {
            // Verify this record belongs to this doctor
            $stmt = $db->prepare("
                UPDATE medical_records 
                SET diagnosis = :diagnosis, treatment = :treatment, prescription = :prescription,
                    notes = :notes, follow_up_date = :follow_up_date, updated_at = NOW()
                WHERE record_id = :id AND doc_id = :doc_id
            ");
            $stmt->execute([
                'diagnosis' => $diagnosis,
                'treatment' => $treatment,
                'prescription' => $prescription,
                'notes' => $notes,
                'follow_up_date' => $follow_up_date ?: null,
                'id' => $id,
                'doc_id' => $doctor_id
            ]);
            $success = 'Medical record updated successfully';
        } catch (PDOException $e) {
            $error = 'Database error: ' . $e->getMessage();
        }
    }
}

// Handle search and filters
$search_query = '';
if (isset($_GET['search'])) {
    $search_query = sanitize($_GET['search']);
}

$filter_patient = isset($_GET['patient']) ? (int)$_GET['patient'] : null;

// Fetch medical records with filters
try {
    $where_conditions = ['mr.doc_id = :doctor_id'];
    $params = ['doctor_id' => $doctor_id];

    if (!empty($search_query)) {
        $where_conditions[] = "(p.pat_first_name LIKE :search OR p.pat_last_name LIKE :search OR mr.diagnosis LIKE :search)";
        $params['search'] = '%' . $search_query . '%';
    }

    if ($filter_patient) {
        $where_conditions[] = "mr.pat_id = :patient";
        $params['patient'] = $filter_patient;
    }

    $where_clause = 'WHERE ' . implode(' AND ', $where_conditions);

    $stmt = $db->prepare("
        SELECT mr.*, 
               p.pat_first_name, p.pat_last_name,
               a.appointment_date
        FROM medical_records mr
        LEFT JOIN patients p ON mr.pat_id = p.pat_id
        LEFT JOIN appointments a ON mr.appointment_id = a.appointment_id
        $where_clause
        ORDER BY mr.record_date DESC
    ");
    $stmt->execute($params);
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = 'Failed to fetch medical records: ' . $e->getMessage();
    $records = [];
}

// Fetch filter data from database
$filter_patients = [];
try {
    // Get unique patients from this doctor's medical records
    $stmt = $db->prepare("SELECT DISTINCT p.pat_id, p.pat_first_name, p.pat_last_name FROM medical_records mr JOIN patients p ON mr.pat_id = p.pat_id WHERE mr.doc_id = :doctor_id ORDER BY p.pat_first_name");
    $stmt->execute(['doctor_id' => $doctor_id]);
    $filter_patients = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $filter_patients = [];
}

// Fetch patients for dropdown (from doctor's appointments)
try {
    $stmt = $db->prepare("
        SELECT DISTINCT p.pat_id, p.pat_first_name, p.pat_last_name
        FROM patients p
        INNER JOIN appointments a ON p.pat_id = a.pat_id
        WHERE a.doc_id = :doctor_id
        ORDER BY p.pat_first_name
    ");
    $stmt->execute(['doctor_id' => $doctor_id]);
    $patients = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $patients = [];
}

require_once __DIR__ . '/../../views/doctor/medical-records.view.php';
