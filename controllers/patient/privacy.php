<?php
require_once __DIR__ . '/../../classes/Auth.php';
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../includes/functions.php';

$auth = new Auth();
$auth->requirePatient();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $success = 'Privacy settings saved successfully';
}

require_once __DIR__ . '/../../views/patient/privacy.view.php';

