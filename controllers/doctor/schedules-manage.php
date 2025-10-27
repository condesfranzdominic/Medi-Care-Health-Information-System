<?php
require_once __DIR__ . '/../../classes/Auth.php';
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../includes/functions.php';

$auth = new Auth();
$auth->requireDoctor();

$db = Database::getInstance();
$error = '';
$success = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'create') {
        $doc_id = (int)$_POST['doc_id'];
        $schedule_date = $_POST['schedule_date'];
        $start_time = sanitize($_POST['start_time']);
        $end_time = sanitize($_POST['end_time']);
        $max_appointments = !empty($_POST['max_appointments']) ? (int)$_POST['max_appointments'] : 10;
        $is_available = isset($_POST['is_available']) ? 1 : 0;
        
        if (empty($doc_id) || empty($schedule_date) || empty($start_time) || empty($end_time)) {
            $error = 'All fields are required';
        } elseif ($start_time >= $end_time) {
            $error = 'End time must be after start time';
        } else {
            try {
                // Check for overlapping schedules
                $stmt = $db->prepare("
                    SELECT schedule_id FROM schedules 
                    WHERE doc_id = :doc_id 
                    AND schedule_date = :schedule_date 
                    AND (
                        (start_time <= :start_time AND end_time > :start_time) OR
                        (start_time < :end_time AND end_time >= :end_time) OR
                        (start_time >= :start_time AND end_time <= :end_time)
                    )
                ");
                $stmt->execute([
                    'doc_id' => $doc_id,
                    'schedule_date' => $schedule_date,
                    'start_time' => $start_time,
                    'end_time' => $end_time
                ]);
                
                if ($stmt->fetch()) {
                    $error = 'This schedule overlaps with an existing schedule for this doctor on this date';
                } else {
                    $stmt = $db->prepare("
                        INSERT INTO schedules (doc_id, schedule_date, start_time, end_time, max_appointments, is_available, created_at) 
                        VALUES (:doc_id, :schedule_date, :start_time, :end_time, :max_appointments, :is_available, NOW())
                    ");
                    $stmt->execute([
                        'doc_id' => $doc_id,
                        'schedule_date' => $schedule_date,
                        'start_time' => $start_time,
                        'end_time' => $end_time,
                        'max_appointments' => $max_appointments,
                        'is_available' => $is_available
                    ]);
                    $success = 'Schedule created successfully';
                }
            } catch (PDOException $e) {
                $error = 'Database error: ' . $e->getMessage();
            }
        }
    }
    
    if ($action === 'update') {
        $id = (int)$_POST['id'];
        $doc_id = (int)$_POST['doc_id'];
        $schedule_date = $_POST['schedule_date'];
        $start_time = sanitize($_POST['start_time']);
        $end_time = sanitize($_POST['end_time']);
        $max_appointments = !empty($_POST['max_appointments']) ? (int)$_POST['max_appointments'] : 10;
        $is_available = isset($_POST['is_available']) ? 1 : 0;
        
        if (empty($doc_id) || empty($schedule_date) || empty($start_time) || empty($end_time)) {
            $error = 'All fields are required';
        } elseif ($start_time >= $end_time) {
            $error = 'End time must be after start time';
        } else {
            try {
                // Check for overlapping schedules (excluding current schedule)
                $stmt = $db->prepare("
                    SELECT schedule_id FROM schedules 
                    WHERE doc_id = :doc_id 
                    AND schedule_date = :schedule_date 
                    AND schedule_id != :id
                    AND (
                        (start_time <= :start_time AND end_time > :start_time) OR
                        (start_time < :end_time AND end_time >= :end_time) OR
                        (start_time >= :start_time AND end_time <= :end_time)
                    )
                ");
                $stmt->execute([
                    'doc_id' => $doc_id,
                    'schedule_date' => $schedule_date,
                    'start_time' => $start_time,
                    'end_time' => $end_time,
                    'id' => $id
                ]);
                
                if ($stmt->fetch()) {
                    $error = 'This schedule overlaps with an existing schedule for this doctor on this date';
                } else {
                    $stmt = $db->prepare("
                        UPDATE schedules 
                        SET doc_id = :doc_id, schedule_date = :schedule_date, start_time = :start_time, 
                            end_time = :end_time, max_appointments = :max_appointments, is_available = :is_available, updated_at = NOW()
                        WHERE schedule_id = :id
                    ");
                    $stmt->execute([
                        'id' => $id,
                        'doc_id' => $doc_id,
                        'schedule_date' => $schedule_date,
                        'start_time' => $start_time,
                        'end_time' => $end_time,
                        'max_appointments' => $max_appointments,
                        'is_available' => $is_available
                    ]);
                    $success = 'Schedule updated successfully';
                }
            } catch (PDOException $e) {
                $error = 'Database error: ' . $e->getMessage();
            }
        }
    }
    
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

// Fetch all schedules with doctor info
try {
    $stmt = $db->query("
        SELECT s.*, 
               CONCAT(d.doc_first_name, ' ', d.doc_last_name) as doctor_name,
               sp.spec_name
        FROM schedules s
        JOIN doctors d ON s.doc_id = d.doc_id
        LEFT JOIN specializations sp ON d.doc_specialization_id = sp.spec_id
        ORDER BY s.schedule_date DESC, s.start_time
    ");
    $all_schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = 'Failed to fetch schedules: ' . $e->getMessage();
    $all_schedules = [];
}

// Fetch today's schedules
try {
    $stmt = $db->prepare("
        SELECT s.*, 
               CONCAT(d.doc_first_name, ' ', d.doc_last_name) as doctor_name,
               sp.spec_name
        FROM schedules s
        JOIN doctors d ON s.doc_id = d.doc_id
        LEFT JOIN specializations sp ON d.doc_specialization_id = sp.spec_id
        WHERE s.schedule_date = CURRENT_DATE AND s.is_available = true
        ORDER BY s.start_time
    ");
    $stmt->execute();
    $today_schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $today_schedules = [];
}

// Fetch all doctors for dropdown
try {
    $doctors = $db->query("
        SELECT doc_id, CONCAT(doc_first_name, ' ', doc_last_name) as doctor_name 
        FROM doctors 
        WHERE doc_status = 'active'
        ORDER BY doc_last_name, doc_first_name
    ")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $doctors = [];
}

// Include the view
require_once __DIR__ . '/../../views/doctor/schedules-manage.view.php';
