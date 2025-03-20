<?php
require_once 'Reviews.php';
require_once 'database.php';

class ReviewRepository {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }
    
    // Methode zum Abrufen der neuesten Bewertungen
    public function get3RecentReviews($limit = 3) {
        $this->db->connect();
        $sql = "SELECT * FROM reviews ORDER BY ReviewDate DESC LIMIT $limit";
        $stmt = $this->db->prepareStatement($sql);
        $stmt->execute();
        $reviews = [];
        while ($row = $stmt->fetch()) {
            $reviews[] = new Review($row['ReviewId'], $row['ArtWorkId'], $row['CustomerId'], $row['ReviewDate'], $row['Rating'], $row['Comment']);
        }
        $this->db->close();
        return $reviews;
    }

    // Methode zum Abrufen der Anzahl der Bewertungen für einen Künstler
    public function getReviewCountForArtist($artistId) {
        $this->db->connect();
        $sql = "SELECT COUNT(reviews.ReviewID) as ReviewCount 
                FROM reviews 
                INNER JOIN artworks ON reviews.ArtWorkID = artworks.ArtWorkID 
                WHERE artworks.ArtistID = ?";
        $stmt = $this->db->prepareStatement($sql);
        $stmt->execute([$artistId]);
        $row = $stmt->fetch();
        $this->db->close();
        
        if (isset($row['ReviewCount']) && $row['ReviewCount'] !== null) {
            return $row['ReviewCount'];
        } else {
            return 0;
        }
    }

    public function getAllReviewsForOneArtworkByArtworkId($artworkId) {
        $this->db->connect();
        $sql = "SELECT * FROM reviews WHERE ArtWorkID = ? ORDER BY ReviewDate DESC";
        $stmt = $this->db->prepareStatement($sql);
        $stmt->execute([$artworkId]);
        $reviews = [];
        while ($row = $stmt->fetch()) {
            $reviews[] = new Review($row['ReviewId'], $row['ArtWorkId'], $row['CustomerId'], $row['ReviewDate'], $row['Rating'], $row['Comment']);
        }
        $this->db->close();
        return $reviews;
    }

    // Methode zum Hinzufügen einer neuen Bewertung
    public function addReview($artworkId, $customerId, $rating, $comment) {
        $this->db->connect();
        $sql = "INSERT INTO reviews (ArtWorkID, CustomerID, Rating, Comment, ReviewDate) VALUES (?, ?, ?, ?, NOW())";
        $stmt = $this->db->prepareStatement($sql);
        $stmt->execute([$artworkId, $customerId, $rating, $comment]);
        $this->db->close();
    }
}
?>