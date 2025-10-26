<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/database.php';

$auth = new Auth();
$auth->requireRole(['superadmin']);

$db = Database::getInstance();
$userId = intval($_GET['id'] ?? 0);

if ($userId > 0 && $userId !== $auth->getUserId()) {
    try {
        $db->execute("DELETE FROM users WHERE user_id = :id", ['id' => $userId]);
        setFlashMessage('success', 'User deleted successfully.');
    } catch (Exception $e) {
        setFlashMessage('error', 'Failed to delete user: ' . $e->getMessage());
    }
} else {
    setFlashMessage('error', 'Cannot delete your own account.');
}

redirect('/users/index.php');
