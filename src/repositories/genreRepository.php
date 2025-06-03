<?php
// Include required classes
require_once __DIR__ . '/../entitys/genre.php';
require_once __DIR__ . '/../../config/database.php';

/**
 * Repository class for accessing genres from the database.
 */
class GenreRepository {
    /**
     * @var Database $db Database connection instance
     */
    private $db;

    /**
     * Constructor for GenreRepository.
     *
     * @param Database $db An instance of the database connection
     */
    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Retrieves all genres from the database, ordered by era and genre name.
     *
     * @return Genre[] Array of Genre objects
     * @throws Exception If a database error occurs
     */
    public function getAllGenres() {
        try {
            $this->db->connect();
            $sql = "SELECT * FROM genres ORDER BY Era, GenreName";
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute();

            $genres = [];

            while ($row = $stmt->fetch()) {
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

    /**
     * Retrieves a single genre by its ID.
     *
     * @param int $id The ID of the genre
     * @return Genre The corresponding Genre object
     * @throws Exception If the genre is not found or a database error occurs
     */
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
