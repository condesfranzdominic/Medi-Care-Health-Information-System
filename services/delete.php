<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/database.php';

$auth = new Auth();
$auth->requireRole(['superadmin']);

$db = Database::getInstance();
$serviceId = intval($_GET['id'] ?? 0);

if ($serviceId > 0) {
    try {
        $db->execute("DELETE FROM services WHERE service_id = :id", ['id' => $serviceId]);
        setFlashMessage('success', 'Service deleted successfully.');
    } catch (Exception $e) {
        setFlashMessage('error', 'Failed to delete service: ' . $e->getMessage());
    }
}

redirect('/services/index.php');
