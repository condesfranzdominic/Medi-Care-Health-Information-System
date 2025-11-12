<?php
require_once __DIR__ . '/../../classes/Auth.php';
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../includes/functions.php';

$auth = new Auth();
$auth->requirePatient();

$db = Database::getInstance();
$error = '';
$success = '';

// Handle settings update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $notifications = isset($_POST['notifications']) ? 1 : 0;
    $email_notifications = isset($_POST['email_notifications']) ? 1 : 0;
    $language = sanitize($_POST['language'] ?? 'en');
    
    $success = 'Settings saved successfully';
}

require_once __DIR__ . '/../../views/patient/settings.view.php';

