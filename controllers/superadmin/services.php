<?php
require_once __DIR__ . '/../classes/Auth.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../includes/functions.php';

$auth = new Auth();
$auth->requireSuperAdmin();

$db = Database::getInstance();
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'create') {
        $service_name = sanitize($_POST['service_name']);
        $description = sanitize($_POST['description']);
        $price = floatval($_POST['price']);
        $duration = !empty($_POST['duration']) ? (int)$_POST['duration'] : 30;
        $category = sanitize($_POST['category'] ?? '');
        
        if (empty($service_name)) {
            $error = 'Service name is required';
        } else {
            try {
                $stmt = $db->prepare("
                    INSERT INTO services (service_name, service_description, service_price, service_duration_minutes, service_category, created_at) 
                    VALUES (:service_name, :description, :price, :duration, :category, NOW())
                ");
                $stmt->execute([
                    'service_name' => $service_name,
                    'description' => $description,
                    'price' => $price,
                    'duration' => $duration,
                    'category' => $category
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
        $description = sanitize($_POST['description']);
        $price = floatval($_POST['price']);
        $duration = !empty($_POST['duration']) ? (int)$_POST['duration'] : 30;
        $category = sanitize($_POST['category'] ?? '');
        
        try {
            $stmt = $db->prepare("
                UPDATE services 
                SET service_name = :service_name, service_description = :description, service_price = :price,
                    service_duration_minutes = :duration, service_category = :category, updated_at = NOW()
                WHERE service_id = :id
            ");
            $stmt->execute([
                'service_name' => $service_name,
                'description' => $description,
                'price' => $price,
                'duration' => $duration,
                'category' => $category,
                'id' => $id
            ]);
            $success = 'Service updated successfully';
        } catch (PDOException $e) {
            $error = 'Database error: ' . $e->getMessage();
        }
    }
    
    if ($action === 'delete') {
        $id = (int)$_POST['id'];
        try {
            $stmt = $db->prepare("DELETE FROM services WHERE service_id = :id");
            $stmt->execute(['id' => $id]);
            $success = 'Service deleted successfully';
        } catch (PDOException $e) {
            $error = 'Database error: ' . $e->getMessage();
        }
    }
}

try {
    $stmt = $db->query("SELECT * FROM services ORDER BY created_at DESC");
    $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = 'Failed to fetch services: ' . $e->getMessage();
    $services = [];
}

require_once __DIR__ . '/../../views/superadmin/services.view.php';
