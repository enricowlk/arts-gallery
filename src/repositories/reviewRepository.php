<?php
// Einbindung der benötigten Klassen
require_once __DIR__ . '/../entitys/reviews.php';    
require_once __DIR__ . '/../../config/database.php'; 

// Repository-Klasse für Review-Operationen
class ReviewRepository {
    private $db;  

    // Konstruktor mit Dependency Injection der Datenbank
    public function __construct($db) {
        $this->db = $db;
    }

    // Holt die neuesten X Reviews (standardmäßig 3)
    public function get3RecentReviews($limit = 3) {
        try {
            $this->db->connect();
            // SQL: Holt Reviews nach Datum absteigend sortiert mit Limit
            $sql = "SELECT * FROM reviews ORDER BY ReviewDate DESC LIMIT $limit";
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute();
            
            $reviews = [];
            while ($row = $stmt->fetch()) {
                // Erstellt Review-Objekte für jede gefundene Zeile
                $reviews[] = new Review(
                    $row['ReviewId'], 
                    $row['ArtWorkId'], 
                    $row['CustomerId'], 
                    $row['ReviewDate'], 
                    $row['Rating'], 
                    $row['Comment']
                );
            }
            return $reviews;
        } catch (PDOException $e) {
            throw new Exception("Database error occurred while fetching recent reviews");
        } finally {
            $this->db->close();
        }
    }

    // Zählt alle Reviews für einen bestimmten Künstler
    public function getReviewCountForArtist($artistId) {
        try {
            $this->db->connect();
            // SQL mit JOIN zwischen reviews und artworks Tabellen
            $sql = "SELECT COUNT(reviews.ReviewID) as ReviewCount 
                    FROM reviews 
                    INNER JOIN artworks ON reviews.ArtWorkID = artworks.ArtWorkID 
                    WHERE artworks.ArtistID = ?";
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute([$artistId]);
            $row = $stmt->fetch();
            
            // Gibt die Anzahl der Reviews oder 0 zurück
            return $row['ReviewCount'] ?? 0;
        } catch (PDOException $e) {
            throw new Exception("Database error occurred while counting artist reviews");
        } finally {
            $this->db->close();
        }
    }

    // Holt alle Reviews für ein bestimmtes Kunstwerk durch die ArtworkID
    public function getAllReviewsForOneArtworkByArtworkId($artworkId) {
        try {
            $this->db->connect();
            $sql = "SELECT * FROM reviews WHERE ArtWorkID = ? ORDER BY ReviewDate DESC";
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute([$artworkId]);
            
            // Initialisiert ein leeres Array für die Review-Objekte
            $reviews = [];

            // Durchläuft alle Ergebniszeilen der Datenbankabfrage
            while ($row = $stmt->fetch()) {
                // Für jede Datenbankzeile wird ein neues Review-Objekt erstellt
                // und dem $reviews-Array hinzugefügt
                $reviews[] = new Review(
                    $row['ReviewId'],     
                    $row['ArtWorkId'],     
                    $row['CustomerId'],    
                    $row['ReviewDate'],   
                    $row['Rating'],        
                    $row['Comment']       
                );
            }
            return $reviews;

        } catch (PDOException $e) {
            throw new Exception("Database error occurred while fetching artwork reviews");
        } finally {
            $this->db->close();
        }
    }

    // Fügt ein neues Review in die Datenbank ein
    public function addReview($artworkId, $customerId, $rating, $comment) {
        try {
            $this->db->connect();
            // SQL: Insert mit aktueller Datumszeit (NOW())
            $sql = "INSERT INTO reviews (ArtWorkID, CustomerID, Rating, Comment, ReviewDate) 
                    VALUES (?, ?, ?, ?, NOW())";
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute([$artworkId, $customerId, $rating, $comment]);
        } catch (PDOException $e) {
            throw new Exception("Database error occurred while adding review");
        } finally {
            $this->db->close();
        }
    }

    // Löscht ein Review anhand der Review-ID
    public function deleteReview($reviewId) {
        try {
            $this->db->connect();
            // SQL: Löscht ein Review mit benanntem Parameter
            $sql = "DELETE FROM reviews WHERE ReviewID = :reviewId";
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute(['reviewId' => $reviewId]);
            return $stmt;
        } catch (PDOException $e) {
            throw new Exception("Database error occurred while deleting review");
        } finally {
            $this->db->close();
        }
    }

    // Prüft, ob ein Benutzer bereits ein Review für ein Kunstwerk abgegeben hat
    public function hasUserReviewedArtwork($artworkId, $customerId) {
        try {
            $this->db->connect();
            $sql = "SELECT COUNT(*) as review_count 
                    FROM reviews 
                    WHERE ArtWorkID = ? AND CustomerID = ?";
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute([$artworkId, $customerId]);
            $row = $stmt->fetch();
            // Gibt true zurück, wenn mindestens ein Review existiert
            return ($row['review_count'] > 0);
        } catch (PDOException $e) {
            throw new Exception("Database error occurred while checking user reviews");
        } finally {
            $this->db->close();
        }
    }
}
?>