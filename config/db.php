<?php
// Database configuration using SQLite
class Database {
    private static $instance = null;
    private $connection;
    
    private function __construct() {
        try {
            // Create database directory if it doesn't exist
            $dbDir = __DIR__ . '/../data';
            if (!file_exists($dbDir)) {
                mkdir($dbDir, 0755, true);
            }
            
            // Connect to SQLite database
            $dbPath = $dbDir . '/estia_system.db';
            
            $this->connection = new PDO('sqlite:' . $dbPath);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $this->checkAndFixSchema();
        } catch(PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->connection;
    }
    
    private function checkAndFixSchema() {
        try {
            // Check if users table has the correct schema
            $result = $this->connection->query("PRAGMA table_info(users)");
            $columns = $result->fetchAll(PDO::FETCH_ASSOC);
            
            $hasIdentifiant = false;
            foreach ($columns as $column) {
                if ($column['name'] === 'identifiant') {
                    $hasIdentifiant = true;
                    break;
                }
            }
            
            // If schema is wrong or tables don't exist, recreate them
            if (!$hasIdentifiant) {
                $this->resetDatabase();
            }
            
            // Check if admin exists
            $stmt = $this->connection->query("SELECT COUNT(*) FROM admin");
            $count = $stmt->fetchColumn();
            
            if ($count == 0) {
                // Create default admin: Admin / Est1a#System@25!
                $hashedPassword = password_hash('Est1a#System@25!', PASSWORD_DEFAULT);
                $stmt = $this->connection->prepare("INSERT INTO admin (username, password) VALUES (?, ?)");
                $stmt->execute(['Admin', $hashedPassword]);
            }
        } catch(PDOException $e) {
            // Tables don't exist, create them
            $this->initializeDatabase();
        }
    }
    
    private function initializeDatabase() {
        // Create admin table
        $this->connection->exec("
            CREATE TABLE IF NOT EXISTS admin (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                username TEXT UNIQUE NOT NULL,
                password TEXT NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ");
        
        $this->connection->exec("
            CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                nom TEXT NOT NULL,
                prenom TEXT NOT NULL,
                identifiant TEXT UNIQUE NOT NULL,
                password TEXT NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                created_by INTEGER,
                FOREIGN KEY (created_by) REFERENCES admin(id)
            )
        ");
        
        // Check if admin exists, if not create default admin
        $stmt = $this->connection->query("SELECT COUNT(*) FROM admin");
        $count = $stmt->fetchColumn();
        
        if ($count == 0) {
            // Create default admin: Admin / Est1a#System@25!
            $hashedPassword = password_hash('Est1a#System@25!', PASSWORD_DEFAULT);
            $stmt = $this->connection->prepare("INSERT INTO admin (username, password) VALUES (?, ?)");
            $stmt->execute(['Admin', $hashedPassword]);
        }
    }
    
    public function resetDatabase() {
        try {
            // Drop existing tables
            $this->connection->exec("DROP TABLE IF EXISTS users");
            $this->connection->exec("DROP TABLE IF EXISTS admin");
            
            // Reinitialize
            $this->initializeDatabase();
            
            return true;
        } catch(PDOException $e) {
            return false;
        }
    }
}
