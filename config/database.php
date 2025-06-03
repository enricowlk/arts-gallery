<?php
// Include the configuration file with database credentials
require_once 'dbconfig.php';

/**
 * Database class for managing the connection and operations with a MySQL database.
 * Uses PDO (PHP Data Objects) for secure database queries.
 */
class Database {
    private $dsn = "mysql:host=" . host . ";dbname=" . db; 
    private $username = user; 
    private $password = pass; 
    private $pdo; // PDO instance for the connection

    /**
     * Establishes a connection to the database.
     * @throws Exception if a connection already exists or the connection fails.
     */
    public function connect() {
        if ($this->isConnected()) {
            throw new Exception("Database already connected.");
        }
        try {
            // Create a new PDO instance (database connection)
            $this->pdo = new PDO($this->dsn, $this->username, $this->password);
            // Set PDO to exception mode for better error handling
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $ex) {
            echo "Connection failed: " . $ex->getMessage();
        }
    }

    /**
     * Closes the database connection.
     */
    public function close() {
        if (!$this->isConnected()) {
            return;
        }
        $this->pdo = null; // Close the connection by destroying the PDO object
    }

    /**
     * Prepares an SQL statement for execution.
     * @param string $sql The SQL statement
     * @return PDOStatement Prepared statement
     * @throws Exception if no connection is established
     */
    public function prepareStatement($sql) {
        if (!$this->isConnected()) {
            throw new Exception("Database is not connected.");
        }
        return $this->pdo->prepare($sql);
    }

    /**
     * Checks whether a database connection is established.
     * @return bool True if connected, otherwise false
     */
    public function isConnected() {
        return $this->pdo !== null;
    }

    /**
     * Starts a transaction.
     * @throws Exception if no connection is established
     */
    public function beginTransaction() {
        if (!$this->isConnected()) {
            throw new Exception("Database is not connected.");
        }
        $this->pdo->beginTransaction();
    }

    /**
     * Commits the current transaction.
     * @throws Exception if no connection is established
     */
    public function commit() {
        if (!$this->isConnected()) {
            throw new Exception("Database is not connected.");
        }
        $this->pdo->commit();
    }

    /**
     * Rolls back the current transaction.
     * @throws Exception if no connection is established
     */
    public function rollBack() {
        if (!$this->isConnected()) {
            throw new Exception("Database is not connected.");
        }
        $this->pdo->rollBack();
    }

    /**
     * Returns the ID of the last inserted record.
     * @return string Last insert ID
     * @throws Exception if no connection is established
     */
    public function lastInsertId() {
        if (!$this->isConnected()) {
            throw new Exception("Database is not connected.");
        }
        return $this->pdo->lastInsertId();
    }
}
?>
