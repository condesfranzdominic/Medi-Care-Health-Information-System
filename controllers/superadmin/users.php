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
        $email = sanitize($_POST['email']);
        $password = $_POST['password'];
        $is_superadmin = isset($_POST['is_superadmin']) && $_POST['is_superadmin'] === '1';
        
        if (empty($email) || empty($password)) {
            $error = 'Email and password are required';
        } elseif (!isValidEmail($email)) {
            $error = 'Invalid email format';
        } else {
            try {
                // Check if email already exists
                $stmt = $db->prepare("SELECT user_id FROM users WHERE user_email = :email");
                $stmt->execute(['email' => $email]);
                
                if ($stmt->fetch()) {
                    $error = 'Email already exists';
                } else {
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $db->prepare("
                        INSERT INTO users (user_email, user_password, user_is_superadmin, created_at) 
                        VALUES (:email, :password, :is_superadmin, NOW())
                    ");
                    $stmt->execute([
                        'email' => $email,
                        'password' => $hashedPassword,
                        'is_superadmin' => $is_superadmin
                    ]);
                    $success = 'User created successfully';
                }
            } catch (PDOException $e) {
                $error = 'Database error: ' . $e->getMessage();
            }
        }
    }
    
    if ($action === 'update') {
        $id = (int)$_POST['id'];
        $email = sanitize($_POST['email']);
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? 'none';
        $role_id = isset($_POST['role_id']) ? (int)$_POST['role_id'] : null;
        
        if (empty($email)) {
            $error = 'Email is required';
        } elseif (!isValidEmail($email)) {
            $error = 'Invalid email format';
        } else {
            try {
                // Determine role flags
                $is_superadmin = ($role === 'superadmin');
                $pat_id = ($role === 'patient' && $role_id) ? $role_id : null;
                $staff_id = ($role === 'staff' && $role_id) ? $role_id : null;
                $doc_id = ($role === 'doctor' && $role_id) ? $role_id : null;
                
                // Validate role_id exists if linking to a profile
                if ($role !== 'superadmin' && $role !== 'none' && !$role_id) {
                    $error = 'Profile ID is required when assigning Staff, Doctor, or Patient role';
                } else {
                    // Verify the profile exists
                    if ($role === 'staff' && $staff_id) {
                        $stmt = $db->prepare("SELECT staff_id FROM staff WHERE staff_id = :id");
                        $stmt->execute(['id' => $staff_id]);
                        if (!$stmt->fetch()) {
                            $error = 'Staff ID does not exist';
                        }
                    } elseif ($role === 'doctor' && $doc_id) {
                        $stmt = $db->prepare("SELECT doc_id FROM doctors WHERE doc_id = :id");
                        $stmt->execute(['id' => $doc_id]);
                        if (!$stmt->fetch()) {
                            $error = 'Doctor ID does not exist';
                        }
                    } elseif ($role === 'patient' && $pat_id) {
                        $stmt = $db->prepare("SELECT pat_id FROM patients WHERE pat_id = :id");
                        $stmt->execute(['id' => $pat_id]);
                        if (!$stmt->fetch()) {
                            $error = 'Patient ID does not exist';
                        }
                    }
                    
                    if (empty($error)) {
                        // Update user with role information
                        if (!empty($password)) {
                            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                            $stmt = $db->prepare("
                                UPDATE users 
                                SET user_email = :email, 
                                    user_password = :password, 
                                    user_is_superadmin = :is_superadmin,
                                    pat_id = :pat_id,
                                    staff_id = :staff_id,
                                    doc_id = :doc_id,
                                    updated_at = NOW()
                                WHERE user_id = :id
                            ");
                            $stmt->execute([
                                'email' => $email,
                                'password' => $hashedPassword,
                                'is_superadmin' => $is_superadmin,
                                'pat_id' => $pat_id,
                                'staff_id' => $staff_id,
                                'doc_id' => $doc_id,
                                'id' => $id
                            ]);
                        } else {
                            $stmt = $db->prepare("
                                UPDATE users 
                                SET user_email = :email, 
                                    user_is_superadmin = :is_superadmin,
                                    pat_id = :pat_id,
                                    staff_id = :staff_id,
                                    doc_id = :doc_id,
                                    updated_at = NOW()
                                WHERE user_id = :id
                            ");
                            $stmt->execute([
                                'email' => $email,
                                'is_superadmin' => $is_superadmin,
                                'pat_id' => $pat_id,
                                'staff_id' => $staff_id,
                                'doc_id' => $doc_id,
                                'id' => $id
                            ]);
                        }
                        $success = 'User updated successfully';
                    }
                }
            } catch (PDOException $e) {
                $error = 'Database error: ' . $e->getMessage();
            }
        }
    }
    
    if ($action === 'delete') {
        $id = (int)$_POST['id'];
        
        try {
            $stmt = $db->prepare("DELETE FROM users WHERE user_id = :id");
            $stmt->execute(['id' => $id]);
            $success = 'User deleted successfully';
        } catch (PDOException $e) {
            $error = 'Database error: ' . $e->getMessage();
        }
    }
}

// Handle search and filters
$search_query = '';
if (isset($_GET['search'])) {
    $search_query = sanitize($_GET['search']);
}

$filter_role = isset($_GET['role']) ? sanitize($_GET['role']) : '';

// Pagination
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$items_per_page = 10;
$offset = ($page - 1) * $items_per_page;

// Fetch users with filters
try {
    $where_conditions = [];
    $params = [];

    if (!empty($search_query)) {
        $where_conditions[] = "u.user_email LIKE :search";
        $params['search'] = '%' . $search_query . '%';
    }

    if (!empty($filter_role)) {
        if ($filter_role === 'superadmin') {
            $where_conditions[] = "u.user_is_superadmin = true";
        } elseif ($filter_role === 'staff') {
            $where_conditions[] = "u.staff_id IS NOT NULL";
        } elseif ($filter_role === 'doctor') {
            $where_conditions[] = "u.doc_id IS NOT NULL";
        } elseif ($filter_role === 'patient') {
            $where_conditions[] = "u.pat_id IS NOT NULL";
        }
    }

    $where_clause = !empty($where_conditions) ? 'WHERE ' . implode(' AND ', $where_conditions) : '';

    // Get total count for pagination
    $count_stmt = $db->prepare("
        SELECT COUNT(*) 
        FROM users u
        LEFT JOIN patients p ON u.pat_id = p.pat_id
        LEFT JOIN staff s ON u.staff_id = s.staff_id
        LEFT JOIN doctors d ON u.doc_id = d.doc_id
        $where_clause
    ");
    $count_stmt->execute($params);
    $total_items = $count_stmt->fetchColumn();
    $total_pages = ceil($total_items / $items_per_page);

    // Fetch paginated results with joined data
    $stmt = $db->prepare("
        SELECT 
            u.user_id, 
            u.user_email, 
            u.user_is_superadmin, 
            u.pat_id, 
            u.staff_id, 
            u.doc_id, 
            u.created_at,
            COALESCE(p.pat_first_name || ' ' || p.pat_last_name, 
                     s.staff_first_name || ' ' || s.staff_last_name,
                     d.doc_first_name || ' ' || d.doc_last_name,
                     'Super Admin') as full_name,
            COALESCE(p.pat_phone, s.staff_phone, d.doc_phone, NULL) as phone_number,
            COALESCE(p.pat_first_name || ' ' || p.pat_last_name, 
                     s.staff_first_name || ' ' || s.staff_last_name,
                     d.doc_first_name || ' ' || d.doc_last_name,
                     'Super Admin') as status_name,
            CASE 
                WHEN u.user_is_superadmin = true THEN 'Super Admin'
                WHEN u.staff_id IS NOT NULL THEN COALESCE(s.staff_status, 'active')
                WHEN u.doc_id IS NOT NULL THEN COALESCE(d.doc_status, 'active')
                WHEN u.pat_id IS NOT NULL THEN 'active'
                ELSE 'inactive'
            END as status
        FROM users u
        LEFT JOIN patients p ON u.pat_id = p.pat_id
        LEFT JOIN staff s ON u.staff_id = s.staff_id
        LEFT JOIN doctors d ON u.doc_id = d.doc_id
        $where_clause
        ORDER BY u.created_at DESC
        LIMIT :limit OFFSET :offset
    ");
    foreach ($params as $key => $value) {
        $stmt->bindValue(':' . $key, $value);
    }
    $stmt->bindValue(':limit', $items_per_page, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = 'Failed to fetch users: ' . $e->getMessage();
    $users = [];
    $total_items = 0;
    $total_pages = 0;
}

// Include the view
require_once __DIR__ . '/../../views/superadmin/users.view.php';
