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
        $first_name = sanitize($_POST['first_name']);
        $last_name = sanitize($_POST['last_name']);
        $email = sanitize($_POST['email']);
        $phone = sanitize($_POST['phone']);
        $specialization_id = !empty($_POST['specialization_id']) ? (int)$_POST['specialization_id'] : null;
        $license_number = sanitize($_POST['license_number']);
        $experience_years = !empty($_POST['experience_years']) ? (int)$_POST['experience_years'] : null;
        $consultation_fee = !empty($_POST['consultation_fee']) ? floatval($_POST['consultation_fee']) : null;
        $qualification = sanitize($_POST['qualification'] ?? '');
        $bio = sanitize($_POST['bio'] ?? '');
        $status = sanitize($_POST['status'] ?? 'active');
        
        if (empty($first_name) || empty($last_name) || empty($email)) {
            $error = 'First name, last name, and email are required';
        } elseif (!isValidEmail($email)) {
            $error = 'Invalid email format';
        } else {
            try {
                $stmt = $db->prepare("
                    INSERT INTO doctors (doc_first_name, doc_last_name, doc_email, doc_phone, doc_specialization_id, 
                                        doc_license_number, doc_experience_years, doc_consultation_fee, 
                                        doc_qualification, doc_bio, doc_status, created_at) 
                    VALUES (:first_name, :last_name, :email, :phone, :specialization_id, :license_number,
                           :experience_years, :consultation_fee, :qualification, :bio, :status, NOW())
                ");
                $stmt->execute([
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'email' => $email,
                    'phone' => $phone,
                    'specialization_id' => $specialization_id,
                    'license_number' => $license_number,
                    'experience_years' => $experience_years,
                    'consultation_fee' => $consultation_fee,
                    'qualification' => $qualification,
                    'bio' => $bio,
                    'status' => $status
                ]);
                $success = 'Doctor created successfully';
            } catch (PDOException $e) {
                $error = 'Database error: ' . $e->getMessage();
            }
        }
    }
    
    if ($action === 'update') {
        $id = (int)$_POST['id'];
        $first_name = sanitize($_POST['first_name']);
        $last_name = sanitize($_POST['last_name']);
        $email = sanitize($_POST['email']);
        $phone = sanitize($_POST['phone']);
        $specialization_id = !empty($_POST['specialization_id']) ? (int)$_POST['specialization_id'] : null;
        $license_number = sanitize($_POST['license_number']);
        $experience_years = !empty($_POST['experience_years']) ? (int)$_POST['experience_years'] : null;
        $consultation_fee = !empty($_POST['consultation_fee']) ? floatval($_POST['consultation_fee']) : null;
        $qualification = sanitize($_POST['qualification'] ?? '');
        $bio = sanitize($_POST['bio'] ?? '');
        $status = sanitize($_POST['status'] ?? 'active');
        
        if (empty($first_name) || empty($last_name) || empty($email)) {
            $error = 'First name, last name, and email are required';
        } else {
            try {
                $stmt = $db->prepare("
                    UPDATE doctors 
                    SET doc_first_name = :first_name, doc_last_name = :last_name, doc_email = :email, 
                        doc_phone = :phone, doc_specialization_id = :specialization_id, doc_license_number = :license_number,
                        doc_experience_years = :experience_years, doc_consultation_fee = :consultation_fee,
                        doc_qualification = :qualification, doc_bio = :bio, doc_status = :status, updated_at = NOW()
                    WHERE doc_id = :id
                ");
                $stmt->execute([
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'email' => $email,
                    'phone' => $phone,
                    'specialization_id' => $specialization_id,
                    'license_number' => $license_number,
                    'experience_years' => $experience_years,
                    'consultation_fee' => $consultation_fee,
                    'qualification' => $qualification,
                    'bio' => $bio,
                    'status' => $status,
                    'id' => $id
                ]);
                $success = 'Doctor updated successfully';
            } catch (PDOException $e) {
                $error = 'Database error: ' . $e->getMessage();
            }
        }
    }
    
    if ($action === 'delete') {
        $id = (int)$_POST['id'];
        
        try {
            $stmt = $db->prepare("DELETE FROM doctors WHERE doc_id = :id");
            $stmt->execute(['id' => $id]);
            $success = 'Doctor deleted successfully';
        } catch (PDOException $e) {
            $error = 'Database error: ' . $e->getMessage();
        }
    }
}

// Check if filtering by specialization
$spec_filter = isset($_GET['spec_id']) ? (int)$_GET['spec_id'] : null;
$spec_name_filter = '';

// Fetch all doctors with specialization names
try {
    if ($spec_filter) {
        // Get specialization name for display
        $stmt = $db->prepare("SELECT spec_name FROM specializations WHERE spec_id = :spec_id");
        $stmt->execute(['spec_id' => $spec_filter]);
        $spec_data = $stmt->fetch(PDO::FETCH_ASSOC);
        $spec_name_filter = $spec_data ? $spec_data['spec_name'] : '';
        
        // Fetch doctors filtered by specialization
        $stmt = $db->prepare("
            SELECT d.*, s.spec_name 
            FROM doctors d
            LEFT JOIN specializations s ON d.doc_specialization_id = s.spec_id
            WHERE d.doc_specialization_id = :spec_id
            ORDER BY d.created_at DESC
        ");
        $stmt->execute(['spec_id' => $spec_filter]);
        $doctors = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        // Fetch all doctors
        $stmt = $db->query("
            SELECT d.*, s.spec_name 
            FROM doctors d
            LEFT JOIN specializations s ON d.doc_specialization_id = s.spec_id
            ORDER BY d.created_at DESC
        ");
        $doctors = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    $error = 'Failed to fetch doctors: ' . $e->getMessage();
    $doctors = [];
}

// Fetch specializations for dropdown
try {
    $stmt = $db->query("SELECT spec_id, spec_name FROM specializations ORDER BY spec_name");
    $specializations = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $specializations = [];
}

// Include the view
require_once __DIR__ . '/../../views/superadmin/doctors.view.php';
