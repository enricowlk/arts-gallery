<?php
// Einbindung der Konfigurationsdatei mit Datenbank-Zugangsdaten
require_once 'dbconfig.php';

/**
 * Database-Klasse zur Handhabung der Verbindung und Operationen mit der MySQL-Datenbank.
 * Verwendet PDO (PHP Data Objects) für sichere Datenbankabfragen.
 */
class Database {
    private $dsn = "mysql:host=" . host . ";dbname=" . db; 
    private $username = user; 
    private $password = pass; 
    private $pdo; // PDO-Instanz für die Verbindung

    /**
     * Stellt eine Verbindung zur Datenbank her.
     * @throws Exception Wenn bereits eine Verbindung besteht oder Verbindung fehlschlägt.
     */
    public function connect() {
        if ($this->isConnected()) {
            throw new Exception("Database already connected.");
        }
        try {
            // Erstellt eine neue PDO-Instanz (Datenbankverbindung)
            $this->pdo = new PDO($this->dsn, $this->username, $this->password);
            // Setzt PDO auf Exception-Modus für bessere Fehlerbehandlung
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $ex) {
            echo "Connection failed: " . $ex->getMessage();
        }
    }

    /**
     * Schließt die Datenbankverbindung.
     */
    public function close() {
        if (!$this->isConnected()) {
            return;
        }
        $this->pdo = null; // Schließt die Verbindung durch Zerstören des PDO-Objekts
    }

    /**
     * Bereitet ein SQL-Statement für die Ausführung vor.
     * @param string $sql Das SQL-Statement
     * @return PDOStatement Vorbereitetes Statement
     * @throws Exception Wenn keine Verbindung besteht
     */
    public function prepareStatement($sql) {
        if (!$this->isConnected()) {
            throw new Exception("Database is not connected.");
        }
        return $this->pdo->prepare($sql);
    }

    /**
     * Überprüft, ob eine Datenbankverbindung besteht.
     * @return bool True wenn verbunden, sonst false
     */
    public function isConnected() {
        return $this->pdo !== null;
    }

    /**
     * Startet eine Transaktion.
     * @throws Exception Wenn keine Verbindung besteht
     */
    public function beginTransaction() {
        if (!$this->isConnected()) {
            throw new Exception("Database is not connected.");
        }
        $this->pdo->beginTransaction();
    }

    /**
     * Führt ein Commit für die aktive Transaktion durch.
     * @throws Exception Wenn keine Verbindung besteht
     */
    public function commit() {
        if (!$this->isConnected()) {
            throw new Exception("Database is not connected.");
        }
        $this->pdo->commit();
    }

    /**
     * Führt ein Rollback für die aktive Transaktion durch.
     * @throws Exception Wenn keine Verbindung besteht
     */
    public function rollBack() {
        if (!$this->isConnected()) {
            throw new Exception("Database is not connected.");
        }
        $this->pdo->rollBack();
    }

    /**
     * Gibt die ID des zuletzt eingefügten Datensatzes zurück.
     * @return string Letzte Insert-ID
     * @throws Exception Wenn keine Verbindung besteht
     */
    public function lastInsertId() {
        if (!$this->isConnected()) {
            throw new Exception("Database is not connected.");
        }
        return $this->pdo->lastInsertId();
    }
}
?>