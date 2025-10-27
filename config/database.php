<?php
class Database {
    private static $instance = null;
    private $conn;

    private function __construct() {
        $dotenv = parse_ini_file(__DIR__ . '/../.env');
        $dsn = "pgsql:host={$dotenv['SUPABASE_DB_HOST']};port={$dotenv['SUPABASE_DB_PORT']};dbname={$dotenv['SUPABASE_DB_NAME']};user={$dotenv['SUPABASE_DB_USER']};password={$dotenv['SUPABASE_DB_PASS']}";
        
        try {
            $this->conn = new PDO($dsn);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance->conn;
    }
}
