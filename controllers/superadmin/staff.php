<?php
require_once __DIR__ . '/../../classes/Auth.php';
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../includes/functions.php';

$auth = new Auth();
$auth->requireSuperAdmin();

$db = Database::getInstance();
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'create') {
        $first_name = sanitize($_POST['first_name']);
        $last_name = sanitize($_POST['last_name']);
        $email = sanitize($_POST['email']);
        $phone = sanitize($_POST['phone']);
        $position = sanitize($_POST['position']);
        $hire_date = $_POST['hire_date'] ?? null;
        $salary = !empty($_POST['salary']) ? floatval($_POST['salary']) : null;
        $status = sanitize($_POST['status'] ?? 'active');
        
        if (empty($first_name) || empty($last_name) || empty($email)) {
            $error = 'First name, last name, and email are required';
        } else {
            try {
                $stmt = $db->prepare("
                    INSERT INTO staff (staff_first_name, staff_last_name, staff_email, staff_phone, staff_position,
                                      staff_hire_date, staff_salary, staff_status, created_at) 
                    VALUES (:first_name, :last_name, :email, :phone, :position, :hire_date, :salary, :status, NOW())
                ");
                $stmt->execute([
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'email' => $email,
                    'phone' => $phone,
                    'position' => $position,
                    'hire_date' => $hire_date,
                    'salary' => $salary,
                    'status' => $status
                ]);
                $success = 'Staff member created successfully';
            } catch (PDOException $e) {
                $error = 'Database error: ' . $e->getMessage();
            }
        }
    }
    
    if ($action === 'update') {
        $id = (int)$_POST['id'];
        $first_name = sanitize($_POST['first_name']);
        $last_name = sanitize($_POST['last_name']);
        $email = sanitize($_POST['email']);
        $phone = sanitize($_POST['phone']);
        $position = sanitize($_POST['position']);
        $hire_date = $_POST['hire_date'] ?? null;
        $salary = !empty($_POST['salary']) ? floatval($_POST['salary']) : null;
        $status = sanitize($_POST['status'] ?? 'active');
        
        try {
            $stmt = $db->prepare("
                UPDATE staff 
                SET staff_first_name = :first_name, staff_last_name = :last_name, staff_email = :email, 
                    staff_phone = :phone, staff_position = :position, staff_hire_date = :hire_date,
                    staff_salary = :salary, staff_status = :status, updated_at = NOW()
                WHERE staff_id = :id
            ");
            $stmt->execute([
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
                'phone' => $phone,
                'position' => $position,
                'hire_date' => $hire_date,
                'salary' => $salary,
                'status' => $status,
                'id' => $id
            ]);
            $success = 'Staff member updated successfully';
        } catch (PDOException $e) {
            $error = 'Database error: ' . $e->getMessage();
        }
    }
    
    if ($action === 'delete') {
        $id = (int)$_POST['id'];
        try {
            $stmt = $db->prepare("DELETE FROM staff WHERE staff_id = :id");
            $stmt->execute(['id' => $id]);
            $success = 'Staff member deleted successfully';
        } catch (PDOException $e) {
            $error = 'Database error: ' . $e->getMessage();
        }
    }
}

try {
    $stmt = $db->query("SELECT * FROM staff ORDER BY created_at DESC");
    $staff = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = 'Failed to fetch staff: ' . $e->getMessage();
    $staff = [];
}

require_once __DIR__ . '/../../views/superadmin/staff.view.php';
