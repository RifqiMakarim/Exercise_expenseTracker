<?php
class Database {
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $db_name = "expensetracker";
    public $conn;

    public function __construct() {
        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name}", 
                $this->username, 
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            die("Koneksi gagal: " . $this->errorMessage($e->getMessage()));
        }
    }

    private function errorMessage($message) {
        return "<div style='padding: 20px; background: #f8d7da; color: #721c24; border-radius: 5px; margin: 20px;'>
                <strong>Database Error:</strong> $message
                <p>Periksa config/database.php dan pastikan database 'expense_tracker_db' sudah dibuat</p>
                </div>";
    }
}
?>