<?php
require_once 'dbconfig.php'; 


class Database {
    private $dsn = "mysql:host=" . host . ";dbname=" . db; 
    private $username = user; 
    private $password = pass; 
    private $pdo; 

    
    public function connect() {
        if ($this->isConnected()) {
            throw new Exception("Database already connected.");
        }
        try {
            $this->pdo = new PDO($this->dsn, $this->username, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $ex) {
            echo "Connection failed: " . $ex->getMessage(); 
        }
    }

    
    public function close() {
        if (!$this->isConnected()) {
            return; 
        }
        $this->pdo = null; 
    }

    
    public function prepareStatement($sql) {
        if (!$this->isConnected()) {
            throw new Exception("Database is not connected.");
        }
        return $this->pdo->prepare($sql); 
    }

   
    public function isConnected() {
        return $this->pdo !== null; 
    }

    
    public function beginTransaction() {
        if (!$this->isConnected()) {
            throw new Exception("Database is not connected.");
        }
        $this->pdo->beginTransaction();
    }

    
    public function commit() {
        if (!$this->isConnected()) {
            throw new Exception("Database is not connected.");
        }
        $this->pdo->commit();
    }

   
    public function rollBack() {
        if (!$this->isConnected()) {
            throw new Exception("Database is not connected.");
        }
        $this->pdo->rollBack();
    }

   
    public function lastInsertId() {
        if (!$this->isConnected()) {
            throw new Exception("Database is not connected.");
        }
        return $this->pdo->lastInsertId();
    }
}
?>