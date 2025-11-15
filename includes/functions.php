<?php
// Helper functions

function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function generateAppointmentId($db) {
    $year = date('Y');
    $month = date('m');
    $prefix = "$year-$month-";
    
    try {
        // Get the last appointment ID for this month
        $stmt = $db->query("SELECT appointment_id FROM appointments WHERE appointment_id LIKE '$prefix%' ORDER BY appointment_id DESC LIMIT 1");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result) {
            $lastNum = (int)substr($result['appointment_id'], -7);
            $newNum = $lastNum + 1;
        } else {
            $newNum = 1;
        }
        
        return $prefix . str_pad($newNum, 7, '0', STR_PAD_LEFT);
    } catch (PDOException $e) {
        return $prefix . str_pad(rand(1, 9999999), 7, '0', STR_PAD_LEFT);
    }
}

function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function formatPhoneNumber($phone) {
    // Remove all non-digit characters
    $digits = preg_replace('/\D/', '', $phone);

    // Ensure it has 11 digits (Philippine format)
    if (strlen($digits) === 11) {
        return preg_replace('/(\d{4})(\d{3})(\d{4})/', '$1-$2-$3', $digits);
    }

    // If not 11 digits, just return the cleaned version
    return $digits;
}

