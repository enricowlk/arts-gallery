<?php
require_once 'reviews.php';
require_once 'database.php';
require_once 'logging.php';

class ReviewRepository {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function get3RecentReviews($limit = 3) {
        try {
            $this->db->connect();
            $sql = "SELECT * FROM reviews ORDER BY ReviewDate DESC LIMIT $limit";
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute();
            $reviews = [];
            while ($row = $stmt->fetch()) {
                $reviews[] = new Review($row['ReviewId'], $row['ArtWorkId'], $row['CustomerId'], $row['ReviewDate'], $row['Rating'], $row['Comment']);
            }
            return $reviews;
        } catch (PDOException $e) {
            Logging::LogError("Error in get3RecentReviews: " . $e->getMessage());
            throw new Exception("Database error occurred while fetching recent reviews");
        } finally {
            $this->db->close();
        }
    }

    public function getReviewCountForArtist($artistId) {
        try {
            $this->db->connect();
            $sql = "SELECT COUNT(reviews.ReviewID) as ReviewCount 
                    FROM reviews 
                    INNER JOIN artworks ON reviews.ArtWorkID = artworks.ArtWorkID 
                    WHERE artworks.ArtistID = ?";
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute([$artistId]);
            $row = $stmt->fetch();
            
            if (isset($row['ReviewCount']) && $row['ReviewCount'] !== null) {
                return $row['ReviewCount'];
            } else {
                return 0;
            }
        } catch (PDOException $e) {
            Logging::LogError("Error in getReviewCountForArtist: " . $e->getMessage());
            throw new Exception("Database error occurred while counting artist reviews");
        } finally {
            $this->db->close();
        }
    }

    public function getAllReviewsForOneArtworkByArtworkId($artworkId) {
        try {
            $this->db->connect();
            $sql = "SELECT * FROM reviews WHERE ArtWorkID = ? ORDER BY ReviewDate DESC";
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute([$artworkId]);
            $reviews = [];
            while ($row = $stmt->fetch()) {
                $reviews[] = new Review($row['ReviewId'], $row['ArtWorkId'], $row['CustomerId'], $row['ReviewDate'], $row['Rating'], $row['Comment']);
            }
            return $reviews;
        } catch (PDOException $e) {
            Logging::LogError("Error in getAllReviewsForOneArtworkByArtworkId: " . $e->getMessage());
            throw new Exception("Database error occurred while fetching artwork reviews");
        } finally {
            $this->db->close();
        }
    }

    public function addReview($artworkId, $customerId, $rating, $comment) {
        try {
            $this->db->connect();
            $sql = "INSERT INTO reviews (ArtWorkID, CustomerID, Rating, Comment, ReviewDate) VALUES (?, ?, ?, ?, NOW())";
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute([$artworkId, $customerId, $rating, $comment]);
        } catch (PDOException $e) {
            Logging::LogError("Error in addReview: " . $e->getMessage());
            throw new Exception("Database error occurred while adding review");
        } finally {
            $this->db->close();
        }
    }

    public function deleteReview($reviewId) {
        try {
            $this->db->connect();
            $sql = "DELETE FROM reviews WHERE ReviewID = :reviewId";
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute(['reviewId' => $reviewId]);
            return $stmt;
        } catch (PDOException $e) {
            Logging::LogError("Error in deleteReview: " . $e->getMessage());
            throw new Exception("Database error occurred while deleting review");
        } finally {
            $this->db->close();
        }
    }

    public function hasUserReviewedArtwork($artworkId, $customerId) {
        try {
            $this->db->connect();
            $sql = "SELECT COUNT(*) as review_count FROM reviews WHERE ArtWorkID = ? AND CustomerID = ?";
            $stmt = $this->db->prepareStatement($sql);
            $stmt->execute([$artworkId, $customerId]);
            $row = $stmt->fetch();
            return ($row['review_count'] > 0);
        } catch (PDOException $e) {
            Logging::LogError("Error in hasUserReviewedArtwork: " . $e->getMessage());
            throw new Exception("Database error occurred while checking user reviews");
        } finally {
            $this->db->close();
        }
    }
}
?>