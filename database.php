<?php
require_once 'dbconfig.php'; // Bindet die Konfigurationsdatei für die Datenbankverbindung ein

/**
 * Klasse Database
 * 
 * Verwaltet die Verbindung zur Datenbank und bietet Methoden zur Ausführung von SQL-Abfragen.
 */
class Database {
    // Eigenschaften für die Datenbankverbindung
    private $dsn = "mysql:host=" . host . ";dbname=" . db; // Datenquelle (DSN) für die Verbindung
    private $username = user; // Benutzername für die Datenbank
    private $password = pass; // Passwort für die Datenbank
    private $pdo; // PDO-Objekt für die Datenbankverbindung

    /**
     * Stellt eine Verbindung zur Datenbank her.
     * 
     * Aktion: Erstellt eine PDO-Instanz und konfiguriert sie für Fehlerbehandlung.
     * Wirft eine Exception, wenn bereits eine Verbindung besteht.
     */
    public function connect() {
        if ($this->isConnected()) {
            throw new Exception("Database already connected.");
        }
        try {
            $this->pdo = new PDO($this->dsn, $this->username, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Fehler als Exception werfen
        } catch (PDOException $ex) {
            echo "Connection failed: " . $ex->getMessage(); // Fehlermeldung bei Verbindungsfehler
        }
    }

    /**
     * Schließt die Datenbankverbindung.
     * 
     * Aktion: Setzt das PDO-Objekt auf `null`, um die Verbindung zu schließen.
     */
    public function close() {
        if (!$this->isConnected()) {
            return; // Keine Verbindung vorhanden
        }
        $this->pdo = null; // Verbindung schließen
    }

    /**
     * Bereitet eine SQL-Abfrage vor.
     * 
     * Eingabe: Die SQL-Abfrage als String.
     * Ausgabe: Ein PDOStatement-Objekt.
     * Wirft eine Exception, wenn keine Verbindung besteht.
     */
    public function prepareStatement($sql) {
        if (!$this->isConnected()) {
            throw new Exception("Database is not connected.");
        }
        return $this->pdo->prepare($sql); // SQL-Abfrage vorbereiten
    }

    /**
     * Überprüft, ob eine Datenbankverbindung besteht.
     * 
     * Ausgabe: `true`, wenn eine Verbindung besteht, sonst `false`.
     */
    public function isConnected() {
        return $this->pdo !== null; // Überprüft, ob das PDO-Objekt existiert
    }
}
?>