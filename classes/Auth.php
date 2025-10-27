<?php
class Auth {
    private $db;
    
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        require_once __DIR__ . '/../config/Database.php';
        $this->db = Database::getInstance();
    }
    
    public function login($email, $password) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM users WHERE user_email = :email LIMIT 1");
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user && password_verify($password, $user['user_password'])) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['user_email'] = $user['user_email'];
                $_SESSION['is_superadmin'] = $user['user_is_superadmin'];
                $_SESSION['pat_id'] = $user['pat_id'];
                $_SESSION['staff_id'] = $user['staff_id'];
                $_SESSION['doc_id'] = $user['doc_id'];
                $_SESSION['logged_in'] = true;
                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Login error: " . $e->getMessage());
            return false;
        }
    }
    
    public function logout() {
        session_destroy();
        session_start();
    }
    
    public function isLoggedIn() {
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }
    
    public function isSuperAdmin() {
        return $this->isLoggedIn() && isset($_SESSION['is_superadmin']) && $_SESSION['is_superadmin'] === true;
    }
    
    public function isStaff() {
        return $this->isLoggedIn() && isset($_SESSION['staff_id']) && $_SESSION['staff_id'] !== null;
    }
    
    public function isDoctor() {
        return $this->isLoggedIn() && isset($_SESSION['doc_id']) && $_SESSION['doc_id'] !== null;
    }
    
    public function isPatient() {
        return $this->isLoggedIn() && isset($_SESSION['pat_id']) && $_SESSION['pat_id'] !== null;
    }
    
    public function getRole() {
        if ($this->isSuperAdmin()) return 'superadmin';
        if ($this->isStaff()) return 'staff';
        if ($this->isDoctor()) return 'doctor';
        if ($this->isPatient()) return 'patient';
        return null;
    }
    
    public function getUserId() {
        return $_SESSION['user_id'] ?? null;
    }
    
    public function getPatientId() {
        return $_SESSION['pat_id'] ?? null;
    }
    
    public function getDoctorId() {
        return $_SESSION['doc_id'] ?? null;
    }
    
    public function getStaffId() {
        return $_SESSION['staff_id'] ?? null;
    }
    
    public function requireLogin() {
        if (!$this->isLoggedIn()) {
            header('Location: /login');
            exit;
        }
    }
    
    public function requireSuperAdmin() {
        $this->requireLogin();
        if (!$this->isSuperAdmin()) {
            header('Location: /');
            exit;
        }
    }
    
    public function requireStaff() {
        $this->requireLogin();
        if (!$this->isStaff() && !$this->isSuperAdmin()) {
            header('Location: /');
            exit;
        }
    }
    
    public function requireDoctor() {
        $this->requireLogin();
        if (!$this->isDoctor() && !$this->isSuperAdmin()) {
            header('Location: /');
            exit;
        }
    }
    
    public function requirePatient() {
        $this->requireLogin();
        if (!$this->isPatient()) {
            header('Location: /');
            exit;
        }
    }
    
    public function requireRole($roles = []) {
        $this->requireLogin();
        $currentRole = $this->getRole();
        
        if (!in_array($currentRole, $roles)) {
            header('Location: /');
            exit;
        }
    }
    
    public function canAccess($allowedRoles = []) {
        if (!$this->isLoggedIn()) {
            return false;
        }
        
        $currentRole = $this->getRole();
        return in_array($currentRole, $allowedRoles);
    }
}
