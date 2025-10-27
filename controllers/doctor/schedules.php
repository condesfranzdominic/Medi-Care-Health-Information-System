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
        $schedule_date = $_POST['schedule_date'];
        $start_time = $_POST['start_time'];
        $end_time = $_POST['end_time'];
        $max_appointments = !empty($_POST['max_appointments']) ? (int)$_POST['max_appointments'] : 10;
        $is_available = isset($_POST['is_available']) ? 1 : 0;
        
        if (empty($schedule_date) || empty($start_time) || empty($end_time)) {
            $error = 'Date, start time, and end time are required';
        } else {
            try {
                $stmt = $db->prepare("
                    INSERT INTO schedules (doc_id, schedule_date, start_time, end_time, max_appointments, is_available, created_at) 
                    VALUES (:doc_id, :schedule_date, :start_time, :end_time, :max_appointments, :is_available, NOW())
                ");
                $stmt->execute([
                    'doc_id' => $doctor_id,
                    'schedule_date' => $schedule_date,
                    'start_time' => $start_time,
                    'end_time' => $end_time,
                    'max_appointments' => $max_appointments,
                    'is_available' => $is_available
                ]);
                $success = 'Schedule created successfully';
            } catch (PDOException $e) {
                $error = 'Database error: ' . $e->getMessage();
            }
        }
    }
    
    if ($action === 'update') {
        $id = (int)$_POST['id'];
        $schedule_date = $_POST['schedule_date'];
        $start_time = $_POST['start_time'];
        $end_time = $_POST['end_time'];
        $max_appointments = !empty($_POST['max_appointments']) ? (int)$_POST['max_appointments'] : 10;
        $is_available = isset($_POST['is_available']) ? 1 : 0;
        
        try {
            $stmt = $db->prepare("
                UPDATE schedules 
                SET schedule_date = :schedule_date, start_time = :start_time, end_time = :end_time,
                    max_appointments = :max_appointments, is_available = :is_available, updated_at = NOW()
                WHERE schedule_id = :id AND doc_id = :doc_id
            ");
            $stmt->execute([
                'schedule_date' => $schedule_date,
                'start_time' => $start_time,
                'end_time' => $end_time,
                'max_appointments' => $max_appointments,
                'is_available' => $is_available,
                'id' => $id,
                'doc_id' => $doctor_id
            ]);
            $success = 'Schedule updated successfully';
        } catch (PDOException $e) {
            $error = 'Database error: ' . $e->getMessage();
        }
    }
    
    if ($action === 'delete') {
        $id = (int)$_POST['id'];
        try {
            $stmt = $db->prepare("DELETE FROM schedules WHERE schedule_id = :id AND doc_id = :doc_id");
            $stmt->execute(['id' => $id, 'doc_id' => $doctor_id]);
            $success = 'Schedule deleted successfully';
        } catch (PDOException $e) {
            $error = 'Database error: ' . $e->getMessage();
        }
    }
}

// Fetch all schedules for this doctor
try {
    $stmt = $db->prepare("
        SELECT * FROM schedules 
        WHERE doc_id = :doctor_id 
        ORDER BY schedule_date DESC, start_time ASC
    ");
    $stmt->execute(['doctor_id' => $doctor_id]);
    $schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = 'Failed to fetch schedules: ' . $e->getMessage();
    $schedules = [];
}

// Get today's schedules
try {
    $today = date('Y-m-d');
    $stmt = $db->prepare("
        SELECT * FROM schedules 
        WHERE doc_id = :doctor_id AND schedule_date = :today
        ORDER BY start_time ASC
    ");
    $stmt->execute(['doctor_id' => $doctor_id, 'today' => $today]);
    $today_schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $today_schedules = [];
}

require_once __DIR__ . '/../../views/doctor/schedules.view.php';
