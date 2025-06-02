<?php
// Einbindung der benötigten Klassen
require_once __DIR__ .'/../entitys/artist.php';
require_once __DIR__ . '/../../config/database.php';

class ArtistRepository {
    private $db;  

    // Konstruktor mit Dependency Injection der Datenbank
    public function __construct($db) {
        $this->db = $db;
    }

    // Holt alle Künstler (sortierbar)
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

    // Holt einen bestimmten Künstler per ID
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

    // Sucht Künstler nach Namen (Vorname/Nachname/Kombination)
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

    // Holt die Top 3 Künstler basierend auf Bewertungen
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