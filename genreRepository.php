<?php
require_once 'genre.php'; // Bindet die Genre-Klasse ein
require_once 'database.php'; // Bindet die Database-Klasse ein

/**
 * Klasse GenreRepository
 * 
 * Verwaltet den Zugriff auf die Genre-Daten in der Datenbank.
 * Enthält Methoden zum Abrufen von Genres.
 */
class GenreRepository {
    private $db; // Datenbankverbindung

    /**
     * Konstruktor
     * 
     * Eingabe: Die Datenbankverbindung.
     */
    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Holt alle Genres aus der Datenbank, sortiert nach Epoche (Era) und GenreName.
     * 
     * Ausgabe: Ein Array von Genre-Objekten.
     */
    public function getAllGenres() {
        $this->db->connect();
        $sql = "SELECT * FROM genres ORDER BY Era, GenreName"; // SQL-Abfrage
        $stmt = $this->db->prepareStatement($sql); // Bereitet die Abfrage vor
        $stmt->execute(); // Führt die Abfrage aus
        $genres = [];
        while ($row = $stmt->fetch()) {
            // Erstellt für jeden Datensatz ein Genre-Objekt
            $genres[] = new Genre($row['GenreID'], $row['GenreName'], $row['Era'], $row['Description'], $row['Link']);
        }
        $this->db->close(); // Schließt die Datenbankverbindung
        return $genres; // Gibt das Array von Genre-Objekten zurück
    }

    /**
     * Holt ein einzelnes Genre anhand der GenreID.
     * 
     * Eingabe: Die GenreID.
     * Ausgabe: Ein Genre-Objekt.
     */
    public function getGenreById($id) {
        $this->db->connect();
        $sql = "SELECT * FROM genres WHERE GenreID = ?"; // SQL-Abfrage
        $stmt = $this->db->prepareStatement($sql); // Bereitet die Abfrage vor
        $stmt->execute([$id]); // Führt die Abfrage mit der GenreID aus
        $row = $stmt->fetch(); // Holt den Datensatz
        $this->db->close(); // Schließt die Datenbankverbindung
        return new Genre($row['GenreID'], $row['GenreName'], $row['Era'], $row['Description'], $row['Link']); // Gibt ein Genre-Objekt zurück
    }
}
?>