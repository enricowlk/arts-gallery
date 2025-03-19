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
        return $row['ReviewCount'] ?? 0; // Falls keine Bewertungen vorhanden sind, geben wir 0 zurück
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
}
?>