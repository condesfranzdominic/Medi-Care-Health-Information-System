<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/database.php';

$auth = new Auth();
$auth->requireRole(['superadmin', 'staff']);

$db = Database::getInstance();
$appointmentId = sanitize($_GET['id'] ?? '');

if (!empty($appointmentId)) {
    try {
        // Get cancelled status ID
        $status = $db->fetchOne("SELECT status_id FROM appointment_statuses WHERE status_name = 'Cancelled'");
        
        if ($status) {
            $db->execute("UPDATE appointments SET status_id = :status_id WHERE appointment_id = :id", 
                        ['status_id' => $status['status_id'], 'id' => $appointmentId]);
            setFlashMessage('success', 'Appointment cancelled successfully.');
        } else {
            setFlashMessage('error', 'Cancelled status not found.');
        }
    } catch (Exception $e) {
        setFlashMessage('error', 'Failed to cancel appointment: ' . $e->getMessage());
    }
}

redirect('/appointments/index.php');
