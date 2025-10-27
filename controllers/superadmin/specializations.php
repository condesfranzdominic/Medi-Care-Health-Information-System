<?php
require_once __DIR__ . '/../classes/Auth.php';
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../includes/functions.php';

$auth = new Auth();
$auth->requireSuperAdmin();

$db = Database::getInstance();
$error = '';
$success = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'create') {
        $spec_name = sanitize($_POST['spec_name']);
        $spec_description = sanitize($_POST['spec_description'] ?? '');
        
        if (empty($spec_name)) {
            $error = 'Specialization name is required';
        } else {
            try {
                $stmt = $db->prepare("
                    INSERT INTO specializations (spec_name, spec_description, created_at) 
                    VALUES (:spec_name, :spec_description, NOW())
                ");
                $stmt->execute([
                    'spec_name' => $spec_name,
                    'spec_description' => $spec_description
                ]);
                $success = 'Specialization created successfully';
            } catch (PDOException $e) {
                $error = 'Database error: ' . $e->getMessage();
            }
        }
    }
    
    if ($action === 'update') {
        $id = (int)$_POST['id'];
        $spec_name = sanitize($_POST['spec_name']);
        $spec_description = sanitize($_POST['spec_description'] ?? '');
        
        if (empty($spec_name)) {
            $error = 'Specialization name is required';
        } else {
            try {
                $stmt = $db->prepare("
                    UPDATE specializations 
                    SET spec_name = :spec_name, spec_description = :spec_description, updated_at = NOW()
                    WHERE spec_id = :id
                ");
                $stmt->execute([
                    'spec_name' => $spec_name,
                    'spec_description' => $spec_description,
                    'id' => $id
                ]);
                $success = 'Specialization updated successfully';
            } catch (PDOException $e) {
                $error = 'Database error: ' . $e->getMessage();
            }
        }
    }
    
    if ($action === 'delete') {
        $id = (int)$_POST['id'];
        try {
            $stmt = $db->prepare("DELETE FROM specializations WHERE spec_id = :id");
            $stmt->execute(['id' => $id]);
            $success = 'Specialization deleted successfully';
        } catch (PDOException $e) {
            $error = 'Database error: ' . $e->getMessage();
        }
    }
}

// Fetch all specializations with doctor count
try {
    $stmt = $db->query("
        SELECT s.*, COUNT(d.doc_id) as doctor_count
        FROM specializations s
        LEFT JOIN doctors d ON s.spec_id = d.doc_specialization_id
        GROUP BY s.spec_id
        ORDER BY s.spec_name ASC
    ");
    $specializations = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = 'Failed to fetch specializations: ' . $e->getMessage();
    $specializations = [];
}

require_once __DIR__ . '/../../views/superadmin/specializations.view.php';
