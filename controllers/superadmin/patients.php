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
        $date_of_birth = $_POST['date_of_birth'];
        $gender = sanitize($_POST['gender']);
        $address = sanitize($_POST['address']);
        $emergency_contact = sanitize($_POST['emergency_contact'] ?? '');
        $emergency_phone = sanitize($_POST['emergency_phone'] ?? '');
        $medical_history = sanitize($_POST['medical_history'] ?? '');
        $allergies = sanitize($_POST['allergies'] ?? '');
        $insurance_provider = sanitize($_POST['insurance_provider'] ?? '');
        $insurance_number = sanitize($_POST['insurance_number'] ?? '');
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
                    // Insert patient
                    $stmt = $db->prepare("
                        INSERT INTO patients (pat_first_name, pat_last_name, pat_email, pat_phone, pat_date_of_birth, 
                                             pat_gender, pat_address, pat_emergency_contact, pat_emergency_phone,
                                             pat_medical_history, pat_allergies, pat_insurance_provider, 
                                             pat_insurance_number, created_at) 
                        VALUES (:first_name, :last_name, :email, :phone, :date_of_birth, :gender, :address,
                               :emergency_contact, :emergency_phone, :medical_history, :allergies,
                               :insurance_provider, :insurance_number, NOW())
                    ");
                    $stmt->execute([
                        'first_name' => $first_name,
                        'last_name' => $last_name,
                        'email' => $email,
                        'phone' => $phone,
                        'date_of_birth' => $date_of_birth,
                        'gender' => $gender,
                        'address' => $address,
                        'emergency_contact' => $emergency_contact,
                        'emergency_phone' => $emergency_phone,
                        'medical_history' => $medical_history,
                        'allergies' => $allergies,
                        'insurance_provider' => $insurance_provider,
                        'insurance_number' => $insurance_number
                    ]);
                    
                    $pat_id = $db->lastInsertId();
                    
                    // Create user account if requested
                    if ($create_user) {
                        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                        $stmt = $db->prepare("
                            INSERT INTO users (user_email, user_password, pat_id, user_is_superadmin, created_at) 
                            VALUES (:email, :password, :pat_id, false, NOW())
                        ");
                        $stmt->execute([
                            'email' => $email,
                            'password' => $hashedPassword,
                            'pat_id' => $pat_id
                        ]);
                        $success = 'Patient and user account created successfully';
                    } else {
                        $success = 'Patient created successfully (no user account created)';
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
        $date_of_birth = $_POST['date_of_birth'];
        $gender = sanitize($_POST['gender']);
        $address = sanitize($_POST['address']);
        $emergency_contact = sanitize($_POST['emergency_contact'] ?? '');
        $emergency_phone = sanitize($_POST['emergency_phone'] ?? '');
        $medical_history = sanitize($_POST['medical_history'] ?? '');
        $allergies = sanitize($_POST['allergies'] ?? '');
        $insurance_provider = sanitize($_POST['insurance_provider'] ?? '');
        $insurance_number = sanitize($_POST['insurance_number'] ?? '');
        
        if (empty($first_name) || empty($last_name) || empty($email)) {
            $error = 'First name, last name, and email are required';
        } else {
            try {
                $stmt = $db->prepare("
                    UPDATE patients 
                    SET pat_first_name = :first_name, pat_last_name = :last_name, pat_email = :email, 
                        pat_phone = :phone, pat_date_of_birth = :date_of_birth, pat_gender = :gender, 
                        pat_address = :address, pat_emergency_contact = :emergency_contact,
                        pat_emergency_phone = :emergency_phone, pat_medical_history = :medical_history,
                        pat_allergies = :allergies, pat_insurance_provider = :insurance_provider,
                        pat_insurance_number = :insurance_number, updated_at = NOW()
                    WHERE pat_id = :id
                ");
                $stmt->execute([
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'email' => $email,
                    'phone' => $phone,
                    'date_of_birth' => $date_of_birth,
                    'gender' => $gender,
                    'address' => $address,
                    'emergency_contact' => $emergency_contact,
                    'emergency_phone' => $emergency_phone,
                    'medical_history' => $medical_history,
                    'allergies' => $allergies,
                    'insurance_provider' => $insurance_provider,
                    'insurance_number' => $insurance_number,
                    'id' => $id
                ]);
                $success = 'Patient updated successfully';
            } catch (PDOException $e) {
                $error = 'Database error: ' . $e->getMessage();
            }
        }
    }
    
    if ($action === 'delete') {
        $id = (int)$_POST['id'];
        
        try {
            $stmt = $db->prepare("DELETE FROM patients WHERE pat_id = :id");
            $stmt->execute(['id' => $id]);
            $success = 'Patient deleted successfully';
        } catch (PDOException $e) {
            $error = 'Database error: ' . $e->getMessage();
        }
    }
}

// Handle search
$search_query = '';
if (isset($_GET['search'])) {
    $search_query = sanitize($_GET['search']);
}

// Fetch all patients
try {
    if (!empty($search_query)) {
        // Search by first name or last name
        $stmt = $db->prepare("
            SELECT * FROM patients 
            WHERE pat_first_name LIKE :search OR pat_last_name LIKE :search
            ORDER BY pat_first_name, pat_last_name
        ");
        $stmt->execute(['search' => '%' . $search_query . '%']);
        $patients = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $stmt = $db->query("SELECT * FROM patients ORDER BY created_at DESC");
        $patients = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    $error = 'Failed to fetch patients: ' . $e->getMessage();
    $patients = [];
}

// Include the view
require_once __DIR__ . '/../../views/superadmin/patients.view.php';
