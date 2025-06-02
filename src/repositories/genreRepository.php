<?php
// Einbinden der benötigten Klassen
require_once __DIR__ . '/../entitys/genre.php';     
require_once __DIR__ . '/../../config/database.php'; 

// Repository-Klasse für Genre-Operationen
class GenreRepository {
    private $db;  

    // Konstruktor mit Dependency Injection der Datenbank
    public function __construct($db) {
        $this->db = $db;
    }

    // Methode zum Abrufen aller Genres, sortiert nach Epoche und Name
    public function getAllGenres() {
        try {
            $this->db->connect();  
            $sql = "SELECT * FROM genres ORDER BY Era, GenreName";  // SQL mit Sortierung
            $stmt = $this->db->prepareStatement($sql);  
            $stmt->execute();  
            
            $genres = [];  // Array für die Ergebnisse
            
            // Ergebnisse zeilenweise verarbeiten
            while ($row = $stmt->fetch()) {
                // Genre-Objekte erstellen und zum Array hinzufügen
                $genres[] = new Genre(
                    $row['GenreID'],
                    $row['GenreName'],
                    $row['Era'],
                    $row['Description'],
                    $row['Link']
                );
            }
            
            return $genres; 
            
        } catch (PDOException $e) {
            throw new Exception("Could not retrieve all genres.");
        } finally {
            $this->db->close(); 
        }
    }

    // Methode zum Abrufen eines spezifischen Genres anhand der ID
    public function getGenreById($id) {
        try {
            $this->db->connect();  
            $sql = "SELECT * FROM genres WHERE GenreID = ?"; 
            $stmt = $this->db->prepareStatement($sql);  
            $stmt->execute([$id]);  
            $row = $stmt->fetch(); 
            
            // Wenn kein Genre gefunden wurde, Exception werfen
            if (!$row) {
                throw new Exception("Genre not found");
            }
            
            // Genre-Objekt erstellen und zurückgeben
            return new Genre(
                $row['GenreID'],
                $row['GenreName'],
                $row['Era'],
                $row['Description'],
                $row['Link']
            );
            
        } catch (PDOException $e) {
            throw new Exception("Could not retrieve genre details.");
        } finally {
            $this->db->close();  
        }
    }
}
?>