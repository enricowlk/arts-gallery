<?php
require_once 'genre.php';
require_once 'database.php';
require_once 'logging.php';

class GenreRepository {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAllGenres() {
       try{
        $this->db->connect();
        $sql = "SELECT * FROM genres ORDER BY Era, GenreName"; // SQL-Abfrage
        $stmt = $this->db->prepareStatement($sql); // Bereitet die Abfrage vor
        $stmt->execute(); // Führt die Abfrage aus
        $genres = [];
        while ($row = $stmt->fetch()) {
            // Erstellt für jeden Datensatz ein Genre-Objekt
            $genres[] = new Genre($row['GenreID'], $row['GenreName'], $row['Era'], $row['Description'], $row['Link']);
        }

        return $genres; // Gibt das Array von Genre-Objekten zurück
        } catch (PDOException $e){
            Logging::LogError("Database error in getAllGenres: " . $e->getMessage());
            throw new Exception("Could not retrieve all genres.");
        } finally {
            $this->db->close();
        }
    }

    public function getGenreById($id) {
        try {
            $this->db->connect();
            $sql = "SELECT * FROM genres WHERE GenreID = ?";
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute([$id]);
            $row = $stmt->fetch();
            
            if (!$row) {
                throw new Exception("Genre not found");
            }
            
            return new Genre(
                $row['GenreID'],
                $row['GenreName'],
                $row['Era'],
                $row['Description'],
                $row['Link']
            );
        } catch (PDOException $e) {
            Logging::LogError("Database error in getGenreById: " . $e->getMessage());
            throw new Exception("Could not retrieve genre details.");
        } finally {
            $this->db->close();
        }
    }
}
?>