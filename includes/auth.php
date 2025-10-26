<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/Database.php';

class Auth {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function login($email, $password) {
        $sql = "SELECT u.*, p.pat_id as patient_id, s.staff_id as staff_only_id, d.doc_id as doctor_id
                FROM users u
                LEFT JOIN patients p ON u.pat_id = p.pat_id
                LEFT JOIN staff s ON u.staff_id = s.staff_id
                LEFT JOIN doctors d ON u.doc_id = d.doc_id
                WHERE u.user_email = :email";
        
        $user = $this->db->fetchOne($sql, ['email' => $email]);
        
        if ($user && password_verify($password, $user['user_password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['user_email'] = $user['user_email'];
            $_SESSION['is_superadmin'] = $user['user_is_superadmin'];
            $_SESSION['pat_id'] = $user['pat_id'];
            $_SESSION['staff_id'] = $user['staff_id'];
            $_SESSION['doc_id'] = $user['doc_id'];
            $_SESSION['logged_in'] = true;
            $_SESSION['last_activity'] = time();
            
            // Determine user role
            if ($user['user_is_superadmin']) {
                $_SESSION['role'] = 'superadmin';
            } elseif ($user['staff_id']) {
                $_SESSION['role'] = 'staff';
            } elseif ($user['doc_id']) {
                $_SESSION['role'] = 'doctor';
            } elseif ($user['pat_id']) {
                $_SESSION['role'] = 'patient';
            }
            
            return true;
        }
        
        return false;
    }
    
    public function logout() {
        session_unset();
        session_destroy();
    }
    
    public function isLoggedIn() {
        if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
            return false;
        }
        
        // Check session timeout
        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > SESSION_LIFETIME)) {
            $this->logout();
            return false;
        }
        
        $_SESSION['last_activity'] = time();
        return true;
    }
    
    public function getRole() {
        return $_SESSION['role'] ?? null;
    }
    
    public function isSuperAdmin() {
        return isset($_SESSION['is_superadmin']) && $_SESSION['is_superadmin'];
    }
    
    public function isStaff() {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'staff';
    }
    
    public function isDoctor() {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'doctor';
    }
    
    public function isPatient() {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'patient';
    }
    
    public function hasAccess($allowedRoles = []) {
        if (!$this->isLoggedIn()) {
            return false;
        }
        
        $role = $this->getRole();
        return in_array($role, $allowedRoles);
    }
    
    public function requireLogin() {
        if (!$this->isLoggedIn()) {
            require_once __DIR__ . '/functions.php';
            redirect('/login.php');
        }
    }
    
    public function requireRole($allowedRoles = []) {
        $this->requireLogin();
        
        if (!$this->hasAccess($allowedRoles)) {
            require_once __DIR__ . '/functions.php';
            redirect('/unauthorized.php');
        }
    }
    
    public function getUserId() {
        return $_SESSION['user_id'] ?? null;
    }
    
    public function getPatientId() {
        return $_SESSION['pat_id'] ?? null;
    }
    
    public function getStaffId() {
        return $_SESSION['staff_id'] ?? null;
    }
    
    public function getDoctorId() {
        return $_SESSION['doc_id'] ?? null;
    }
}
