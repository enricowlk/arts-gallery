<?php
/**
 * Handles all database operations related to artists.
 */
class ArtistRepository {
    /**
     * @var Database The database connection instance.
     */
    private $db;  

    /**
     * Constructor with dependency injection of the database.
     *
     * @param Database $db The database connection instance.
     */
    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Retrieves all artists from the database with optional sorting.
     *
     * @param string $order Sorting direction ('ASC' or 'DESC').
     * @return Artist[] An array of Artist objects.
     * @throws Exception If a database error occurs.
     */
    public function getAllArtists($order = 'ASC') {
        try {
            $this->db->connect();
            $sql = "SELECT * FROM artists ORDER BY LastName $order, FirstName $order";
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute();
            $artists = [];
            while ($row = $stmt->fetch()) {
                $artists[] = new Artist($row['ArtistID'], $row['FirstName'], $row['LastName'], 
                                      $row['Nationality'], $row['YearOfBirth'], $row['YearOfDeath'], 
                                      $row['Details'], $row['ArtistLink']);
            }
            return $artists;
        } catch (PDOException $e) {
            throw new Exception("Database error occurred while fetching artists");
        } finally {
            $this->db->close();
        }
    }

    /**
     * Retrieves a single artist by their ID.
     *
     * @param int $id The ID of the artist to retrieve.
     * @return Artist The requested Artist object.
     * @throws Exception If the artist is not found or a database error occurs.
     */
    public function getArtistById($id) {
        try {
            $this->db->connect();
            $sql = "SELECT * FROM artists WHERE ArtistID = ?";
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute([$id]);
            $row = $stmt->fetch();
            
            if (!$row) {
                throw new Exception("Artist not found with ID: $id");
            }
            
            return new Artist($row['ArtistID'], $row['FirstName'], $row['LastName'], 
                            $row['Nationality'], $row['YearOfBirth'], $row['YearOfDeath'], 
                            $row['Details'], $row['ArtistLink']);
        } catch (PDOException $e) {
            throw new Exception("Database error occurred while fetching artist");
        } finally {
            $this->db->close();
        }
    }

    /**
     * Searches for artists by name (first name, last name, or combination).
     *
     * @param string $query The search term.
     * @param string $order Sorting direction ('ASC' or 'DESC').
     * @return Artist[] An array of matching Artist objects.
     * @throws Exception If a database error occurs.
     */
    public function searchArtists($query, $order = 'ASC') {
        try {
            $this->db->connect();
            $sql = "SELECT * FROM artists 
                    WHERE LastName LIKE :query 
                    OR FirstName LIKE :query 
                    OR CONCAT(FirstName, ' ', LastName) LIKE :query
                    OR CONCAT(LastName, ' ', FirstName) LIKE :query
                    ORDER BY LastName $order, FirstName $order";
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute(['query' => "%$query%"]);
            $artists = [];
            while ($row = $stmt->fetch()) {
                $artists[] = new Artist($row['ArtistID'], $row['FirstName'], $row['LastName'], 
                                      $row['Nationality'], $row['YearOfBirth'], $row['YearOfDeath'], 
                                      $row['Details'], $row['ArtistLink']);
            }
            return $artists;
        } catch (PDOException $e) {
            throw new Exception("Database error occurred while searching artists");
        } finally {
            $this->db->close();
        }
    }

    /**
     * Retrieves the top 3 artists based on review count.
     *
     * @param int $limit The maximum number of artists to return (default: 3).
     * @return array An array of associative arrays containing Artist objects and their review counts.
     * @throws Exception If a database error occurs.
     */
    public function getTop3Artists($limit = 3) {
        try {
            $this->db->connect();
            $sql = "SELECT artists.*, COUNT(reviews.ReviewID) as ReviewCount 
                    FROM artists 
                    LEFT JOIN artworks ON artists.ArtistID = artworks.ArtistID 
                    LEFT JOIN reviews ON artworks.ArtWorkID = reviews.ArtWorkID 
                    GROUP BY artists.ArtistID 
                    ORDER BY ReviewCount DESC 
                    LIMIT $limit";
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute();
            $artists = [];
            while ($row = $stmt->fetch()) {
                $artists[] = [
                    'artist' => new Artist($row['ArtistID'], $row['FirstName'], $row['LastName'], 
                                         $row['Nationality'], $row['YearOfBirth'], $row['YearOfDeath'], 
                                         $row['Details'], $row['ArtistLink']),
                    'reviewCount' => $row['ReviewCount']
                ];
            }
            return $artists;
        } catch (PDOException $e) {
            throw new Exception("Database error occurred while fetching top artists");
        } finally {
            $this->db->close();
        }
    }
}
?>