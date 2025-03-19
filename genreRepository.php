<?php
require_once 'genre.php';
require_once 'database.php';
class GenreRepository {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Holt alle Genres aus der Datenbank, sortiert nach Era und GenreName.
     */
    public function getAllGenres() {
        $this->db->connect();
        $sql = "SELECT * FROM genres ORDER BY Era, GenreName";
        $stmt = $this->db->prepareStatement($sql);
        $stmt->execute();
        $genres = [];
        while ($row = $stmt->fetch()) {
            $genres[] = new Genre($row['GenreID'], $row['GenreName'], $row['Era'], $row['Description'], $row['Link']);
        }
        $this->db->close();
        return $genres;
    }

    /**
     * Holt ein einzelnes Genre anhand der GenreID.
     */
    public function getGenreById($id) {
        $this->db->connect();

        $sql = "SELECT * FROM genres WHERE GenreID = ?";
        $stmt = $this->db->prepareStatement($sql);
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        $this->db->close();
        return new Genre($row['GenreID'], $row['GenreName'], $row['Era'], $row['Description'], $row['Link']);
    }
}
?>