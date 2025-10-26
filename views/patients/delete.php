<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/database.php';

$auth = new Auth();
$auth->requireRole(['superadmin', 'staff']);

$db = Database::getInstance();
$patientId = intval($_GET['id'] ?? 0);

if ($patientId > 0) {
    try {
        $user = $db->fetchOne("SELECT user_id FROM users WHERE pat_id = :id", ['id' => $patientId]);
        if ($user) {
            $db->execute("DELETE FROM users WHERE pat_id = :id", ['id' => $patientId]);
        }
        $db->execute("DELETE FROM patients WHERE pat_id = :id", ['id' => $patientId]);
        setFlashMessage('success', 'Patient deleted successfully.');
    } catch (Exception $e) {
        setFlashMessage('error', 'Failed to delete patient: ' . $e->getMessage());
    }
}

redirect('/patients/index.php');
