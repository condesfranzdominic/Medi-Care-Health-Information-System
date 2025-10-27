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
        $service_name = sanitize($_POST['service_name']);
        $service_description = sanitize($_POST['service_description'] ?? '');
        $service_price = !empty($_POST['service_price']) ? floatval($_POST['service_price']) : 0;
        $service_duration = !empty($_POST['service_duration']) ? (int)$_POST['service_duration'] : 30;
        $service_category = sanitize($_POST['service_category'] ?? '');
        
        if (empty($service_name)) {
            $error = 'Service name is required';
        } else {
            try {
                $stmt = $db->prepare("
                    INSERT INTO services (service_name, service_description, service_price, 
                                         service_duration_minutes, service_category, created_at) 
                    VALUES (:service_name, :service_description, :service_price, 
                           :service_duration, :service_category, NOW())
                ");
                $stmt->execute([
                    'service_name' => $service_name,
                    'service_description' => $service_description,
                    'service_price' => $service_price,
                    'service_duration' => $service_duration,
                    'service_category' => $service_category
                ]);
                $success = 'Service created successfully';
            } catch (PDOException $e) {
                $error = 'Database error: ' . $e->getMessage();
            }
        }
    }
    
    if ($action === 'update') {
        $id = (int)$_POST['id'];
        $service_name = sanitize($_POST['service_name']);
        $service_description = sanitize($_POST['service_description'] ?? '');
        $service_price = !empty($_POST['service_price']) ? floatval($_POST['service_price']) : 0;
        $service_duration = !empty($_POST['service_duration']) ? (int)$_POST['service_duration'] : 30;
        $service_category = sanitize($_POST['service_category'] ?? '');
        
        if (empty($service_name)) {
            $error = 'Service name is required';
        } else {
            try {
                $stmt = $db->prepare("
                    UPDATE services 
                    SET service_name = :service_name, service_description = :service_description, 
                        service_price = :service_price, service_duration_minutes = :service_duration,
                        service_category = :service_category, updated_at = NOW()
                    WHERE service_id = :id
                ");
                $stmt->execute([
                    'service_name' => $service_name,
                    'service_description' => $service_description,
                    'service_price' => $service_price,
                    'service_duration' => $service_duration,
                    'service_category' => $service_category,
                    'id' => $id
                ]);
                $success = 'Service updated successfully';
            } catch (PDOException $e) {
                $error = 'Database error: ' . $e->getMessage();
            }
        }
    }
}

// Fetch all services with appointment count
try {
    $stmt = $db->query("
        SELECT s.*, COUNT(a.appointment_id) as appointment_count
        FROM services s
        LEFT JOIN appointments a ON s.service_id = a.service_id
        GROUP BY s.service_id
        ORDER BY s.service_name ASC
    ");
    $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = 'Failed to fetch services: ' . $e->getMessage();
    $services = [];
}

require_once __DIR__ . '/../../views/staff/services.view.php';
