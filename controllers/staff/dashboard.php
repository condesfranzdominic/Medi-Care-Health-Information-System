<?php
require_once __DIR__ . '/../../classes/Auth.php';
require_once __DIR__ . '/../../config/Database.php';

$auth = new Auth();
$auth->requireStaff();

$db = Database::getInstance();

// Get dashboard statistics
try {
    // Count staff
    $stmt = $db->query("SELECT COUNT(*) as count FROM staff");
    $stats['total_staff'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    // Count services
    $stmt = $db->query("SELECT COUNT(*) as count FROM services");
    $stats['total_services'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    // Count specializations
    $stmt = $db->query("SELECT COUNT(*) as count FROM specializations");
    $stats['total_specializations'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    // Count payment methods
    $stmt = $db->query("SELECT COUNT(*) as count FROM payment_methods WHERE is_active = true");
    $stats['total_payment_methods'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    
    // Recent services
    $stmt = $db->query("
        SELECT service_id, service_name, service_price, service_category
        FROM services
        ORDER BY created_at DESC
        LIMIT 5
    ");
    $recent_services = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    error_log("Staff Dashboard error: " . $e->getMessage());
    $stats = [
        'total_staff' => 0,
        'total_services' => 0,
        'total_specializations' => 0,
        'total_payment_methods' => 0
    ];
    $recent_services = [];
}

require_once __DIR__ . '/../../views/staff/dashboard.view.php';
