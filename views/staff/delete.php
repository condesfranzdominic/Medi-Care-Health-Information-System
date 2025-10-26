<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../config/database.php';

$auth = new Auth();
$auth->requireRole(['superadmin']);

$db = Database::getInstance();
$staffId = intval($_GET['id'] ?? 0);

if ($staffId > 0) {
    try {
        // Check if staff has a user account
        $user = $db->fetchOne("SELECT user_id FROM users WHERE staff_id = :id", ['id' => $staffId]);
        
        if ($user) {
            // Delete user account first
            $db->execute("DELETE FROM users WHERE staff_id = :id", ['id' => $staffId]);
        }
        
        // Delete staff
        $db->execute("DELETE FROM staff WHERE staff_id = :id", ['id' => $staffId]);
        setFlashMessage('success', 'Staff member deleted successfully.');
    } catch (Exception $e) {
        setFlashMessage('error', 'Failed to delete staff member: ' . $e->getMessage());
    }
}

redirect('/staff/index.php');
