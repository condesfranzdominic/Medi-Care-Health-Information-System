<?php
require_once __DIR__ . '/../../classes/Auth.php';
require_once __DIR__ . '/../../config/Database.php';

$auth = new Auth();
$auth->requireStaff();

$db = Database::getInstance();
$error = '';

// Fetch all specializations with doctor count
try {
    $stmt = $db->query("
        SELECT s.*, COUNT(d.doc_id) as doctor_count
        FROM specializations s
        LEFT JOIN doctors d ON s.spec_id = d.doc_specialization_id
        GROUP BY s.spec_id
        ORDER BY s.spec_name ASC
    ");
    $specializations = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = 'Failed to fetch specializations: ' . $e->getMessage();
    $specializations = [];
}

require_once __DIR__ . '/../../views/staff/specializations.view.php';
