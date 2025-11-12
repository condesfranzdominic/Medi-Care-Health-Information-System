<?php
require_once __DIR__ . '/../../classes/Auth.php';
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../includes/functions.php';

$auth = new Auth();
$auth->requireSuperAdmin();

$db = Database::getInstance();
$error = '';
$success = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'delete') {
        $id = (int)$_POST['id'];
        try {
            $stmt = $db->prepare("DELETE FROM schedules WHERE schedule_id = :id");
            $stmt->execute(['id' => $id]);
            $success = 'Schedule deleted successfully';
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

$filter_doctor = isset($_GET['doctor']) ? (int)$_GET['doctor'] : null;
$filter_available = isset($_GET['available']) ? $_GET['available'] : '';

// Fetch schedules with filters
try {
    $where_conditions = [];
    $params = [];

    if (!empty($search_query)) {
        $where_conditions[] = "(d.doc_first_name LIKE :search OR d.doc_last_name LIKE :search)";
        $params['search'] = '%' . $search_query . '%';
    }

    if ($filter_doctor) {
        $where_conditions[] = "s.doc_id = :doctor";
        $params['doctor'] = $filter_doctor;
    }

    if ($filter_available !== '') {
        if ($filter_available === 'yes') {
            $where_conditions[] = "s.is_available = 1";
        } elseif ($filter_available === 'no') {
            $where_conditions[] = "s.is_available = 0";
        }
    }

    $where_clause = !empty($where_conditions) ? 'WHERE ' . implode(' AND ', $where_conditions) : '';

    $stmt = $db->prepare("
        SELECT s.*, 
               d.doc_first_name, d.doc_last_name,
               sp.spec_name
        FROM schedules s
        LEFT JOIN doctors d ON s.doc_id = d.doc_id
        LEFT JOIN specializations sp ON d.doc_specialization_id = sp.spec_id
        $where_clause
        ORDER BY s.schedule_date DESC, s.start_time ASC
    ");
    $stmt->execute($params);
    $schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = 'Failed to fetch schedules: ' . $e->getMessage();
    $schedules = [];
}

// Fetch filter data from database
$filter_doctors = [];
try {
    // Get unique doctors from schedules
    $stmt = $db->query("SELECT DISTINCT d.doc_id, d.doc_first_name, d.doc_last_name FROM schedules s JOIN doctors d ON s.doc_id = d.doc_id ORDER BY d.doc_first_name");
    $filter_doctors = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $filter_doctors = [];
}

// Get today's schedules
try {
    $today = date('Y-m-d');
    $stmt = $db->prepare("
        SELECT s.*, 
               d.doc_first_name, d.doc_last_name,
               sp.spec_name
        FROM schedules s
        LEFT JOIN doctors d ON s.doc_id = d.doc_id
        LEFT JOIN specializations sp ON d.doc_specialization_id = sp.spec_id
        WHERE s.schedule_date = :today
        ORDER BY s.start_time ASC
    ");
    $stmt->execute(['today' => $today]);
    $today_schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $today_schedules = [];
}

require_once __DIR__ . '/../../views/superadmin/schedules.view.php';
