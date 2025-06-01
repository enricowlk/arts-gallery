<?php
require_once __DIR__ . '/../entitys/genre.php';
require_once __DIR__ . '/../../config/database.php';

class GenreRepository {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAllGenres() {
       try{
        $this->db->connect();
        $sql = "SELECT * FROM genres ORDER BY Era, GenreName";
        $stmt = $this->db->prepareStatement($sql); 
        $stmt->execute();
        $genres = [];
        while ($row = $stmt->fetch()) {
            $genres[] = new Genre($row['GenreID'], $row['GenreName'], $row['Era'], $row['Description'], $row['Link']);
        }

        return $genres; 
        } catch (PDOException $e){
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
            throw new Exception("Could not retrieve genre details.");
        } finally {
            $this->db->close();
        }
    }
}
?>