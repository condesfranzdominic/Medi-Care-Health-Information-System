<?php
require_once __DIR__ . '/../../classes/Auth.php';
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../includes/functions.php';

$auth = new Auth();
$auth->requireSuperAdmin();

$db = Database::getInstance();
$error = '';
$success = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'create') {
        $appointment_id = sanitize($_POST['appointment_id']);
        $amount = floatval($_POST['amount']);
        $payment_method_id = (int)$_POST['payment_method_id'];
        $payment_status_id = (int)$_POST['payment_status_id'];
        $payment_date = $_POST['payment_date'];
        $notes = sanitize($_POST['notes'] ?? '');
        
        if (empty($appointment_id) || empty($amount) || empty($payment_method_id) || empty($payment_status_id)) {
            $error = 'Appointment ID, amount, payment method, and status are required';
        } else {
            try {
                $stmt = $db->prepare("
                    INSERT INTO payments (appointment_id, amount, payment_method_id, payment_status_id, 
                                         payment_date, notes, created_at) 
                    VALUES (:appointment_id, :amount, :payment_method_id, :payment_status_id, 
                           :payment_date, :notes, NOW())
                ");
                $stmt->execute([
                    'appointment_id' => $appointment_id,
                    'amount' => $amount,
                    'payment_method_id' => $payment_method_id,
                    'payment_status_id' => $payment_status_id,
                    'payment_date' => $payment_date,
                    'notes' => $notes
                ]);
                $success = 'Payment record created successfully';
            } catch (PDOException $e) {
                $error = 'Database error: ' . $e->getMessage();
            }
        }
    }
    
    if ($action === 'update') {
        $id = (int)$_POST['id'];
        $amount = floatval($_POST['amount']);
        $payment_method_id = (int)$_POST['payment_method_id'];
        $payment_status_id = (int)$_POST['payment_status_id'];
        $payment_date = $_POST['payment_date'];
        $notes = sanitize($_POST['notes'] ?? '');
        
        try {
            $stmt = $db->prepare("
                UPDATE payments 
                SET amount = :amount, payment_method_id = :payment_method_id, 
                    payment_status_id = :payment_status_id, payment_date = :payment_date,
                    notes = :notes, updated_at = NOW()
                WHERE payment_id = :id
            ");
            $stmt->execute([
                'amount' => $amount,
                'payment_method_id' => $payment_method_id,
                'payment_status_id' => $payment_status_id,
                'payment_date' => $payment_date,
                'notes' => $notes,
                'id' => $id
            ]);
            $success = 'Payment record updated successfully';
        } catch (PDOException $e) {
            $error = 'Database error: ' . $e->getMessage();
        }
    }
    
    if ($action === 'delete') {
        $id = (int)$_POST['id'];
        try {
            $stmt = $db->prepare("DELETE FROM payments WHERE payment_id = :id");
            $stmt->execute(['id' => $id]);
            $success = 'Payment record deleted successfully';
        } catch (PDOException $e) {
            $error = 'Database error: ' . $e->getMessage();
        }
    }
}

// Fetch all payments with related data
try {
    $stmt = $db->query("
        SELECT p.*, 
               a.appointment_id, a.appointment_date,
               pat.pat_first_name, pat.pat_last_name,
               pm.method_name,
               ps.status_name
        FROM payments p
        LEFT JOIN appointments a ON p.appointment_id = a.appointment_id
        LEFT JOIN patients pat ON a.pat_id = pat.pat_id
        LEFT JOIN payment_methods pm ON p.payment_method_id = pm.method_id
        LEFT JOIN payment_statuses ps ON p.payment_status_id = ps.payment_status_id
        ORDER BY p.payment_date DESC, p.created_at DESC
    ");
    $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = 'Failed to fetch payments: ' . $e->getMessage();
    $payments = [];
}

// Fetch payment methods and statuses for dropdowns
try {
    $payment_methods = $db->query("SELECT * FROM payment_methods WHERE is_active = true ORDER BY method_name")->fetchAll(PDO::FETCH_ASSOC);
    $payment_statuses = $db->query("SELECT * FROM payment_statuses ORDER BY status_name")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $payment_methods = [];
    $payment_statuses = [];
}

require_once __DIR__ . '/../../views/superadmin/payments.view.php';
