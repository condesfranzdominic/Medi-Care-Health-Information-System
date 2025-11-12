<?php
require_once __DIR__ . '/../../classes/Auth.php';
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../includes/functions.php';

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

// Handle search and filters
$search_query = '';
if (isset($_GET['search'])) {
    $search_query = sanitize($_GET['search']);
}

$filter_status = isset($_GET['status']) ? (int)$_GET['status'] : null;
$filter_category = isset($_GET['category']) ? sanitize($_GET['category']) : '';

// Get all appointments for this patient with filters
try {
    $where_conditions = ['a.pat_id = :patient_id'];
    $params = ['patient_id' => $patient_id];

    if (!empty($search_query)) {
        $where_conditions[] = "(d.doc_first_name LIKE :search OR d.doc_last_name LIKE :search OR s.service_name LIKE :search)";
        $params['search'] = '%' . $search_query . '%';
    }

    if ($filter_status) {
        $where_conditions[] = "a.status_id = :status";
        $params['status'] = $filter_status;
    }

    $where_clause = 'WHERE ' . implode(' AND ', $where_conditions);

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
        $where_clause
        ORDER BY a.appointment_date DESC, a.appointment_time DESC
    ");
    $stmt->execute($params);
    $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Separate into upcoming and past based on filter_category
    $today = date('Y-m-d');
    if ($filter_category === 'upcoming') {
        $upcoming_appointments = array_filter($appointments, function($apt) use ($today) {
            return $apt['appointment_date'] >= $today;
        });
        $past_appointments = [];
    } elseif ($filter_category === 'past') {
        $upcoming_appointments = [];
        $past_appointments = array_filter($appointments, function($apt) use ($today) {
            return $apt['appointment_date'] < $today;
        });
    } else {
        $upcoming_appointments = array_filter($appointments, function($apt) use ($today) {
            return $apt['appointment_date'] >= $today;
        });
        $past_appointments = array_filter($appointments, function($apt) use ($today) {
            return $apt['appointment_date'] < $today;
        });
    }
    
} catch (PDOException $e) {
    $error = 'Failed to fetch appointments: ' . $e->getMessage();
    $appointments = [];
    $upcoming_appointments = [];
    $past_appointments = [];
}

// Fetch filter data from database
$filter_statuses = [];
try {
    // Get unique statuses from this patient's appointments
    $stmt = $db->prepare("SELECT DISTINCT st.status_id, st.status_name FROM appointments a JOIN appointment_statuses st ON a.status_id = st.status_id WHERE a.pat_id = :patient_id ORDER BY st.status_name");
    $stmt->execute(['patient_id' => $patient_id]);
    $filter_statuses = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $filter_statuses = [];
}

require_once __DIR__ . '/../../views/patient/appointments.view.php';
