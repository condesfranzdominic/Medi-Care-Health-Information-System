<?php
require_once __DIR__ . '/../../classes/Auth.php';
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../includes/functions.php';

$auth = new Auth();
$auth->requireStaff();

$db = Database::getInstance();
$error = '';
$success = '';

// Handle form submissions (Staff can Add and Update, but NOT Delete)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'create') {
        $method_name = sanitize($_POST['method_name']);
        $method_description = sanitize($_POST['method_description'] ?? '');
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        
        if (empty($method_name)) {
            $error = 'Payment method name is required';
        } else {
            try {
                $stmt = $db->prepare("
                    INSERT INTO payment_methods (method_name, method_description, is_active, created_at) 
                    VALUES (:method_name, :method_description, :is_active, NOW())
                ");
                $stmt->execute([
                    'method_name' => $method_name,
                    'method_description' => $method_description,
                    'is_active' => $is_active
                ]);
                $success = 'Payment method created successfully';
            } catch (PDOException $e) {
                $error = 'Database error: ' . $e->getMessage();
            }
        }
    }
    
    if ($action === 'update') {
        $id = (int)$_POST['id'];
        $method_name = sanitize($_POST['method_name']);
        $method_description = sanitize($_POST['method_description'] ?? '');
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        
        if (empty($method_name)) {
            $error = 'Payment method name is required';
        } else {
            try {
                $stmt = $db->prepare("
                    UPDATE payment_methods 
                    SET method_name = :method_name, method_description = :method_description, 
                        is_active = :is_active, updated_at = NOW()
                    WHERE method_id = :id
                ");
                $stmt->execute([
                    'method_name' => $method_name,
                    'method_description' => $method_description,
                    'is_active' => $is_active,
                    'id' => $id
                ]);
                $success = 'Payment method updated successfully';
            } catch (PDOException $e) {
                $error = 'Database error: ' . $e->getMessage();
            }
        }
    }
}

// Fetch all payment methods
try {
    $stmt = $db->query("
        SELECT pm.*, COUNT(p.payment_id) as payment_count
        FROM payment_methods pm
        LEFT JOIN payments p ON pm.method_id = p.payment_method_id
        GROUP BY pm.method_id
        ORDER BY pm.method_name ASC
    ");
    $payment_methods = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = 'Failed to fetch payment methods: ' . $e->getMessage();
    $payment_methods = [];
}

require_once __DIR__ . '/../../views/staff/payment-methods.view.php';
