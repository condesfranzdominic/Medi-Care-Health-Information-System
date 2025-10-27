<?php
require_once __DIR__ . '/../../classes/Auth.php';
require_once __DIR__ . '/../../config/Database.php';

$auth = new Auth();
$auth->requireStaff();

$db = Database::getInstance();
$error = '';

// Fetch all medical records (view-only for staff)
try {
    $stmt = $db->query("
        SELECT mr.*, 
               p.pat_first_name, p.pat_last_name,
               d.doc_first_name, d.doc_last_name,
               a.appointment_date
        FROM medical_records mr
        LEFT JOIN patients p ON mr.pat_id = p.pat_id
        LEFT JOIN doctors d ON mr.doc_id = d.doc_id
        LEFT JOIN appointments a ON mr.appointment_id = a.appointment_id
        ORDER BY mr.record_date DESC
    ");
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = 'Failed to fetch medical records: ' . $e->getMessage();
    $records = [];
}

require_once __DIR__ . '/../../views/staff/medical-records.view.php';
