<?php
require_once __DIR__ . '/../../classes/Auth.php';
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../includes/functions.php';

$auth = new Auth();
$auth->requireDoctor();

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
        $password = $_POST['password'] ?? '';
        $create_user = isset($_POST['create_user']) && $_POST['create_user'] === '1';
        
        if (empty($first_name) || empty($last_name) || empty($email)) {
            $error = 'First name, last name, and email are required';
        } elseif (!isValidEmail($email)) {
            $error = 'Invalid email format';
        } elseif ($create_user && empty($password)) {
            $error = 'Password is required when creating user account';
        } elseif ($create_user && strlen($password) < 6) {
            $error = 'Password must be at least 6 characters';
        } else {
            try {
                // Check if email already exists in users table
                if ($create_user) {
                    $stmt = $db->prepare("SELECT user_id FROM users WHERE user_email = :email");
                    $stmt->execute(['email' => $email]);
                    if ($stmt->fetch()) {
                        $error = 'A user account with this email already exists';
                    }
                }
                
                if (empty($error)) {
                    // Insert doctor
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
                    
                    $doc_id = $db->lastInsertId();
                    
                    // Create user account if requested
                    if ($create_user) {
                        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                        $stmt = $db->prepare("
                            INSERT INTO users (user_email, user_password, doc_id, user_is_superadmin, created_at) 
                            VALUES (:email, :password, :doc_id, false, NOW())
                        ");
                        $stmt->execute([
                            'email' => $email,
                            'password' => $hashedPassword,
                            'doc_id' => $doc_id
                        ]);
                        $success = 'Doctor and user account created successfully';
                    } else {
                        $success = 'Doctor created successfully (no user account created)';
                    }
                }
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
        } elseif (!isValidEmail($email)) {
            $error = 'Invalid email format';
        } else {
            try {
                $stmt = $db->prepare("
                    UPDATE doctors 
                    SET doc_first_name = :first_name, doc_last_name = :last_name, doc_email = :email, 
                        doc_phone = :phone, doc_specialization_id = :specialization_id, 
                        doc_license_number = :license_number, doc_experience_years = :experience_years,
                        doc_consultation_fee = :consultation_fee, doc_qualification = :qualification,
                        doc_bio = :bio, doc_status = :status, updated_at = NOW()
                    WHERE doc_id = :id
                ");
                $stmt->execute([
                    'id' => $id,
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
                $success = 'Doctor updated successfully';
            } catch (PDOException $e) {
                $error = 'Database error: ' . $e->getMessage();
            }
        }
    }
}

// Fetch all doctors with specialization info
try {
    $stmt = $db->query("
        SELECT d.*, s.spec_name 
        FROM doctors d
        LEFT JOIN specializations s ON d.doc_specialization_id = s.spec_id
        ORDER BY d.doc_last_name, d.doc_first_name
    ");
    $doctors = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = 'Failed to fetch doctors: ' . $e->getMessage();
    $doctors = [];
}

// Fetch specializations for dropdown
try {
    $specializations = $db->query("SELECT * FROM specializations ORDER BY spec_name")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $specializations = [];
}

// Include the view
require_once __DIR__ . '/../../views/doctor/doctors.view.php';
