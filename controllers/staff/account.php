<?php
require_once __DIR__ . '/../../classes/Auth.php';
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../includes/functions.php';

$auth = new Auth();
$auth->requireStaff();

$db = Database::getInstance();
$staff_id = $auth->getStaffId();
$error = '';
$success = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'update_profile') {
        $first_name = sanitize($_POST['first_name'] ?? '');
        $last_name = sanitize($_POST['last_name'] ?? '');
        $email = sanitize($_POST['email'] ?? '');
        $phone = sanitize($_POST['phone'] ?? '');
        $position = sanitize($_POST['position'] ?? '');
        
        try {
            $stmt = $db->prepare("
                UPDATE staff 
                SET staff_first_name = :first_name, 
                    staff_last_name = :last_name,
                    staff_email = :email,
                    staff_phone = :phone,
                    staff_position = :position,
                    updated_at = NOW()
                WHERE staff_id = :staff_id
            ");
            $stmt->execute([
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
                'phone' => $phone,
                'position' => $position,
                'staff_id' => $staff_id
            ]);
            $success = 'Account information updated successfully';
        } catch (PDOException $e) {
            $error = 'Failed to update account: ' . $e->getMessage();
        }
    }
    
    if ($action === 'change_password') {
        $current_password = $_POST['current_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        
        if ($new_password !== $confirm_password) {
            $error = 'New passwords do not match';
        } else {
            try {
                $stmt = $db->prepare("SELECT u.user_password FROM users u JOIN staff s ON u.staff_id = s.staff_id WHERE s.staff_id = :staff_id");
                $stmt->execute(['staff_id' => $staff_id]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($user && password_verify($current_password, $user['user_password'])) {
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $stmt = $db->prepare("UPDATE users SET user_password = :password WHERE staff_id = :staff_id");
                    $stmt->execute(['password' => $hashed_password, 'staff_id' => $staff_id]);
                    $success = 'Password changed successfully';
                } else {
                    $error = 'Current password is incorrect';
                }
            } catch (PDOException $e) {
                $error = 'Failed to change password: ' . $e->getMessage();
            }
        }
    }
}

// Fetch staff data
try {
    $stmt = $db->prepare("SELECT * FROM staff WHERE staff_id = :staff_id");
    $stmt->execute(['staff_id' => $staff_id]);
    $staff = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = 'Failed to fetch account information: ' . $e->getMessage();
    $staff = null;
}

require_once __DIR__ . '/../../views/staff/account.view.php';

