<?php
// Check-Go/php-backend/config/database.php

class Database {
    // Usar o Host do Pooler que funcionou
    private $host = "aws-1-eu-west-1.pooler.supabase.com";

    private $port = "6543"; 
    private $db_name = "postgres";
    private $username = "postgres.zzftdgnxiwqteyqfglzi";
    private $password = "Pingo2931@";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $dsn = "pgsql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name . ";sslmode=require";
            
            $this->conn = new PDO($dsn, $this->username, $this->password);
            
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            

            $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
            

            
        } catch(PDOException $exception) {
            echo "Erro de Ligação: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>