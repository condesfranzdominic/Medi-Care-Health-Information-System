<?php
// Helper functions

function redirect($url) {
    // If URL starts with /, make it relative to the project root
    if (strpos($url, '/') === 0) {
        $scriptName = dirname($_SERVER['SCRIPT_NAME']);
        $basePath = ($scriptName === '/' || $scriptName === '\\') ? '' : $scriptName;
        $url = $basePath . $url;
    }
    header("Location: $url");
    exit;
}

function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function formatDate($date, $format = 'Y-m-d') {
    if (empty($date)) return '';
    return date($format, strtotime($date));
}

function formatDateTime($datetime, $format = 'Y-m-d H:i:s') {
    if (empty($datetime)) return '';
    return date($format, strtotime($datetime));
}

function formatCurrency($amount) {
    return '₱' . number_format($amount, 2);
}

function generateAppointmentId($db) {
    $year = date('Y');
    $month = date('m');
    $prefix = "$year-$month-";
    
    // Get the last appointment ID for this month
    $sql = "SELECT appointment_id FROM appointments 
            WHERE appointment_id LIKE :prefix 
            ORDER BY appointment_id DESC LIMIT 1";
    
    $result = $db->fetchOne($sql, ['prefix' => $prefix . '%']);
    
    if ($result) {
        $lastId = $result['appointment_id'];
        $number = intval(substr($lastId, -7)) + 1;
    } else {
        $number = 1;
    }
    
    return $prefix . str_pad($number, 7, '0', STR_PAD_LEFT);
}

function setFlashMessage($type, $message) {
    $_SESSION['flash_message'] = [
        'type' => $type,
        'message' => $message
    ];
}

function getFlashMessage() {
    if (isset($_SESSION['flash_message'])) {
        $flash = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
        return $flash;
    }
    return null;
}

function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function isValidPhone($phone) {
    return preg_match('/^[0-9\-\+\(\)\s]+$/', $phone);
}

function getCurrentDate() {
    return date('Y-m-d');
}

function getCurrentDateTime() {
    return date('Y-m-d H:i:s');
}

function getTodayStart() {
    return date('Y-m-d 00:00:00');
}

function getTodayEnd() {
    return date('Y-m-d 23:59:59');
}

function csrfToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCsrfToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
