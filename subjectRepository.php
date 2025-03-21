<?php
// Einbinden der benötigten Dateien
require_once 'Subject.php'; // Enthält die Klasse "Subject"
require_once 'database.php'; // Enthält die Datenbankverbindung

// Definition der Klasse "SubjectRepository"
class SubjectRepository {
    // Private Eigenschaft für die Datenbankverbindung
    private $db;

    // Konstruktor, der die Datenbankverbindung übernimmt
    public function __construct($db) {
        $this->db = $db; // Setzt die Datenbankverbindung
    }

    /**
     * Holt alle Subjects aus der Datenbank, sortiert nach SubjectName.
     * @return array Ein Array von Subject-Objekten.
     */
    public function getAllSubjects() {
        $this->db->connect(); // Verbindung zur Datenbank herstellen
        $sql = "SELECT * FROM subjects ORDER BY SubjectName"; // SQL-Abfrage
        $stmt = $this->db->prepareStatement($sql); // SQL-Statement vorbereiten
        $stmt->execute(); // SQL-Statement ausführen
        $subjects = []; // Array für die Subjects initialisieren

        // Durchläuft alle Zeilen der Abfrageergebnisse
        while ($row = $stmt->fetch()) {
            // Erstellt ein neues Subject-Objekt und fügt es dem Array hinzu
            $subjects[] = new Subject($row['SubjectId'], $row['SubjectName']);
        }

        $this->db->close(); // Datenbankverbindung schließen
        return $subjects; // Gibt das Array der Subjects zurück
    }

    /**
     * Holt ein einzelnes Subject anhand der SubjectID.
     * @param int $id Die ID des Subjects.
     * @return Subject Ein Subject-Objekt.
     */
    public function getSubjectById($id) {
        $this->db->connect(); // Verbindung zur Datenbank herstellen
        $sql = "SELECT * FROM subjects WHERE SubjectId = ?"; // SQL-Abfrage mit Platzhalter
        $stmt = $this->db->prepareStatement($sql); // SQL-Statement vorbereiten
        $stmt->execute([$id]); // SQL-Statement mit der ID ausführen
        $row = $stmt->fetch(); // Ergebniszeile abrufen
        $this->db->close(); // Datenbankverbindung schließen

        // Erstellt und gibt ein neues Subject-Objekt zurück
        return new Subject($row['SubjectId'], $row['SubjectName']);
    }
}
?>