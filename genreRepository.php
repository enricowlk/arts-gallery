<?php
require_once 'genre.php';
require_once 'database.php';
require_once 'logging.php';

class GenreRepository {
    private $db;

    public function __construct($db) {
        $this->db = $db;
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