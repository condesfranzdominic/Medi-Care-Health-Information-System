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

// Get all medical records for this patient
try {
    $where_conditions = ['mr.pat_id = :patient_id'];
    $params = ['patient_id' => $patient_id];

    if (!empty($search_query)) {
        $where_conditions[] = "(d.doc_first_name LIKE :search OR d.doc_last_name LIKE :search OR mr.diagnosis LIKE :search OR mr.treatment LIKE :search)";
        $params['search'] = '%' . $search_query . '%';
    }

    $where_clause = 'WHERE ' . implode(' AND ', $where_conditions);

    $stmt = $db->prepare("
        SELECT mr.*, 
               d.doc_first_name, d.doc_last_name, d.doc_specialization_id,
               a.appointment_date, a.appointment_id, a.appointment_time,
               sp.spec_name
        FROM medical_records mr
        LEFT JOIN doctors d ON mr.doc_id = d.doc_id
        LEFT JOIN appointments a ON mr.appointment_id = a.appointment_id
        LEFT JOIN specializations sp ON d.doc_specialization_id = sp.spec_id
        $where_clause
        ORDER BY mr.record_date DESC
    ");
    $stmt->execute($params);
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = 'Failed to fetch medical records: ' . $e->getMessage();
    $records = [];
}

require_once __DIR__ . '/../../views/patient/medical-records.view.php';

